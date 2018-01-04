<?php
namespace Zodream\ThirdParty\WeChat\MiniProgram;

class Service extends BaseMiniProgram {

    protected $apiMap = [
        'send' => [
            [
                'https://api.weixin.qq.com/cgi-bin/message/custom/send',
                '#access_token'
            ],
            [
                '#touser',
                '#msgtype',
                '#content',
                '#media_id',
                '#title',
                '#description',
                '#url',
                '#picurl',
                '#pagepath',
                '#thumb_media_id'
            ],
            'POST'
        ],

    ];
}