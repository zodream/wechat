<?php
namespace Zodream\ThirdParty\WeChat\MiniProgram;

use Exception;

/**
 * 生成小程序码和二维码
 * @package Zodream\ThirdParty\WeChat\MiniProgram
 */
class Code extends BaseMiniProgram {

    protected $apiMap = [
        'getwxacode' => [
            [
                'https://api.weixin.qq.com/wxa/getwxacode',
                '#access_token'
            ],
            [
                '#path',
                'width',
                'auto_color',
                'line_color'
            ],
            'POST'
        ],
        'getwxacodeunlimit' => [
            [
                'https://api.weixin.qq.com/wxa/getwxacodeunlimit',
                '#access_token'
            ],
            [
                '#path',
                '#scene',
                'width',
                'auto_color',
                'line_color'
            ],
            'POST'
        ],

        'createwxaqrcode' => [
            [
                'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode',
                '#access_token'
            ],
            [
                '#path',
                'width',
            ],
            'POST'
        ],
    ];
}