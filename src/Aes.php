<?php
declare(strict_types=1);
namespace Zodream\ThirdParty\WeChat;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/6
 * Time: 19:37
 */
use Zodream\Helpers\Str;
use Zodream\Helpers\Security\BaseSecurity;

class Aes extends BaseSecurity {

    protected string $key;

    protected string $appId;

    protected int $blockSize = 32;

    public function __construct(string $key, string $appId = '') {
        $this->key = base64_decode($key . '=');
        $this->appId = $appId;
    }

    public function getAppId() {
        return $this->appId;
    }

    /**
     * ENCRYPT STRING
     * @param string $data
     * @return string
     */
    public function encrypt($data): string {
        //获得16位随机字符串，填充到明文之前
        $random = Str::random(16);
        $data = $random . pack("N", strlen($data)) . $data . $this->appId;

        $iv = substr($this->key, 0, 16);
        //使用自定义的填充方式对明文进行补位填充
        $data = $this->pkcs7Pad($data, $this->blockSize);
        return openssl_encrypt($data, 'AES-256-CBC',
            substr($this->key, 0, 32), OPENSSL_ZERO_PADDING, $iv);
    }

    /**
     * DECRYPT STRING
     * @param string $data
     * @return string
     * @throws \Exception
     */
    public function decrypt(string $data) {
        $iv = substr($this->key, 0, 16);
        $decrypted = openssl_decrypt($data, 'AES-256-CBC',
            substr($this->key, 0, 32), OPENSSL_ZERO_PADDING, $iv);

        //去除补位字符
        $result = $this->pkcs7UnPad($decrypted, $this->blockSize);
        //去除16位随机字符串,网络字节序和AppId
        if (strlen($result) < 16) {
            throw new \InvalidArgumentException('LENGTH < 16');
        }
        $content = substr($result, 16, strlen($result));
        $len_list = unpack('N', substr($content, 0, 4));
        $xml_len = $len_list[1];
        $xml_content = substr($content, 4, $xml_len);
        $fromAppId = substr($content, $xml_len + 4);
        if (!$this->appId)
            $this->appId = $fromAppId;
        return $xml_content;
    }
}