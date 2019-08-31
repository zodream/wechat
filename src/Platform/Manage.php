<?php
namespace Zodream\ThirdParty\WeChat\Platform;

use Zodream\Http\Http;
use Zodream\Http\Uri;
use Zodream\Service\Factory;

/**
 * 微信第三方平台管理公众号
 * User: zx648
 * Date: 2017/4/5
 * Time: 19:34
 */
class Manage extends BasePlatform {

    public function getLogin() {
        return $this->getHttp()
            ->url('https://mp.weixin.qq.com/cgi-bin/componentloginpage',
                [
                    '#component_appid',
                    '#pre_auth_code',
                    '#redirect_uri'
                ]);
    }

    public function getToken() {
        return $this->getHttp('https://api.weixin.qq.com/cgi-bin/component/api_component_token')
            ->maps([
                '#component_appid',
                '#component_appsecret',
                '#component_verify_ticket'
            ]);
    }

    public function getPreAuthCode() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode')
            ->maps([
                '#component_appid'
            ]);
    }

    public function getAccessToken() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/component/api_query_auth')
            ->maps([
                '#component_appid',
                '#authorization_code' // 在授权通知里接收
            ]);
    }

    public function getRefreshToken() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token')
            ->maps([
                '#component_appid',
                '#authorizer_appid',
                '#authorizer_refresh_token',
            ]);
    }

    public function getInfo() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info')
            ->maps([
                '#component_appid',
                '#authorizer_appid',
            ]);
    }

    public function getOption() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_option')
            ->maps([
                '#component_appid',
                '#authorizer_appid',
                '#option_name'
            ]);
    }

    public function getSetOption() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/component/api_set_authorizer_option')
            ->maps([
                '#component_appid',
                '#authorizer_appid',
                '#option_name',
                '#option_value'
            ]);
    }

    public function getClear() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/component/clear_quota')
            ->maps([
                '#component_appid',
            ]);
    }

    /**
     * 2.获取令牌
     * @return mixed
     * @throws \Exception
     */
    public function token() {
        static::getOrSetCache('WeChatThirdToken', function (callable $next) {
            $args = $this->getToken()->parameters($this->merge([
                'component_verify_ticket' => Factory::cache()
                    ->get('WeChatThirdComponentVerifyTicket')
            ]))->json();
            if (!is_array($args)) {
                throw new \Exception('HTTP ERROR!');
            }
            if (!array_key_exists('component_access_token', $args)) {
                throw new \Exception(isset($args['errmsg']) ? $args['errmsg'] : 'GET ACCESS TOKEN ERROR!');
            }
            return call_user_func($next, $args['component_access_token'], $args['expires_in']);
        });
    }

    /**
     * 3.获取预授权码
     * @return mixed
     * @throws \Exception
     */
    public function preAuthCode() {
        return static::getOrSetCache('WeChatThirdPreAuthCode', function (callable $next) {
            $args = $this->getPreAuthCode()->json();
            if (!is_array($args)) {
                throw new \Exception('HTTP ERROR!');
            }
            if (!array_key_exists('pre_auth_code', $args)) {
                throw new \Exception(isset($args['errmsg']) ? $args['errmsg'] : 'GET ACCESS TOKEN ERROR!');
            }
            return call_user_func($next, $args['pre_auth_code'], $args['expires_in']);
        });
    }

    /**
     * 4.进入授权页面
     * @return Uri
     * @throws \Exception
     */
    public function login() {
        return $this->getLogin()->parameters($this->merge([
            'pre_auth_code' => $this->getPreAuthCode()
        ]))->getUrl();
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function callback() {
        $code = isset($_GET['auth_code']) ? $_GET['auth_code'] : null;
        if (empty($code)) {
            throw new \Exception('AUTH CODE ERROR!');
        }
        Http::log('WECHAT AUTH CODE: '. $code);
        $this->set('authorization_code', $code);
        return $this->accessToken();
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function accessToken() {
        $data = $this->getAccessToken()->json();
        if (!array_key_exists('authorization_info', $data)) {
            throw new \Exception('ACCESS TOKEN ERROR!');
        }
        $this->set($data['authorization_info']);
        return $data['authorization_info'];
    }

    /**
     *
     * @param string $appId 公众号的appid
     * @param $token
     * @return mixed
     * @throws \Exception
     */
    public function refreshAccessToken($appId, $token) {
        $data = $this->getRefreshToken()->parameters([
            'authorizer_appid' => $appId,
            'authorizer_refresh_token' => $token
        ])->json();
        if (!array_key_exists('authorizer_access_token', $data)) {
            throw new \Exception('REFRESH ACCESS TOKEN ERROR!');
        }
        $this->set($data);
        return $data;
    }

    /**
     * 获取公众号的信息
     * @param string $appId 公众号的appid
     * @return array
     * @throws \Exception
     */
    public function info($appId) {
        $data = $this->getInfo()->parameters([
            'authorizer_appid' => $appId
        ])->json();
        if (!array_key_exists('authorizer_info', $data)) {
            throw new \Exception('INFO ERROR!');
        }
        $this->set($data['authorizer_info']);
        return $data['authorizer_info'];
    }

    /**
     * @param $appId
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function option($appId, $name) {
        return $this->getOption()->parameters([
            'authorizer_appid' => $appId,
            'option_name' => $name
        ])->json();
    }

    /**
     * @param $appId
     * @param $name
     * @param $value
     * @return bool
     * @throws \Exception
     */
    public function setOption($appId, $name, $value) {
        $data = $this->getSetOption()->parameters([
            'authorizer_appid' => $appId,
            'option_name' => $name,
            'option_value' => $value
        ])->json();
        return $data['errcode'] === 0;
    }
}