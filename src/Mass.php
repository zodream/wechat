<?php
namespace Zodream\ThirdParty\WeChat;

use Zodream\Http\Http;
use Exception;
/**
 * 群发
 * @package Zodream\Domain\ThirdParty\WeChat
 */
class Mass extends BaseWeChat {
    const NEWS = 'mpnews';
    const TEXT = 'text';
    const VOICE = 'voice';
    const IMAGE = 'image';
    const VIDEO = 'mpvideo';
    const CARD = 'wxcard';

    /**
     * @return Http
     * @throws Exception
     */
    public function getUploadImg() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/media/uploadimg')
            ->maps([
                '#media',
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getUploadNews() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/media/uploadnews')
            ->maps([
                '#articles',
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getSendAll() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/message/mass/sendall')
            ->maps([
                '#filter',
                '#msgtype',
                'mpnews',
                'text',
                'voice',
                'image',
                'mpvideo',
                'wxcard'
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getSend() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/message/mass/send')
            ->maps([
                '#touser',
                '#msgtype',
                'mpnews',
                'text',
                'voice',
                'image',
                'mpvideo',
                'wxcard'
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getDelete() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/message/mass/delete')
            ->maps([
                '#msg_id'
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getPreview() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/message/mass/preview')
            ->maps([
                '#touser',
                '#msgtype',
                'mpnews',
                'text',
                'voice',
                'image',
                'mpvideo',
                'wxcard'
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getQuery() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/message/mass/get')
            ->maps([
                '#msg_id'
            ]);
    }

    /**
     * @param $file
     * @return bool
     * @throws \Exception
     */
    public function uploadImg($file) {
        $args = $this->getUploadImg()->parameters([
            'media' => '@'.$file
        ])->json();
        return array_key_exists('url', $args) ? $args['url'] : false;
    }

    /**
     * @param NewsItem $news
     * @return bool
     * @throws \Exception
     */
    public function updateNews(NewsItem $news) {
        $args = $this->getUploadNews()->parameters($news->toArray())->json();
        return $args['errcode'] == 0;
    }

    /**
     * @param $data
     * @param string $type
     * @param null $groupId
     * @return mixed
     * @throws \Exception
     */
    public function sendAll($data, $type = self::TEXT, $groupId = null) {
        $data = $this->parseData($data, $type);
        $data['filter'] =  empty($groupId) ? [
            'is_to_all' => true
        ] : [
            'is_to_all' => false,
            'group_id' => $groupId
        ];
        $args = $this->getSendAll()->parameters($data)->json();
        if ($args['errcode'] === 0) {
            return $args['msg_id'];
        }
        throw new \Exception($args['errmsg']);
    }

    /**
     * @param array $openId
     * @param $data
     * @param string $type
     * @return mixed
     * @throws \Exception
     */
    public function send(array $openId, $data, $type = self::TEXT) {
        $data = $this->parseData($data, $type);
        $data['touser'] = array_values($openId);
        $args = $this->getSend()->parameters($data)->json();
        if ($args['errcode'] === 0) {
            return $args['msg_id'];
        }
        throw new \Exception($args['errmsg']);
    }

    /**
     * @param $msgId
     * @return bool
     * @throws \Exception
     */
    public function cancel($msgId) {
        $args = $this->getDelete()->parameters([
            'msg_id' => $msgId
        ]);
        if ($args['errcode'] === 0) {
            return true;
        }
        throw new \Exception($args['errmsg']);
    }

    /**
     * @param $openId
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function preview($openId, array $data) {
        $data['touser'] = $openId;
        $args = $this->getPreview()->parameters($data)->json();
        if ($args['errcode'] === 0) {
            return true;
        }
        throw new \Exception($args['errmsg']);
    }

    /**
     * @param $msgId
     * @return bool
     * @throws \Exception
     */
    public function query($msgId) {
        $args = $this->getQuery()->parameters([
            'msg_id' => $msgId
        ])->json();
        if (isset($args['msg_status'])) {
            return $args['msg_status'];
        }
        return false;
    }

    /**
     * 转化
     * @param string|array $arg
     * @param string $type
     * @return array
     */
    protected function parseData($arg, $type = self::TEXT) {
        $data = [
            $type => [],
            'msgtype' => $type
        ];
        if ($type == self::TEXT) {
            $data[$type] = ['content' => $arg];
            return $data;
        }
        if ($type == self::CARD) {
            $data[$type] = $arg;
            return $data;
        }
        $data[$type] = ['media_id' => $arg];
        return $data;
    }
}