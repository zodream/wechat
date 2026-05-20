<?php
declare(strict_types=1);
namespace Zodream\ThirdParty\WeChat\MiniProgram;

use Zodream\Http\Http;
/**
 * 生成小程序码和二维码
 * @package Zodream\ThirdParty\WeChat\MiniProgram
 */
class Code extends BaseMiniProgram {

    public function getWxaCode(): Http {
        return $this->getBaseHttp('https://api.weixin.qq.com/wxa/getwxacode')
            ->maps([
                '#path',
                'width',
                'auto_color',
                'line_color'
            ]);
    }

    public function getWxaCodeUnlimit(): Http {
        return $this->getBaseHttp('https://api.weixin.qq.com/wxa/getwxacodeunlimit')
            ->maps([
                '#path',
                '#scene',
                'width',
                'auto_color',
                'line_color'
            ]);
    }

    public function getCreateWxaQrcode(): Http {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode')
            ->maps([
                '#path',
                'width',
            ]);
    }
}