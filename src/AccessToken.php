<?php
namespace Zodream\ThirdParty\WeChat;


use Zodream\Service\Factory;
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
        $key = 'WeChatToken'.$this->get('appid');
        if (Factory::cache()->has($key)) {
            return Factory::cache()->get($key);
        }
        $args = $this->getToken()->json();
        if (!is_array($args)) {
            throw new Exception('HTTP ERROR!');
        }
        if (!array_key_exists('access_token', $args)) {
            throw new Exception(isset($args['errmsg']) ?
                $args['errmsg'] : 'GET ACCESS TOKEN ERROR!');
        }
        Factory::cache()->set($key, $args['access_token'], $args['expires_in']);
        return $args['access_token'];
    }

    /**
     * @return bool|array
     * @throws Exception
     */
    public function ip() {
        $key = 'WeChatServerIp';
        if (Factory::cache()->has($key)) {
            return Factory::cache()->get($key);
        }
        $args = $this->getIp()->json();
        if (!is_array($args)) {
            throw new Exception('HTTP ERROR!');
        }
        if (!array_key_exists('ip_list', $args)) {
            return false;
        }
        Factory::cache()->set($key, $args['ip_list'], 86400);
        return $args['ip_list'];
    }

    /**
     * 验证ip
     * @param $ip
     * @return bool
     * @throws Exception
     */
    public function verifyIp($ip) {
        $data = $this->ip();
        return $data === false || in_array($ip, $data);
    }
}