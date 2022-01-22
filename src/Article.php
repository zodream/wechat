<?php
declare(strict_types=1);
namespace Zodream\ThirdParty\WeChat;

/**
 * 草稿
 */
class Article extends BaseWeChat {

    public function getAdd() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/draft/add')
            ->maps([
                '#articles',
            ]);
    }

    public function getArticle() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/draft/get')
            ->maps([
                '#media_id',
            ]);
    }

    public function getUpdate() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/draft/update')
            ->maps([
                '#media_id',
                '#index',
                '#articles',
            ]);
    }

    public function getRemove() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/draft/delete')
            ->maps([
                '#media_id',
            ]);
    }

    public function getTotal() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/draft/count');
    }

    public function getList() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/draft/batchget')
            ->maps([
                '#offset',
                '#count',
                'no_content'
            ]);
    }

    public function getPublish() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/freepublish/submit')
            ->maps([
                '#media_id',
            ]);
    }

    public function getQueryPublish() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/freepublish/get')
            ->maps([
                '#publish_id',
            ]);
    }

    public function getDeletePublish() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/freepublish/delete')
            ->maps([
                '#article_id',
                'index'
            ]);
    }

    public function getPublishList() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/freepublish/batchget')
            ->maps([
                '#offset',
                '#count',
                'no_content'
            ]);
    }

    /**
     * 增加草稿
     * @param NewsItem $data
     * @return string
     * @throws \Exception
     */
    public function add(NewsItem $data) {
        $args = $this->getAddNews()->parameters($data->toArray())->json();
        if (array_key_exists('media_id', $args)) {
            return $args['media_id'];
        }
        throw new \Exception($args['errmsg']);
    }

    /**
     * @param string $mediaId
     * @return array [{
    "title":TITLE,
    "author":AUTHOR,
    "digest":DIGEST,
    "content":CONTENT,
    "content_source_url":CONTENT_SOURCE_URL,
    "thumb_media_id":THUMB_MEDIA_ID,
    "show_cover_pic":1,
    "need_open_comment":0,
    "only_fans_can_comment":0,
    "url":URL
    }]
     * @throws \Exception
     */
    public function article(string $mediaId): array {
        $args = $this->getArticle()->parameters([
            'media_id' => $mediaId
        ])->json();
        if (array_key_exists('news_item', $args)) {
            return $args['news_item'];
        }
        throw new \Exception($args['errmsg']);
    }

    public function update(NewsItem $data): bool {
        $args = $this->getUpdate()->parameters($data->toArray())->json();
        if ($args['errcode'] === 0) {
            return true;
        }
        throw new \Exception($args['errmsg']);
    }

    public function remove(string $mediaId): bool {
        $args = $this->getRemove()->parameters([
            'media_id' => $mediaId
        ])->json();
        if ($args['errcode'] === 0) {
            return true;
        }
        throw new \Exception($args['errmsg']);
    }

    public function total(): int {
        $args = $this->getTotal()->json();
        if (array_key_exists('total_count', $args)) {
            return $args['total_count'];
        }
        throw new \Exception($args['errmsg']);
    }

    /**
     * 发布草稿
     * @param string $mediaId
     * @return string 任务id
     * @throws \Exception
     */
    public function publish(string $mediaId): string {
        $args = $this->getPublish()->parameters([
            'media_id' => $mediaId
        ])->json();
        if ($args['errcode'] === 0) {
            return $args['publish_id'];
        }
        throw new \Exception($args['errmsg']);
    }

    /**
     * 查询发布状态
     * @param string $publishId
     * @return array {
    "publish_id":"100000001",
    "publish_status":0, // 发布状态，0:成功, 1:发布中，2:原创失败, 3: 常规失败, 4:平台审核不通过, 5:成功后用户删除所有文章, 6: 成功后系统封禁所有文章
    "article_id":ARTICLE_ID,
    "article_detail":{
    "count":1,
    "item":[
    {
    "idx":1,
    "article_url": ARTICLE_URL
    }
    //如果 count 大于 1，此处会有多篇文章
    ]
    },
    "fail_idx": []
    }
     * @throws \Exception
     */
    public function queryPublish(string $publishId): array {
        $args = $this->getQueryPublish()->parameters([
            'publish_id' => $publishId
        ])->json();
        if (array_key_exists('publish_id', $args)) {
            return $args;
        }
        throw new \Exception($args['errmsg']);
    }

    /**
     * 删除发布
     * @param string $articleId
     * @param int $index 要删除的文章在图文消息中的位置，第一篇编号为1，该字段不填或填0会删除全部文章
     * @return bool
     * @throws \Exception
     */
    public function deletePublish(string $articleId, int $index = 0): bool {
        $args = $this->getDeletePublish()->parameters([
            'article_id' => $articleId,
            'index' => $index
        ])->json();
        if ($args['errcode'] === 0) {
            return true;
        }
        throw new \Exception($args['errmsg']);
    }

    /**
     * @param int $offset
     * @param int $count
     * @param bool $noContent
     * @return array {
    "total_count":TOTAL_COUNT,
    "item_count":ITEM_COUNT,
    "item":[
    {
    "media_id":MEDIA_ID,
    "content": {
    "news_item" : [
    {
    "title":TITLE,
    "author":AUTHOR,
    "digest":DIGEST,
    "content":CONTENT,
    "content_source_url":CONTENT_SOURCE_URL,
    "thumb_media_id":THUMB_MEDIA_ID,
    "show_cover_pic":1,
    "need_open_comment":0,
    "only_fans_can_comment":0,
    "url":URL
    },
    //多图文消息会在此处有多篇文章
    ]
    },
    "update_time": UPDATE_TIME
    },
    //可能有多个图文消息item结构
    ]
    }
     * @throws \Exception
     */
    public function query(int $offset = 0, int $count = 20, bool $noContent = false): array {
        $args = $this->getList()->parameters([
            'offset' => $offset,
            'count' => $count,
            'no_content' => (int)$noContent
        ])->json();
        if (array_key_exists('total_count', $args)) {
            return $args;
        }
        throw new \Exception($args['errmsg']);
    }

    /**
     * 获取成功发布列表
     * @param int $offset
     * @param int $count
     * @param bool $noContent
     * @return array
     * @throws \Exception
     */
    public function publishList(int $offset = 0, int $count = 20, bool $noContent = false): array {
        $args = $this->getPublishList()->parameters([
            'offset' => $offset,
            'count' => $count,
            'no_content' => (int)$noContent
        ])->json();
        if (array_key_exists('total_count', $args)) {
            return $args;
        }
        throw new \Exception($args['errmsg']);
    }
}