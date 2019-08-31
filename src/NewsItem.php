<?php
namespace Zodream\ThirdParty\WeChat;
/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/8/20
 * Time: 11:13
 */

class NewsItem {
    protected $title;
    protected $thumb;
    protected $author;
    protected $digest;
    /**
     * @var bool
     */
    protected $showCover;
    protected $content;
    protected $url;

    /**
     * 是否打开评论，0不打开，1打开
     * @var int
     */
    protected $needOpenComment = false;

    /**
     * 是否粉丝才可评论，0所有人可评论，1粉丝才可评论
     * @var int
     */
    protected $onlyFansCanComment = false;

    /**
     * @var NewsItem[]
     */
    protected $articles = [];

    /**
     * UPDATE NEWS
     * @var string
     */
    protected $mediaId;
    protected $index = 0;

    public function setTitle($arg) {
        $this->title = $arg;
        return $this;
    }

    /**
     * 图文消息的封面图片素材id（必须是永久 media_ID）
     * @param $arg
     * @return $this
     */
    public function setThumb($arg) {
        $this->thumb = $arg;
        return $this;
    }

    /**
     * 作者
     * @param $arg
     * @return $this
     */
    public function setAuthor($arg) {
        $this->author = $arg;
        return $this;
    }

    /**
     * 图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空
     * @param $arg
     * @return $this
     */
    public function setDigest($arg) {
        $this->digest = $arg;
        return $this;
    }

    /**
     * 是否显示封面，0为false，即不显示，1为true，即显示
     * @param $arg
     * @return $this
     */
    public function setShowCover($arg) {
        $this->showCover = boolval($arg);
        return $this;
    }

    /**
     * 图文消息的原文地址，即点击“阅读原文”后的URL
     * @param $arg
     * @return $this
     */
    public function setUrl($arg) {
        $this->url = $arg;
        return $this;
    }

    /**
     * 图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS
     * @param $arg
     * @return $this
     */
    public function setContent($arg) {
        $this->content = $arg;
        return $this;
    }

    /**
     * @param $arg
     * @return $this
     */
    public function setMediaId($arg) {
        $this->mediaId = $arg;
        return $this;
    }

    /**
     * @param $arg
     * @return $this
     */
    public function setIndex($arg) {
        $this->index = $arg;
        return $this;
    }

    /**
     * @param $arg
     * @return $this
     */
    public function setArticle($arg) {
        if (is_array($arg)) {
            $this->articles = $arg;
        } else {
            $this->articles[] = $arg;
        }
        return $this;
    }

    /**
     * 是否打开评论，0不打开，1打开
     * @param $arg
     * @return $this
     */
    public function setNeedOpenComment($arg) {
        $this->needOpenComment = boolval($arg);
        return $this;
    }

    /**
     * 是否粉丝才可评论，0所有人可评论，1粉丝才可评论
     * @param $arg
     * @return $this
     */
    public function setOnlyFansCanComment($arg) {
        $this->onlyFansCanComment = boolval($arg);
        return $this;
    }

    public function toArray() {
        if (empty($this->articles)) {
            return $this->getArticle();
        }
        $args = array_splice($this->articles, 0, 8);
        $data = [
            'articles' => array_map([$this, 'getArray'], $args)
        ];
        if (!empty($this->mediaId)) {
            $data['media_id'] = $this->mediaId;
            $data['index'] = $this->index;
        }
        return $data;
    }

    protected function getArray(NewsItem $item) {
        if (count($this->articles) > 1) {
            $this->setDigest(null);
        }
        return $item->toArray();
    }

    protected function getArticle() {
        return [
            'title' => $this->title,
            'thumb_media_id' => $this->thumb,
            'author' => $this->author,
            'digest' => $this->digest,
            'show_cover_pic' => intval($this->showCover),
            'content' => $this->content,
            'content_source_url' => $this->url,
            'need_open_comment' => intval($this->needOpenComment),
            'only_fans_can_comment' => intval($this->onlyFansCanComment)
        ];
    }

}