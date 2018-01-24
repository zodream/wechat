<?php
namespace Zodream\ThirdParty\WeChat;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/8/19
 * Time: 22:27
 */
use Zodream\Http\Http;
use Zodream\ThirdParty\ThirdParty;
use Exception;

abstract class BaseWeChat extends ThirdParty  {

    protected $configKey = 'wechat';

    /**
     * @param null $url
     * @return Http
     * @throws Exception
     */
    public function getBaseHttp($url = null) {
        $token = (new AccessToken($this->get()))->token();
        return $this->getHttp()
            ->url($url, [
                '#access_token'
            ])
            ->parameters($this->merge([
                'access_token' => $token
            ]))
            ->encode(Http::JSON);
    }
}