<?php
namespace Zodream\ThirdParty\WeChat;

use Zodream\Helpers\Str;
use Zodream\Helpers\Xml;
use Zodream\Infrastructure\Base\MagicObject;
use Zodream\Infrastructure\Traits\EventTrait;
use Zodream\Service\Factory;

/**
 * 消息管理
 * @package Zodream\Domain\ThirdParty\WeChat
 *
 * @property string $toUserName
 * @property string $fromUserName
 * @property integer $createTime
 * @property string $msgType
 * //消息
 * @property integer $msgId
 * //@property string //$msgType
 * //文本
 * @property string $content
 * //图片消息
 * @property string $picUrl
 * @property string $mediaId
 * //语音消息
 * @property string $format
 * @property string $recognition  开通语音识别
 * //视频消息 小视频消息
 * @property string $thumbMediaId
 * //地理位置消息
 * @property float $location_X
 * @property float $location_Y
 * @property integer $scale
 * @property string $label
 * //链接消息
 * @property string $title
 * @property string $description
 * @property string $url
 *
 * @property string $event
 * //自定义菜单
 * @property string $eventKey
 * // 门店审核事件
 * @property string $uniqId  //商户自己内部ID，即字段中的sid
 * @property string $poiId  //微信的门店ID，微信内门店唯一标示ID
 * @property string $result  //审核结果，成功succ 或失败fail
 * @property string $msg //成功的通知信息，或审核失败的驳回理由
 */
class Message extends MagicObject {

    use EventTrait;

    protected $configKey = 'wechat';

    protected $configs = [
        'aes_key' => '',
        'appid' => '',
        'token' => ''
    ];

    protected $xml;

    protected $encryptType;

    /**
     * Message constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config = array()) {
        $this->configs = array_merge(Factory::config(
            $this->configKey, $this->configs),
            $config);
        $this->encryptType = app('request')->get('encrypt_type');
        $this->get();
    }

    /**
     * @param null $key
     * @param null $default
     * @return mixed
     * @throws \Exception
     */
    public function get($key = null, $default = null) {
        if (!$this->hasAttribute()) {
            $this->setData();
        }
        return parent::get(lcfirst($key), $default);
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function setData() {
        if (empty($this->xml)) {
            $this->xml = app('request')->input();
        }
        if (!empty($this->xml)) {
            $args = $this->getData();
            foreach ($args as $key => $item) {
                $this->set(lcfirst($key), $item);
            }
        }
        return $this;
    }

    /**
     * 分离扫码关注事件
     * @return string
     */
    public function getEvent() {
        if (!$this->isEvent()) {
            return EventEnum::Message;
        }
        // ADD SCAN SUBSCRIBE EVENT
        if ($this->event == EventEnum::Subscribe
            && strpos($this->eventKey, 'qrscene_') === 0) {
            $this->eventKey = Str::firstReplace($this->eventKey, 'qrscene_');
            return EventEnum::ScanSubscribe;
        }
        return $this->event;
    }

    /**
     * 判断是否是事件推送
     * @return bool
     */
    public function isEvent() {
        return $this->msgType == 'event';
    }

    /**
     * 判断是否是消息推送
     * @return bool
     */
    public function isMessage() {
        return !$this->isEvent();
    }

    public function getXml() {
        return $this->xml;
    }

    /**
     * 来源者
     * @return string
     * @throws \Exception
     */
    public function getFrom() {
        return $this->get('FromUserName');
    }

    /**
     * 接收方
     * @return string
     * @throws \Exception
     */
    public function getTo() {
        return $this->get('ToUserName');
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function getData() {
        Factory::log()->info('WECHAT MESSAGE: '.$this->xml);
        $data = (array)Xml::decode($this->xml, false);
        if ($this->encryptType != 'aes') {
            return $data;
        }
        $encryptStr = $data['Encrypt'];
        $aes = new Aes($this->configs['aes_key'], $this->configs['appid']);
        $xml = $aes->decrypt($encryptStr);
        $this->configs['appid'] = $aes->getAppId();
        return (array)Xml::decode($xml, false);
    }

    /**
     * 当前操作是否是验证
     * @return bool
     * @throws \Exception
     */
    public function isValid() {
        return app('request')->has('signature')
            || app('request')->has('msg_signature');
    }

    /**
     * 验证
     * @param string $str
     * @return bool
     * @throws \Exception
     */
    protected function checkSignature($str = '') {
        $signature = app('request')->get('signature');
        $signature = app('request')->get('msg_signature', $signature); //如果存在加密验证则用加密验证段
        $timestamp = app('request')->get('timestamp');
        $nonce = app('request')->get('nonce');

        $token = $this->configs['token'];
        $tmpArr = array($token, $timestamp, $nonce, $str);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        return $tmpStr == $signature;
    }

    /**
     * 验证
     * @throws \Exception
     */
    public function valid() {
        $echoStr = app('request')->get('echostr');
        if (!is_null($echoStr)) {
            if ($this->checkSignature()) {
                exit($echoStr);
            }
            throw new \Exception('no access');
        }
        $encryptStr = '';
        if (app('request')->isPost()) {
            $data = (array)Xml::decode($this->xml, false);
            if ($this->encryptType != 'aes') {
                return $data;
            }
            $encryptStr = $data['Encrypt'];
        }
        if (!$this->checkSignature($encryptStr)) {
            throw new \Exception('no access');
        }
        return true;
    }

    /**
     * 获取响应
     * @return MessageResponse
     * @throws \Exception
     */
    public function getResponse() {
        $response = new MessageResponse($this->configs['token'],
            $this->configs['aes_key'],
            $this->encryptType,
            $this->configs['appid']);
        return $response->setFromUseName($this->getTo())
            ->setToUseName($this->getFrom());
    }

    /**
     * 无法回复时自动返回success
     * @return MessageResponse
     * @throws \Exception
     */
    public function run() {
        $response = $this->getResponse();
        $this->invoke($this->getEvent(), [$this, $response]);
        return $response;
    }

    /**
     * 验证消息来源
     * @param string $original
     * @return bool
     * @throws \Exception
     */
    public function verifyServer($original) {
        if ($original !== $this->getTo()) {
            return false;
        }
        return (new AccessToken($this->configs))->verifyIp(app('request')->ip());
    }
}