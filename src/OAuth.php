<?php
namespace Zodream\ThirdParty\WeChat;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/8/20
 * Time: 10:23
 */
use Zodream\Helpers\Str;
use Zodream\Http\Http;
use Zodream\Http\Uri;
use Zodream\Service\Factory;
use Zodream\Infrastructure\Http\Request;
use Exception;

/**
 * Class OAuth
 * @package Zodream\Domain\ThirdParty\WeChat
 * @property string $identity
 * @property string $username
 * @property string $sex
 * @property string $avatar
 */
class OAuth extends BaseWeChat {

    public function getLogin() {
        return $this->getHttp()
            ->url('https://open.weixin.qq.com/connect/oauth2/authorize', [
                    '#appid',
                    '#redirect_uri',
                    'response_type' => 'code',
                    'scope' => 'snsapi_userinfo', // snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid），snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地
                    'state',
                    //'#wechat_redirect'
                ])->parameters($this->get());
    }

    public function getAccess() {
        return $this->getHttp()
            ->url('https://api.weixin.qq.com/sns/oauth2/access_token',
                [
                    '#appid',
                    '#secret',
                    '#code',
                    'grant_type' => 'authorization_code'
                ])->parameters($this->get());
    }

    public function getRefreshToken() {
        return $this->getHttp()
            ->url('https://api.weixin.qq.com/sns/oauth2/refresh_token',
                [
                    '#appid',
                    '#refresh_token',
                    'grant_type' => 'refresh_token',
                ])->parameters($this->get());
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getInfo() {
        return $this->getHttp()
            ->url('https://api.weixin.qq.com/sns/userinfo',
                [
                    '#access_token',
                    '#openid',
                    'lang' => 'zh_CN'
                ])->parameters($this->get());
    }

    /**
     * @return Uri
     * @throws \Exception
     */
    public function login() {
        $state = Str::randomNumber(7);
        Factory::session()->set('state', $state);
        $this->set('state', $state);
        return $this->getLogin()->getUrl()->setFragment('wechat_redirect');
    }

    /**
     * @return array|bool|mixed|null|string
     * @throws \Exception
     */
    public function callback() {
        Factory::log()
            ->info('WECHAT CALLBACK: '.var_export($_GET, true));
        $state = app('request')->get('state');
        if (empty($state) || $state != Factory::session()->get('state')) {
            return false;
        }
        $code = app('request')->get('code');
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
        $user['sex'] = $user['sex'] == 2 ? 'F' : 'M';
        $user['identity'] = isset($user['unionid']) ? $user['unionid'] : $user['openid'];
        $this->set($user);
        return $user;
    }

}