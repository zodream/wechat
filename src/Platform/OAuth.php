<?php
namespace Zodream\ThirdParty\WeChat\Platform;


use Zodream\Helpers\Str;
use Zodream\Http\Http;
use Zodream\Http\Uri;

/**
 * 网页授权
 * @package Zodream\Domain\ThirdParty\WeChat\Platform
 * @property string $identity
 * @property string $username
 * @property string $sex
 * @property string $avatar
 */
class OAuth extends BasePlatform {

    public function getLogin() {
        return $this->getHttp()
            ->url('https://open.weixin.qq.com/connect/oauth2/authorize',
                [
                    '#appid',
                    '#redirect_uri',
                    'response_type' => 'code',
                    'scope' => 'snsapi_userinfo', // snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid），snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地
                    'state',
                    '#component_appid'
                    //'#wechat_redirect'
                ])->parameters($this->get());
    }

    /**
     * @return Http
     * @throws \Exception
     */
    public function getAccess() {
        return $this->getBaseHttp()
            ->url('https://api.weixin.qq.com/sns/oauth2/component/access_token',
                [
                    '#appid',
                    '#component_access_token',
                    '#code',
                    'grant_type' => 'authorization_code',
                    '#component_appid',
                ]);
    }

    /**
     * @return Http
     * @throws \Exception
     */
    public function getRefreshToken() {
        return $this->getBaseHttp()
            ->url('https://api.weixin.qq.com/sns/oauth2/component/refresh_token',
                [
                    '#appid',
                    '#component_access_token',
                    '#refresh_token',
                    'grant_type' => 'refresh_token',
                    '#component_appid',
                ]);
    }

    public function getInfo() {
        return $this->getHttp()
            ->url('https://api.weixin.qq.com/sns/userinfo',
                [
                    '#access_token',
                    '#openid',
                    'lang' => 'zh_CN'
                ]);
    }

    /**
     * @return Uri
     * @throws \Exception
     */
    public function login() {
        $state = Str::randomNumber(7);
        if (function_exists('session')) {
            session([
                'state' => $state
            ]);
        }
        $this->set('state', $state);
        return $this->getLogin()->getUrl()->setFragment('wechat_redirect');
    }

    /**
     * @return array|bool|mixed|null|string
     * @throws \Exception
     */
    public function callback() {
        Http::log('WECHAT CALLBACK: '.var_export($_GET, true));
        $state = isset($_GET['state']) ? $_GET['state'] : null;
        if (empty($state)) {
            return false;
        }
        if (function_exists('session')
            && $state !== session('state')) {
            return false;
        }
        $code = isset($_GET['code']) ? $_GET['code'] : null;
        if (empty($code)) {
            return false;
        }
        $access = $this->getAccess()->parameters([
            'code' => $code
        ])->json();
        if (!is_array($access) || !array_key_exists('openid', $access)) {
            return false;
        }
        $access['identity'] = $access['openid'];
        $this->set($access);
        return $access;
    }

    /**
     * @return array|bool|mixed
     * @throws \Exception
     */
    public function info() {
        $user = $this->getInfo()->json();
        if (!is_array($user) || !array_key_exists('nickname', $user)) {
            return false;
        }
        $user['username'] = $user['nickname'];
        $user['avatar'] = $user['headimgurl'];
        $user['sex'] = $user['sex'] == 2 ? 'F' : 'M';;
        $user['identity'] = isset($user['unionid']) ? $user['unionid'] : $user['openid'];
        $this->set($user);
        return $user;
    }

}