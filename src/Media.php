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

class Media extends BaseWeChat {
    const IMAGE = 'image';
    const VOICE = 'voice';
    const VIDEO = 'video';
    const THUMB = 'thumb';
    const NEWS = 'news';

    protected $apiMap = [
        'uploadTemp' => [
            [
                'https://api.weixin.qq.com/cgi-bin/media/upload',
                [
                    '#access_token',
                    '#type'
                ]
            ],
            '#media',
            'POST'
        ],
        'downloadTemp' => [
            'https://api.weixin.qq.com/cgi-bin/media/get',
            [
                '#access_token',
                '#media_id'
            ]
        ],
        'addNews' => [
            [
                'https://api.weixin.qq.com/cgi-bin/material/add_news',
                '#access_token'
            ],
            '#articles',
            'POST'
        ],
        'uploadImg' => [
            [
                'https://api.weixin.qq.com/cgi-bin/media/uploadimg',
                '#access_token'
            ],
            '#media',
            'POST'
        ],
        'addMedia' => [
            [
                'https://api.weixin.qq.com/cgi-bin/material/add_material',
                [
                    '#access_token',
                    '#type'
                ],
                '#media'
            ],
            'POST'
        ],
        'getMedia' => [
            [
                'https://api.weixin.qq.com/cgi-bin/material/get_material',
                '#access_token'
            ],
            '#media_id',
            'POST'
        ],
        'deleteMedia' => [
            [
                'https://api.weixin.qq.com/cgi-bin/material/del_material',
                '#access_token'
            ],
            '#media_id',
            'POST'
        ],
        'updateNews' => [
            [
                'https://api.weixin.qq.com/cgi-bin/material/update_news',
                '#access_token'
            ],
            [
                '#articles',
                '#media_id',
                'index'
            ],
            'POST'
        ],
        'count' => [
            'https://api.weixin.qq.com/cgi-bin/material/get_materialcount',
            '#access_token'
        ],
        'mediaList' => [
            [
                'https://api.weixin.qq.com/cgi-bin/material/batchget_material',
                '#access_token'
            ],
            [
                '#type',
                '#offset',
                '#count'
            ],
            'POST'
        ],
        'openComment' => [
            [
                'https://api.weixin.qq.com/cgi-bin/comment/open',
                '#access_token'
            ],
            [
                '#msg_data_id',
                'index'
            ],
            'POST'
        ],
        'closeComment' => [
            [
                'https://api.weixin.qq.com/cgi-bin/comment/close',
                '#access_token'
            ],
            [
                '#msg_data_id',
                'index'
            ],
            'POST'
        ],
        'commentList' => [
            [
                'https://api.weixin.qq.com/cgi-bin/comment/list',
                '#access_token'
            ],
            [
                '#msg_data_id',
                'index',
                '#begin',
                '#count',  //	获取数目（>=50会被拒绝）
                '#type'   //type=0 普通评论&精选评论 type=1 普通评论 type=2 精选评论
            ],
            'POST'
        ],
        'markComment' => [
            [
                'https://api.weixin.qq.com/cgi-bin/comment/markelect',
                '#access_token'
            ],
            [
                '#msg_data_id',
                'index',
                '#user_comment_id'
            ],
            'POST'
        ],
        'unMarkComment' => [
            [
                'https://api.weixin.qq.com/cgi-bin/comment/unmarkelect',
                '#access_token'
            ],
            [
                '#msg_data_id',
                'index',
                '#user_comment_id'
            ],
            'POST'
        ],
        'addComment' => [
            [
                'https://api.weixin.qq.com/cgi-bin/comment/reply/add',
                '#access_token'
            ],
            [
                '#msg_data_id',
                'index',
                '#user_comment_id',
                '#content'
            ],
            'POST'
        ],
        'deleteComment' => [
            [
                'https://api.weixin.qq.com/cgi-bin/comment/delete',
                '#access_token'
            ],
            [
                '#msg_data_id',
                'index',
                '#user_comment_id'
            ],
            'POST'
        ],
        'deleteReplyComment' => [
            [
                'https://api.weixin.qq.com/cgi-bin/comment/reply/delete',
                '#access_token'
            ],
            [
                '#msg_data_id',
                'index',
                '#user_comment_id'
            ],
            'POST'
        ],
    ];

    /**
     * @param string|File $file
     * @param string $type
     * @return array [type, media_id, created_at]
     */
    public function uploadTemp($file, $type) {
        return $this->getJson('uploadTemp', [
            'media' => '@'.$file,
            'type' => $type
        ]);
    }

    public function downloadTemp($mediaId, $file, $type = null) {
        $url = $this->getUrl('downloadTemp', [
            'media_id' => $mediaId
        ]);
        if ($type == self::VIDEO) {
            $url->setScheme('http');
        }
        return $this->http->download($url, $file);
    }

    /**
     *
     * @param NewsItem $news
     * @return string|bool media_id
     */
    public function addNews(NewsItem $news) {
        $args = $this->getJson('addNews', $news->toArray());
        return array_key_exists('media_id', $args) ? $args['media_id'] : false;
    }

    /**
     *
     * @param $file
     * @return string|bool url
     */
    public function uploadImg($file) {
        $args = $this->getJson('uploadImg', [
            'media' => '@'.$file
        ]);
        return array_key_exists('url', $args) ? $args['url'] : false;
    }

    public function addMedia($file, $type, $title = null, $introduction = null) {
        $args = $this->getJson('addMedia', [
            'type' => $type,
            'media' => '@'.$file
        ]);
        if ($type == self::VIDEO) {
            $args = $this->http->post([
                'description' => json_encode([
                    'title' => $title,
                    'introduction' => $introduction
                ])
            ]);
        }
        return $args;
    }

    public function getMedia($mediaId, $file = null) {
        $args = $this->getByApi('getMedia', [
            'media_id' => $mediaId
        ]);
        if (empty($file)) {
            return $args;
        }
        if (!$file instanceof File) {
            $file = new File($file);
        }
        return $file->write($args);
    }

    public function deleteMedia($mediaId) {
        $args = $this->getJson('deleteMedia', [
            'media_id' => $mediaId
        ]);
        return $args['errcode'] == 0;
    }

    public function updateNews(NewsItem $news) {
        $args = $this->getJson('updateNews', $news->toArray());
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
        $args = $this->getJson('count');
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
     */
    public function mediaList($type, $offset = 0, $count = 20) {
        return $this->getJson('mediaList', [
            'type' => $type,
            'offset' => $offset,
            'count' => $count
        ]);
    }

    /**
     * 打开已群发文章评论
     * @param $msg_data_id
     * @param null $index
     * @return bool
     */
    public function openComment($msg_data_id, $index = null) {
        $args = $this->getJson(__FUNCTION__,
            compact('msg_data_id', 'user_comment_id', 'index'));
        return $args['errcode'] == 0;
    }

    /**
     * 关闭已群发文章评论
     * @param $msg_data_id
     * @param null $index
     * @return bool
     */
    public function closeComment($msg_data_id, $index = null) {
        $args = $this->getJson(__FUNCTION__,
            compact('msg_data_id', 'user_comment_id', 'index'));
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
        $args = $this->getJson(__FUNCTION__,
            compact('msg_data_id', 'begin', 'count', 'type', 'index'));
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
     */
    public function markComment($msg_data_id, $user_comment_id, $index = null) {
        $args = $this->getJson(__FUNCTION__,
            compact('msg_data_id', 'user_comment_id', 'index'));
        return $args['errcode'] == 0;
    }

    /**
     * 将评论取消精选
     * @param $msg_data_id
     * @param $user_comment_id
     * @param null $index
     * @return bool
     */
    public function unMarkComment($msg_data_id, $user_comment_id, $index = null) {
        $args = $this->getJson(__FUNCTION__,
            compact('msg_data_id', 'user_comment_id', 'index'));
        return $args['errcode'] == 0;
    }

    /**
     * 删除评论
     * @param $msg_data_id
     * @param $user_comment_id
     * @param null $index
     * @return bool
     */
    public function deleteComment($msg_data_id, $user_comment_id, $index = null) {
        $args = $this->getJson(__FUNCTION__,
            compact('msg_data_id', 'user_comment_id', 'index'));
        return $args['errcode'] == 0;
    }

    /**
     * 回复评论
     * @param $msg_data_id
     * @param $user_comment_id
     * @param null $index
     * @return bool
     */
    public function addComment($msg_data_id, $user_comment_id, $content, $index = null) {
        $args = $this->getJson(__FUNCTION__,
            compact('msg_data_id', 'user_comment_id', 'content', 'index'));
        return $args['errcode'] == 0;
    }

    /**
     *  删除回复
     * @param $msg_data_id
     * @param $user_comment_id
     * @param null $index
     * @return bool
     */
    public function deleteReplyComment($msg_data_id, $user_comment_id, $index = null) {
        $args = $this->getJson(__FUNCTION__,
            compact('msg_data_id', 'user_comment_id', 'index'));
        return $args['errcode'] == 0;
    }
}