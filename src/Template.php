<?php
namespace Zodream\ThirdParty\WeChat;

use Zodream\Http\Http;
use Exception;
/**
 * 模板消息
 * User: zx648
 * Date: 2016/8/23
 * Time: 19:17
 */
class Template extends BaseWeChat {

    /**
     * @return Http
     * @throws Exception
     */
    public function getSetIndustry() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/template/api_set_industry')
            ->maps([
                '#industry_id1',
                '#industry_id2'
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getIndustry() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/template/get_industry');
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getAddTemplate() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/template/api_add_template')
            ->maps([
                '#template_id_short',
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getAll() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/template/get_all_private_template');
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getDelete() {
        return $this->getBaseHttp('https://api,weixin.qq.com/cgi-bin/template/del_private_template')
            ->maps([
                '#template_id',
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getSend() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/message/template/send')
            ->maps([
                '#touser',
                '#template_id',
                '#url',
                '#data'
            ]);
    }

    /**
     * @param $id1
     * @param $id2
     * @return mixed
     * @throws \Exception
     */
    public function setIndustry($id1, $id2) {
        return $this->getSetIndustry()->parameters([
            'industry_id1' => $id1,
            'industry_id2' => $id2
        ])->json();
    }

    /**
     * @return array [primary_industry, secondary_industry]
     * @throws \Exception
     */
    public function industry() {
        return $this->getIndustry()->json();
    }

    /**
     * @param $id
     * @return bool|string template_id
     * @throws \Exception
     */
    public function addTemplate($id) {
        $args = $this->getAddTemplate()->parameters([
            'template_id_short' => $id
        ])->json();
        if ($args['errcode'] == 0) {
            return $args['template_id'];
        }
        return false;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function allTemplate() {
        return $this->getAll()->json();
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function deleteTemplate($id) {
        $args = $this->getDelete()->parameters([
            'template_id' => $id
        ])->json();
        return $args['errcode'] == 0;
    }

    /**
     * 发送模板消息
     * @param string $openId
     * @param string $template
     * @param string $url 链接的网址
     * @param array $data [key => [value, color]]
     * @return bool
     * @throws \Exception
     */
    public function send($openId, $template, $url, array $data) {
        foreach ($data as $key => &$item) {
            if (!is_array($item)) {
                $item = [
                    'value' => $item,
                    'color' => '#777'
                ];
            }
        }
        $arg = $this->getSend()->parameters([
            'touser' => $openId,
            'template_id' => $template,
            'url' => $url,
            'data' => $data
        ])->json();
        return $arg['errcode'] == 0;
    }

}