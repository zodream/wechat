<?php
namespace Zodream\ThirdParty\WeChat;

/**
 * 素材管理
 * User: zx648
 * Date: 2016/8/20
 * Time: 10:39
 */
use Zodream\Disk\File;
use Exception;
use Zodream\Http\Http;

class Media extends BaseWeChat {
    const IMAGE = 'image';
    const VOICE = 'voice';
    const VIDEO = 'video';
    const THUMB = 'thumb';
    const NEWS = 'news';

    /**
     * @return Http
     * @throws Exception
     */
    public function getUploadTemp() {
        return $this->getBaseHttp()
            ->url('https://api.weixin.qq.com/cgi-bin/media/upload',
                [
                    '#access_token',
                    '#type'
                ])->maps([
                '#media',
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getDownloadTemp() {
        return $this->getBaseHttp()
            ->url('https://api.weixin.qq.com/cgi-bin/media/get',
                [
                    '#access_token',
                    '#media_id'
                ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getAddNews() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/material/add_news')
            ->maps([
                '#articles',
            ]);
    }

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
    public function getAddMedia() {
        return $this->getBaseHttp()
            ->url('https://api.weixin.qq.com/cgi-bin/material/add_material',
                [
                    '#access_token',
                    '#type'
                ])->maps([
                '#media',
                'description' // 上传以后再次提交
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getMedia() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/material/get_material')
            ->maps([
                '#media_id',
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getDeleteMedia() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/material/del_material')
            ->maps([
                '#media_id',
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getUpdateNews() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/material/update_news')
            ->maps([
                '#articles',
                '#media_id',
                'index'
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getCount() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/material/get_materialcount');
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getMediaList() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/material/batchget_material')
            ->maps([
                '#type',
                '#offset',
                '#count'
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getOpenComment() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/comment/open')
            ->maps([
                '#msg_data_id',
                'index'
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getCloseComment() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/comment/close')
            ->maps([
                '#msg_data_id',
                'index'
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getCommentList() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/comment/list')
            ->maps([
                '#msg_data_id',
                'index',
                '#begin',
                '#count',  //	获取数目（>=50会被拒绝）
                '#type'   //type=0 普通评论&精选评论 type=1 普通评论 type=2 精选评论
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getMarkComment() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/comment/markelect')
            ->maps([
                '#msg_data_id',
                'index',
                '#user_comment_id'
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getUnMarkComment() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/comment/unmarkelect')
            ->maps([
                '#msg_data_id',
                'index',
                '#user_comment_id'
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getAddComment() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/comment/reply/add')
            ->maps([
                '#msg_data_id',
                'index',
                '#user_comment_id',
                '#content'
            ]);
    }


    /**
     * @return Http
     * @throws Exception
     */
    public function getDeleteComment() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/comment/delete')
            ->maps([
                '#msg_data_id',
                'index',
                '#user_comment_id'
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getDeleteReplyComment() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/comment/reply/delete')
            ->maps([
                '#msg_data_id',
                'index',
                '#user_comment_id'
            ]);
    }

    /**
     * @param string|File $file
     * @param string $type
     * @return array [type, media_id, created_at]
     * @throws Exception
     */
    public function uploadTemp($file, $type) {
        return $this->getUploadTemp()->parameters([
            'media' => '@'.$file,
            'type' => $type
        ])->json();
    }

    /**
     * @param $mediaId
     * @param $file
     * @param null $type
     * @return string
     * @throws Exception
     */
    public function downloadTemp($mediaId, $file, $type = null) {
        $http = $this->getDownloadTemp()->parameters([
            'media_id' => $mediaId
        ]);
        if ($type == self::VIDEO) {
            $http->url($http->getUrl()->setScheme('http'));
        }
        return $http->save($file);
    }

    /**
     *
     * @param NewsItem $news
     * @return string|bool media_id
     * @throws Exception
     */
    public function addNews(NewsItem $news) {
        $args = $this->getAddNews()->parameters($news->toArray())->json();
        return array_key_exists('media_id', $args) ? $args['media_id'] : false;
    }

    /**
     *
     * @param $file
     * @return string|bool url
     * @throws Exception
     */
    public function uploadImg($file) {
        $args = $this->getUploadImg()->parameters([
            'media' => '@'.$file
        ])->json();
        return array_key_exists('url', $args) ? $args['url'] : false;
    }

    /**
     * @param $file
     * @param $type
     * @param null $title
     * @param null $introduction
     * @return mixed
     * @throws Exception
     */
    public function addMedia($file, $type, $title = null, $introduction = null) {
        $http = $this->getAddMedia()->parameters([
            'type' => $type,
            'media' => '@'.$file
        ]);
        $args = $http->json();
        if ($type == self::VIDEO) {
            $args = $http->maps([
                'description' => json_encode([
                    'title' => $title,
                    'introduction' => $introduction
                ])
            ])->json();
        }
        return $args;
    }

    /**
     * @param $mediaId
     * @param null $file
     * @return string
     * @throws Exception
     */
    public function media($mediaId, $file = null) {
        return $this->getMedia()->parameters([
            'media_id' => $mediaId
        ])->save($file);
    }

    /**
     * @param $mediaId
     * @return bool
     * @throws Exception
     */
    public function deleteMedia($mediaId) {
        $args = $this->getDeleteMedia()->parameters([
            'media_id' => $mediaId
        ])->json();
        return $args['errcode'] == 0;
    }

    /**
     * @param NewsItem $news
     * @return bool
     * @throws Exception
     */
    public function updateNews(NewsItem $news) {
        $args = $this->getUpdateNews()->parameters($news->toArray())->json();
        return $args['errcode'] == 0;
    }

    /**
     * 获取素材总数
     * @return array [ "voice_count":COUNT,
    "video_count":COUNT,
    "image_count":COUNT,
    "news_count":COUNT]
     * @throws \Exception
     */
    public function materialCount() {
        $args = $this->getCount()->json();
        if (array_key_exists('errcode', $args)) {
            throw new Exception($args['errmsg']);
        }
        return $args;
    }

    /**
     * 获取素材列表
     * @param string $type
     * @param int $offset
     * @param int $count
     * @return array
     * @throws Exception
     */
    public function mediaList($type, $offset = 0, $count = 20) {
        return $this->getMediaList()->parameters([
            'type' => $type,
            'offset' => $offset,
            'count' => $count
        ])->json();
    }

    /**
     * 打开已群发文章评论
     * @param $msg_data_id
     * @param null $index
     * @return bool
     * @throws Exception
     */
    public function openComment($msg_data_id, $index = null) {
        $args = $this->getOpenComment()->parameters(
            compact('msg_data_id', 'user_comment_id', 'index'))->json();
        return $args['errcode'] == 0;
    }

    /**
     * 关闭已群发文章评论
     * @param $msg_data_id
     * @param null $index
     * @return bool
     * @throws Exception
     */
    public function closeComment($msg_data_id, $index = null) {
        $args = $this->getCloseComment()->parameters(
            compact('msg_data_id', 'user_comment_id', 'index'))->json();
        return $args['errcode'] == 0;
    }

    /**
     * 查看指定文章的评论数据
     * @param $msg_data_id
     * @param $begin
     * @param $count
     * @param $type
     * @param null $index
     * @return bool
     * @throws \Exception
     */
    public function commentList($msg_data_id, $begin, $count, $type, $index = null) {
        if ($count > 50) {
            $count = 50;
        }
        $args = $this->getCommentList()->parameters(
            compact('msg_data_id', 'begin', 'count', 'type', 'index'))->json();
        if ($args['errcode'] != 0) {
            throw new Exception($args['errmsg']);
        }
        return $args;
    }

    /**
     * 将评论标记精选
     * @param $msg_data_id
     * @param $user_comment_id
     * @param null $index
     * @return bool
     * @throws Exception
     */
    public function markComment($msg_data_id, $user_comment_id, $index = null) {
        $args = $this->getMarkComment()->parameters(
            compact('msg_data_id', 'user_comment_id', 'index'))->json();
        return $args['errcode'] == 0;
    }

    /**
     * 将评论取消精选
     * @param $msg_data_id
     * @param $user_comment_id
     * @param null $index
     * @return bool
     * @throws Exception
     */
    public function unMarkComment($msg_data_id, $user_comment_id, $index = null) {
        $args = $this->getUnMarkComment()->parameters(
            compact('msg_data_id', 'user_comment_id', 'index'))->json();
        return $args['errcode'] == 0;
    }

    /**
     * 删除评论
     * @param $msg_data_id
     * @param $user_comment_id
     * @param null $index
     * @return bool
     * @throws Exception
     */
    public function deleteComment($msg_data_id, $user_comment_id, $index = null) {
        $args = $this->getDeleteComment()->parameters(
            compact('msg_data_id', 'user_comment_id', 'index'))->json();
        return $args['errcode'] == 0;
    }

    /**
     * 回复评论
     * @param $msg_data_id
     * @param $user_comment_id
     * @param null $index
     * @return bool
     * @throws Exception
     */
    public function addComment($msg_data_id, $user_comment_id, $content, $index = null) {
        $args = $this->getAddComment()->parameters(
            compact('msg_data_id', 'user_comment_id', 'content', 'index'))->json();
        return $args['errcode'] == 0;
    }

    /**
     *  删除回复
     * @param $msg_data_id
     * @param $user_comment_id
     * @param null $index
     * @return bool
     * @throws Exception
     */
    public function deleteReplyComment($msg_data_id, $user_comment_id, $index = null) {
        $args = $this->getDeleteReplyComment()->parameters(
            compact('msg_data_id', 'user_comment_id', 'index'))->json();
        return $args['errcode'] == 0;
    }
}