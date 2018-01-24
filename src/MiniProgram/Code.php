<?php
namespace Zodream\ThirdParty\WeChat\MiniProgram;

/**
 * 生成小程序码和二维码
 * @package Zodream\ThirdParty\WeChat\MiniProgram
 */
class Code extends BaseMiniProgram {

    public function getWxaCode() {
        return $this->getBaseHttp('https://api.weixin.qq.com/wxa/getwxacode')
            ->maps([
                '#path',
                'width',
                'auto_color',
                'line_color'
            ]);
    }

    public function getWxaCodeUnlimit() {
        return $this->getBaseHttp('https://api.weixin.qq.com/wxa/getwxacodeunlimit')
            ->maps([
                '#path',
                '#scene',
                'width',
                'auto_color',
                'line_color'
            ]);
    }

    public function getCreateWxaQrcode() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode')
            ->maps([
                '#path',
                'width',
            ]);
    }
}