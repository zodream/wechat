<?php
namespace Zodream\ThirdParty\WeChat;

use Zodream\Helpers\Str;
use Zodream\Infrastructure\Base\ZObject;

/**
 * Class MenuItem
 * @package Zodream\ThirdParty\WeChat
 * @method MenuItem type($type)
 * @method MenuItem name($arg)
 * @method MenuItem key($arg)
 * @method MenuItem url($url)
 * @method MenuItem mediaId($arg)
 * @method MenuItem menu($arg)
 */
class MenuItem extends ZObject {
    const CLICK = 'click';
    const VIEW = 'view';
    const MINI_PROGRAM = 'miniprogram'; // 小程序
    const SCAN_CODE_MSG = 'scancode_waitmsg';
    const SCAN_CODE_PUSH = 'scancode_push';
    const SYSTEM_PHOTO = 'pic_sysphoto';
    const SYSTEM_PHOTO_ALBUM = 'pic_photo_or_album';
    const WEI_XIN_PHOTO = 'pic_weixin';
    const LOCATION = 'location_select';
    const MEDIA = 'media_id';
    const VIEW_LIMITED = 'view_limited';

    protected $type;
    protected $name;
    protected $key;
    protected $url;
    protected $mediaId;
    protected $appid; // 小程序的appid
    protected $pagepath; //小程序的页面路径

    /**
     * @var MenuItem[]
     */
    protected $menu = [];

    public function __construct() {
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setName($arg) {
        $this->name = $arg;
        return $this;
    }

    /**
     * 设置点击事件及标记
     * @param $arg
     * @return $this
     */
    public function setKey($arg) {
        $this->setType(self::CLICK);
        $this->key = $arg;
        return $this;
    }

    /**
     * 设置网址
     * @param $arg
     * @return $this
     */
    public function setUrl($arg) {
        if ($this->type != self::MINI_PROGRAM) {
            $this->setType(self::VIEW);
        }
        $this->url = (string)$arg;
        return $this;
    }

    /**
     * 设置资源
     * @param $arg
     * @return $this
     */
    public function setMediaId($arg) {
        if (empty($this->type)) {
            $this->setType(self::MEDIA);
        }
        $this->mediaId = $arg;
        return $this;
    }

    /**
     * 设置或添加子菜单
     * @param MenuItem[]|MenuItem $arg
     * @return $this
     */
    public function setMenu($arg) {
        if (is_array($arg)) {
            $this->menu = $arg;
        } else {
            $this->menu[] = $arg;
        }
        return $this;
    }

    /**
     * 设置跳转小程序
     * @param $appid
     * @param $page
     * @param string $url 不支持小程序的老版本客户端将打开本url
     * @return $this
     */
    public function setMini($appid, $page, $url) {
        $this->setType(self::MINI_PROGRAM);
        $this->appid = $appid;
        $this->pagepath = $page;
        $this->url = $url;
        return $this;
    }

    /**
     *
     */
    public function toArray() {
        if (!empty($this->type)) {
            return $this->getHasType();
        }
        if (!empty($this->name)) {
            $args = array_splice($this->menu, 0, 5);
            return [
                'name' => $this->name,
                'sub_button' => array_map([$this, 'getArray'], $args)
            ];
        }
        $args = array_splice($this->menu, 0, 3);
        return [
            'button' => array_map([$this, 'getArray'], $args)
        ];
    }

    protected function getArray(MenuItem $item) {
        return $item->toArray();
    }

    protected function getHasType() {
        $data = [
            'type' => $this->type,
            'name' => $this->name
        ];
        if (in_array($this->type, [self::CLICK,
            self::SCAN_CODE_MSG,
            self::SCAN_CODE_PUSH,
            self::SYSTEM_PHOTO,
            self::SYSTEM_PHOTO_ALBUM,
            self::WEI_XIN_PHOTO,
            self::LOCATION
        ])) {
            $data['key'] = $this->key;
            return $data;
        }
        if (in_array($this->type, [self::VIEW])) {
            $data['url'] = (string)$this->url;
            return $data;
        }
        if (in_array($this->type, [self::MINI_PROGRAM])) {
            $data['url'] = (string)$this->url;
            $data['appid'] = $this->appid;
            $data['pagepath'] = $this->pagepath;
            return $data;
        }
        if (in_array($this->type, [self::MEDIA, self::VIEW_LIMITED])) {
            $data['media_id'] = $this->mediaId;
            return $data;
        }
        return $data;
    }

    public function __call($name, $arguments) {
        $name = 'set'.Str::studly($name);
        return call_user_func_array([$this, $name], $arguments);
    }

    public static function __callStatic($name, $arguments) {
        return call_user_func_array([new static(), $name], $arguments);
    }
}