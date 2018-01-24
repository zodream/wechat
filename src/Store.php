<?php
namespace Zodream\ThirdParty\WeChat;

use Zodream\Http\Http;
use Exception;

/**
 * 门店
 * @package Zodream\ThirdParty\WeChat
 */
class Store extends BaseWeChat {

    /**
     * @return Http
     * @throws Exception
     */
    public function getAdd() {
        return $this->getBaseHttp('http://api.weixin.qq.com/cgi-bin/poi/addpoi')
            ->maps([
                '#business' => [
                    '#base_info' => [
                        'sid',  //商户自己的id，用于后续审核通过收到poi_id 的通知时，做对应关系。请商户自己保证唯一识别性
                        '#business_name', //门店名称（仅为商户名，如：国美、麦当劳，不应包含地区、地址、分店名等信息，错误示例：北京国美）
                        '#branch_name', //	分店名称
                        '#province',
                        '#city',
                        '#district',
                        '#address',
                        '#telephone',
                        '#categories',
                        '#offset_type',  //1 为火星坐标 2 为sogou经纬度3 为百度经纬度 4 为mapbar经纬度 5 为GPS坐标 6 为sogou墨卡托坐标
                        '#longitude',
                        '#latitude',
                        'photo_list',
                        'photo_url',
                        'photo_url',
                        'recommend',
                        'special',
                        'introduction',
                        'open_time',
                        'avg_price',
                    ]
                ]
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getQuery() {
        return $this->getBaseHttp('http://api.weixin.qq.com/cgi-bin/poi/getpoi')
            ->maps([
                '#poi_id',
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getList() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/poi/getpoilist')
            ->maps([
                'begin' => 0,
                'limit' => 20
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getUpdate() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/poi/updatepoi')
            ->maps([
                '#business' => [
                    '#base_info' => [
                        '#poi_id',
                        'sid',  //商户自己的id，用于后续审核通过收到poi_id 的通知时，做对应关系。请商户自己保证唯一识别性
                        'business_name', //门店名称（仅为商户名，如：国美、麦当劳，不应包含地区、地址、分店名等信息，错误示例：北京国美）
                        'branch_name', //	分店名称
                        'province',
                        'city',
                        'district',
                        'address',
                        'telephone',
                        'categories',
                        'offset_type',  //1 为火星坐标 2 为sogou经纬度3 为百度经纬度 4 为mapbar经纬度 5 为GPS坐标 6 为sogou墨卡托坐标
                        'longitude',
                        'latitude',
                        'photo_list',
                        'photo_url',
                        'photo_url',
                        'recommend',
                        'special',
                        'introduction',
                        'open_time',
                        'avg_price',
                    ]
                ]
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getDelete() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/poi/delpoi')
            ->maps([
                '#poi_id'
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getCategoryList() {
        return $this->getBaseHttp('http://api.weixin.qq.com/cgi-bin/poi/getwxcategory');
    }
}