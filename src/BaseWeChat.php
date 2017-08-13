<?php
namespace Zodream\ThirdParty\WeChat;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/8/19
 * Time: 22:27
 */
use Zodream\Helpers\Json;
use Zodream\ThirdParty\ThirdParty;

abstract class BaseWeChat extends ThirdParty  {
    protected $configKey = 'wechat';

    protected function getPostData($name, array $args) {
        return Json::encode(parent::getPostData($name, $args));
    }

    protected function getData(array $keys, array $args) {
        if ((in_array('#access_token', $keys) || in_array('access_token', $keys))
            && (!$this->has('access_token') || !array_key_exists('access_token', $args))) {
            $args['access_token'] = (new AccessToken($args))->getAccessToken();
        }
        return parent::getData($keys, $args);
    }
}