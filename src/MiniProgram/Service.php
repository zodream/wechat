<?php
namespace Zodream\ThirdParty\WeChat\MiniProgram;

class Service extends BaseMiniProgram {

    public function getSend() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/message/custom/send')
            ->maps([
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
            ]);
    }
}