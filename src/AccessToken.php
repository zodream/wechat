<?php
namespace Zodream\ThirdParty\WeChat;

use Exception;

/**
 * AccessToken
 * @package Zodream\Domain\ThirdParty\WeChat
 */
class AccessToken extends BaseWeChat {

    public function getToken() {
        return $this->getHttp()
            ->url('https://api.weixin.qq.com/cgi-bin/token', [
                    'grant_type' => 'client_credential',
                    '#appid',
                    '#secret'
                ])->parameters($this->get());
    }

    public function getIp() {
        return $this->getBaseHttp()
            ->url('https://api.weixin.qq.com/cgi-bin/getcallbackip', [
                '#access_token'
            ]);
    }
    /**
     * GET ACCESS TOKEN AND SAVE CACHE
     * @return string
     * @throws \Exception
     */
    public function token() {
        return static::getOrSetCache('WeChatToken'.$this->get('appid'),
            function (callable $next) {
            $args = $this->getToken()->json();
            if (!is_array($args)) {
                throw new Exception('HTTP ERROR!');
            }
            if (!array_key_exists('access_token', $args)) {
                throw new Exception($args['errmsg'] ?? 'GET ACCESS TOKEN ERROR!');
            }
           return call_user_func($next, $args['access_token'], $args['expires_in']);
        });
    }

    /**
     * @return bool|array
     * @throws Exception
     */
    public function ip() {
        return static::getOrSetCache('WeChatServerIp', function (callable $next) {
            $args = $this->getIp()->json();
            if (!is_array($args)) {
                throw new Exception('HTTP ERROR!');
            }
            if (!array_key_exists('ip_list', $args)) {
                return false;
            }
            return call_user_func($next, $args['ip_list'], 86400);
        });
    }

    /**
     * 验证ip
     * @param $ip
     * @return bool
     * @throws Exception
     */
    public function verifyIp(string $ip) {
        $data = $this->ip();
        return $data === false || in_array($ip, $data);
    }
}