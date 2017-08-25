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
use Exception;

abstract class BaseWeChat extends ThirdParty  {
    protected $configKey = 'wechat';

    /**
     * 是否通过统一方法显示错误
     * @var bool
     */
    protected $autoThrow = false;

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

    protected function getJson($name, $args = array()) {
        $args = parent::getJson($name, $args);
        if ($this->autoThrow) {
            $this->throwError($args);
        }
        return $args;
    }

    /**
     * 是否消息错误
     * @param array $args
     * @throws \Exception
     */
    public function throwError(array $args) {
        if (array_key_exists('errcode', $args)
            && $args['errcode'] != 0) {
            throw new Exception($args['errmsg']);
        }
    }
}