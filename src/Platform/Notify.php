<?php
namespace Zodream\ThirdParty\WeChat\Platform;


use Zodream\Helpers\Xml;
use Zodream\Http\Http;
use Zodream\ThirdParty\Traits\Attributes;
use Zodream\ThirdParty\WeChat\Aes;
use Zodream\ThirdParty\Traits\EventTrait;

/**
 * 推送给平台授权相关通知
 * @package Zodream\Domain\ThirdParty\WeChat\Platform
 * @property string $componentVerifyTicket
 * @property string $createTime
 * @property string $infoType  unauthorized是取消授权，updateauthorized是更新授权，authorized是授权成功通知
 * @property string $authorizerAppid 公众号
 * @property string $authorizationCode   授权码，可用于换取公众号的接口调用凭据
 * @property string $authorizationCodeExpiredTime  授权码过期时间
 */
class Notify {
    protected $configKey = 'wechat.platform';

    use EventTrait, Attributes;
    const TYPE_ComponentVerifyTicket = 'component_verify_ticket';
    const TYPE_Unauthorized = 'unauthorized';
    const TYPE_UpdateAuthorized = 'updateauthorized';
    const TYPE_Authorized = 'authorized';

    protected $xml;

    protected $aesKey;

    protected $encryptType;

    protected $appId;

    /**
     * Notify constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config = array()) {
        if (function_exists('config')) {
            $config = array_merge(config($this->configKey, array(
                'aes_key' => '',
                'component_appid' => ''
            )), $config);
        }
        $this->aesKey = $config['aes_key'];
        $this->appId = $config['component_appid'];
        if (empty($this->_data)) {
            $this->setData();
        }
        $this->setComponentVerifyTicket();
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function setData() {
        if (empty($this->xml)) {
            $this->xml = file_get_contents('php://input');
        }
        if (!empty($this->xml)) {
            $this->set($this->getData());
        }
        return $this;
    }

    protected function preProcessKey($key) {
        return lcfirst($key);
    }

    public function getXml() {
        return $this->xml;
    }

    public function getEvent() {
        return $this->infoType;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function getData() {
        Http::log('WECHAT NOTIFY: '.$this->xml);
        $data = (array)Xml::decode($this->xml, false);
        $encryptStr = $data['Encrypt'];
        $aes = new Aes($this->aesKey, $this->appId);
        $this->xml = $aes->decrypt($encryptStr);
        return (array)Xml::decode($this->xml, false);
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function setComponentVerifyTicket() {
        if ($this->infoType != self::TYPE_ComponentVerifyTicket) {
            return $this;
        }
        (new Manage())->ticket($this->componentVerifyTicket);
        return $this;
    }

    public function run() {
        $this->invoke($this->getEvent(), [$this]);
        return 'success';
    }
}