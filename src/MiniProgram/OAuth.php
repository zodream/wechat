<?php
namespace Zodream\ThirdParty\WeChat\MiniProgram;

use Exception;

class OAuth extends BaseMiniProgram {

    protected $apiMap = [
        'login' => [
            'https://api.weixin.qq.com/sns/jscode2session',
            [
                '#appid',
                '#secret',
                '#js_code',
                'grant_type' => 'authorization_code'
            ]
        ],
    ];

    /**
     * @param string $code
     * @return array [
     openid	用户唯一标识
    session_key	会话密钥
    unionid	用户在开放平台的唯一标识符。本字段在满足一定条件的情况下才返回]
     * @throws Exception
     */
    public function login($code) {
        $args = $this->getJson('login', [
            'js_code' => $code
        ]);
        if (array_key_exists('errcode', $args)) {
            throw new Exception($args['errmsg']);
        }
        return $args;
    }

}