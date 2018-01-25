<?php
namespace Zodream\ThirdParty\WeChat\MiniProgram;

class Template extends BaseMiniProgram {

    protected $apiMap = [
        'library_list' => [
            [
                'https://api.weixin.qq.com/cgi-bin/wxopen/template/library/list',
                '#access_token'
            ],
            [
                'offset' => 0,
                'count' => 20
            ],
            'POST'
        ],
        'query' => [
            [
                'https://api.weixin.qq.com/cgi-bin/wxopen/template/library/get',
                '#access_token'
            ],
            '#id',
            'POST'
        ],
        'add' => [
            [
                'https://api.weixin.qq.com/cgi-bin/wxopen/template/add',
                '#access_token'
            ],
            [
                '#id',
                '#keyword_id_list'
            ],
            'POST'
        ],
        'list' => [
            [
                'https://api.weixin.qq.com/cgi-bin/wxopen/template/list',
                '#access_token'
            ],
            [
                'offset' => 0,
                'count' => 20
            ],
            'POST'
        ],
        'del' => [
            [
                'https://api.weixin.qq.com/cgi-bin/wxopen/template/del',
                '#access_token'
            ],
            '#template_id',
            'POST'
        ],
        'send' => [
            [
                'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send',
                '#access_token'
            ],
            [
                '#touser',
                '#template_id',
                'page',
                '#form_id',
                '#data',
                'color',
                'emphasis_keyword'
            ],
            'POST'
        ],
    ];
}