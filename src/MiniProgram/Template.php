<?php
namespace Zodream\ThirdParty\WeChat\MiniProgram;

class Template extends BaseMiniProgram {

    public function getLibraryList() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/wxopen/template/library/list')
            ->maps([
                'offset' => 0,
                'count' => 20
            ]);
    }

    public function getQuery() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/wxopen/template/library/get')
            ->maps([
                '#id',
            ]);
    }

    public function getAdd() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/wxopen/template/add')
            ->maps([
                '#id',
                '#keyword_id_list'
            ]);
    }

    public function getList() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/wxopen/template/list')
            ->maps([
                'offset' => 0,
                'count' => 20
            ]);
    }

    public function getDel() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/wxopen/template/del')
            ->maps([
                '#template_id',
            ]);
    }

    public function getSend() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send')
            ->maps([
                '#touser',
                '#template_id',
                'page',
                '#form_id',
                '#data',
                'color',
                'emphasis_keyword'
            ]);
    }
}