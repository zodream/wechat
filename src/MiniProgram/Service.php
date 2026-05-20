<?php
declare(strict_types=1);
namespace Zodream\ThirdParty\WeChat\MiniProgram;

use Zodream\Http\Http;

class Service extends BaseMiniProgram {

    public function getSend(): Http {
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