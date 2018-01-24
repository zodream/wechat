<?php
namespace Zodream\ThirdParty\WeChat;

use Zodream\Http\Http;
use Exception;

/**
 * 微信卡券
 * @package Zodream\Domain\ThirdParty\WeChat
 */
class Card extends BaseWeChat {

    /**
     * @return Http
     * @throws Exception
     */
    public function getCreate() {
        return $this->getBaseHttp('https://api.weixin.qq.com/card/create')
            ->maps([
                '#card' => [
                    'card_type' => 'GROUPON', //CASH、 GIFT、GENERAL_COUPON
                    'groupon' => [
                        'base_info' => [
                            '#logo_url',
                            '#code_type',
                            '#brand_name',
                            '#title',
                            '#color',
                            '#notice',
                            '#description',
                            '#sku',
                            '#quantity',
                            '#date_info',
                            '#type',
                            '#begin_time',
                            '#end_time',
                            '#fixed_term',
                            '#fixed_begin',
                            '#end_time',
                            ''
                        ]
                    ]
                ]
            ]);
    }

    /**
     * 设置买单接口
     * @return Http
     * @throws \Exception
     */
    public function getPaycell() {
        return $this->getBaseHttp('https://api.weixin.qq.com/card/paycell/set')
            ->maps([
                '#card_id',
                'is_open' => false
            ]);
    }

    /**
     * 设置自助核销接口
     * @return Http
     * @throws \Exception
     */
    public function getSelfconsumecell() {
        return $this->getBaseHttp('https://api.weixin.qq.com/card/selfconsumecell/set')
            ->maps([
                '#card_id',
                'is_open' => false,
                'need_verify_cod' => false,
                'need_remark_amount' => false
            ]);
    }

    public function getCreateQr() {
        return $this->getBaseHttp('https://api.weixin.qq.com/card/qrcode/create')
            ->maps([
                'action_name' => 'QR_CARD',
                'expire_seconds',
                '#action_info'
            ]);
    }

    /**
     * 创建货架接口
     * @return Http
     * @throws \Exception
     */
    public function getLandingpage() {
        return $this->getBaseHttp('https://api.weixin.qq.com/card/landingpage/create')
            ->maps([
                '#banner',
                '#page_title',
                '#can_share',
                '#scene',
                '#card_list', //['#card_id','#thumb_url']
            ]);
    }

    public function getCode() {
        return $this->getBaseHttp('https://api.weixin.qq.com/card/code/get')
            ->maps([
                '#code',
                'card_id',
                'check_consume'
            ]);
    }

    public function getUserCards() {
        return $this->getBaseHttp('https://api.weixin.qq.com/card/user/getcardlist')
            ->maps([
                '#openid',
                'card_id',
            ]);
    }

    public function getCardInfo() {
        return $this->getBaseHttp('https://api.weixin.qq.com/card/get')
            ->maps([
                '#card_id'
            ]);
    }

    public function getCardList() {
        return $this->getBaseHttp('https://api.weixin.qq.com/card/batchget')
            ->maps([
                'offset' => 0,
                'count' => 50,
                'status_list'  //“CARD_STATUS_NOT_VERIFY”,待审核；“CARD_STATUS_VERIFY_FAIL”,审核失败；“CARD_STATUS_VERIFY_OK”，通过审核；“CARD_STATUS_DELETE”，卡券被商户删除；“CARD_STATUS_DISPATCH”，在公众平台投放过的卡券；
            ]);
    }

    public function getUpdateCard() {
        return $this->getBaseHttp('https://api.weixin.qq.com/card/update')
            ->maps([
                '#card_id',
                '#member_card'
            ]);
    }

    public function getModifystock() {
        return $this->getBaseHttp('https://api.weixin.qq.com/card/modifystock')
            ->maps([
                '#card_id',
                'increase_stock_value',
                'reduce_stock_value'
            ]);
    }

    public function getUpdateCode() {
        return $this->getBaseHttp('https://api.weixin.qq.com/card/code/update')
            ->maps([
                '#card_id',
                '#code',
                '#new_code'
            ]);
    }

    public function getDelCard() {
        return $this->getBaseHttp('https://api.weixin.qq.com/card/delete')
            ->maps([
                '#card_id',
            ]);
    }

    public function getDelCode() {
        return $this->getBaseHttp('https://api.weixin.qq.com/card/code/unavailable')
            ->maps([
                '#card_id',
                '#code',
                'reason'
            ]);
    }

    public function getQuery() {
        return $this->getBaseHttp('https://api.weixin.qq.com/datacube/getcardbizuininfo')
            ->maps([
                '#begin_date',
                '#end_date',
                'cond_source' => 0
            ]);
    }

    public function getQueryFree() {
        return $this->getBaseHttp('https://api.weixin.qq.com/datacube/getcardcardinfo')
            ->maps([
                '#begin_date',
                '#end_date',
                'cond_source' => 0,
                'card_id'
            ]);
    }

    public function getCardMemberCardInfo() {
        return $this->getBaseHttp('https://api.weixin.qq.com/datacube/getcardmembercardinfo')
            ->maps([
                '#begin_date',
                '#end_date',
                'cond_source' => 0,
            ]);
    }

    public function getCardMemberCardDetail() {
        return $this->getBaseHttp('https://api.weixin.qq.com/datacube/getcardmembercarddetail')
            ->maps([
                '#begin_date',
                '#end_date',
                '#card_id',
            ]);
    }

    /**
     * 创建卡券
     * @param array $card
     * @return bool
     * @throws \Exception
     */
    public function create(array $card) {
        $args = $this->getCreate()->parameters([
            'card' => $card
        ])->json();
        return $args['errcode'] == 0;
    }

    /**
     * 获取卡券详细信息
     * @param $card_id
     * @return mixed
     * @throws \Exception
     */
    public function getInfo($card_id) {
        $card = $this->getCardInfo()->parameters([
            'card_id' => $card_id
        ])->json();
        if ($card['errcode'] == 0) {
            return $card['card'];
        }
        throw new \Exception($card['errmsg']);
    }

    /**
     * 获取卡券列表
     * @param array $status_list
     * @param int $offset
     * @param int $count
     * @return array [  "card_id_list":["ph_gmt7cUVrlRk8swPwx7aDyF-pg"],"total_num":1]
     * @throws \Exception
     */
    public function getList(array $status_list, $offset = 0, $count = 20) {
        return $this->getCardList()->parameters(compact('status_list',
            'offset', 'count'))->json();
    }

    /**
     * 创建卡券货架
     * @param array $card_list ['card_id' => '', 'thumb_url' => '']
     * @return array
     * @throws \Exception
     */
    public function createLandingPage($banner, $page_title, array $card_list, $scene = 'SCENE_IVR', $can_share = false) {
        return $this->getLandingpage()->parameters(compact('banner',
            'page_title', 'can_share', 'scene', 'card_list'))->json();
    }
}