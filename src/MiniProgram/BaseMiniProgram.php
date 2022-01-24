<?php
namespace Zodream\ThirdParty\WeChat\MiniProgram;

use Zodream\ThirdParty\WeChat\BaseWeChat;
use Exception;

/**
 * Class BaseMiniProgram
 * @package Zodream\ThirdParty\WeChat\MiniProgram
 * @property string $sessionKey
 */
class BaseMiniProgram extends BaseWeChat {

    protected string $configKey = 'wechat.mini';

    /**
     * 解密数据
     * @param $data
     * @param $iv
     * @return array
     * @throws Exception
     */
    public function decrypt($data, $iv) {
        if (strlen($this->sessionKey) != 24) {
            throw new Exception('sessionkey 错误');
        }
        $aesKey = base64_decode($this->sessionKey);
        if (strlen($iv) != 24) {
            throw new Exception('iv 错误');
        }
        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($data);
        $result = openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $args = json_decode($result, true);
        if (empty($args)) {
            throw new Exception('aes 解密失败');
        }
        if ($args['watermark']['appid'] != $this->appid) {
            throw new Exception('解密后得到的buffer非法');
        }
        return $args;
    }
}