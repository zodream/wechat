<?php
namespace Zodream\ThirdParty\WeChat;

use Zodream\Http\Http;
use Exception;

/**
 * 帐号管理
 * User: zx648
 * Date: 2016/8/20
 * Time: 14:38
 */
class Account extends BaseWeChat {

    /**
     * @return Http
     * @throws Exception
     */
    public function getQrcode() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/qrcode/create')
            ->maps([
                '#action_info',
                'expire_seconds',
                'action_name'
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getShortUrl() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/shorturl')
            ->maps([
                'action' => 'long2short',
                '#long_url'
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getClear() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/clear_quota')
            ->maps([
                '#appid',
            ]);
    }

    /**
     * @param integer|string $scene
     * @param bool|integer $time IF FALSE, QR_LIMIT_SCENE , OR INT, QR_SCENE
     * @return array [ticket, expire_seconds, url]
     * @throws Exception
     */
    public function qrCode($scene, $time = false) {
        $data = [
            'action_info' => [
                'scene' => []
            ]
        ];
        if ($time !== false) {
            $data['expire_seconds'] = intval($time);
            if (is_integer($scene)) {
                $data['action_name'] = 'QR_SCENE';
                $data['action_info']['scene'] = ['scene_id' => intval($scene)];
            } else {
                $data['action_name'] = 'QR_STR_SCENE';
                $data['action_info']['scene'] = ['scene_str' => $scene];
            }
        } else {
            if (is_integer($scene)) {
                if ($scene > 100000) {
                    throw new Exception('scene_id only in 1~100000');
                }
                $data['action_name'] = 'QR_LIMIT_SCENE';
                $data['action_info']['scene'] = ['scene_id' => $scene];
            } else {
                $data['action_name'] = 'QR_LIMIT_STR_SCENE';
                $data['action_info']['scene'] = ['scene_str' => $scene];
            }
        }
        return $this->getQrcode()->parameters($data)->json();
    }

    /**
     * @param $url
     * @return bool
     * @throws Exception
     */
    public function shortUrl($url) {
        $args = $this->getShortUrl()->parameters([
            'action' => 'long2short',
            'long_url' => $url
        ])->json();
        return array_key_exists('short_url', $args) ? $args['short_url'] : false;
    }
}