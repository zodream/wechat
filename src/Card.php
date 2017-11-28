<?php
namespace Zodream\ThirdParty\WeChat;
/**
 * 微信卡券
 * @package Zodream\Domain\ThirdParty\WeChat
 */
class Card extends BaseWeChat {

    protected $apiMap = [
        'create' => [
            [
                'https://api.weixin.qq.com/card/create',
                '#access_token'
            ],
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
            ],
            'POST'
        ],
        'paycell' => [ // 设置买单接口
            [
                'https://api.weixin.qq.com/card/paycell/set',
                '#access_token'
            ],
            [
                '#card_id',
                'is_open' => false
            ],
            'POST'
        ],
        'selfconsumecell' => [ // 设置自助核销接口
            [
                'https://api.weixin.qq.com/card/selfconsumecell/set',
                '#access_token'
            ],
            [
                '#card_id',
                'is_open' => false,
                'need_verify_cod' => false,
                'need_remark_amount' => false
            ],
            'POST'
        ],
        'createQr' => [
            [
                'https://api.weixin.qq.com/card/qrcode/create',
                '#access_token'
            ],
            [
                'action_name' => 'QR_CARD',
                'expire_seconds',
                '#action_info'
            ],
            'POST'
        ],
        'landingpage' => [ //创建货架接口
            [
                'https://api.weixin.qq.com/card/landingpage/create',
                '#access_token'
            ],
            [
                '#banner',
                '#title',
                '#can_share',
                '#scene',
                '#card_list',
                '#card_id',
                '#thumb_url'
            ],
            'POST'
        ],
        'getCode' => [
            [
                'https://api.weixin.qq.com/card/code/get',
                '#access_token'
            ],
            [
                '#code',
                'card_id',
                'check_consume'
            ],
            'POST'
        ],
        'getUserCards' => [
            [
                'https://api.weixin.qq.com/card/user/getcardlist',
                '#access_token'
            ],
            [
                '#openid',
                'card_id',
            ],
            'POST'
        ],
        'cardInfo' => [
            [
                'https://api.weixin.qq.com/card/get',
                '#access_token'
            ],
            '#card_id',
            'POST'
        ],
        'cardList' => [
            [
                'https://api.weixin.qq.com/card/batchget',
                '#access_token'
            ],
            [
                'offset' => 0,
                'count' => 50,
                'status_list'  //“CARD_STATUS_NOT_VERIFY”,待审核；“CARD_STATUS_VERIFY_FAIL”,审核失败；“CARD_STATUS_VERIFY_OK”，通过审核；“CARD_STATUS_DELETE”，卡券被商户删除；“CARD_STATUS_DISPATCH”，在公众平台投放过的卡券；
            ],
            'POST'
        ],
        'updateCard' => [
            [
                'https://api.weixin.qq.com/card/update',
                '#access_token'
            ],
            [
                '#card_id',
                '#member_card'
            ],
            'POST'
        ],
        'modifystock' => [
            [
                'https://api.weixin.qq.com/card/modifystock',
                '#access_token'
            ],
            [
                '#card_id',
                'increase_stock_value',
                'reduce_stock_value'
            ],
            'POST'
        ],
        'updateCode' => [
            [
                'https://api.weixin.qq.com/card/code/update',
                '#access_token'
            ],
            [
                '#card_id',
                '#code',
                '#new_code'
            ],
            'POST'
        ],
        'delCard' => [
            [
                'https://api.weixin.qq.com/card/delete',
                '#access_token'
            ],
            '#card_id',
            'POST'
        ],
        'delCode' => [
            [
                'https://api.weixin.qq.com/card/code/unavailable',
                '#access_token'
            ],
            [
                '#card_id',
                '#code',
                'reason'
            ],
            'POST'
        ],
        'query' => [
            [
                'https://api.weixin.qq.com/datacube/getcardbizuininfo',
                '#access_token'
            ],
            [
                '#begin_date',
                '#end_date',
                'cond_source' => 0
            ],
            'POST'
        ],
        'queryFree' => [
            [
                'https://api.weixin.qq.com/datacube/getcardcardinfo',
                '#access_token'
            ],
            [
                '#begin_date',
                '#end_date',
                'cond_source' => 0,
                'card_id'
            ],
            'POST'
        ],
        'getcardmembercardinfo' => [
            [
                'https://api.weixin.qq.com/datacube/getcardmembercardinfo',
                '#access_token'
            ],
            [
                '#begin_date',
                '#end_date',
                'cond_source' => 0,
            ],
            'POST'
        ],
        'getcardmembercarddetail' => [
            [
                'https://api.weixin.qq.com/datacube/getcardmembercarddetail',
                '#access_token'
            ],
            [
                '#begin_date',
                '#end_date',
                '#card_id',
            ],
            'POST'
        ]
    ];

    /**
     * 创建卡券
     * @param array $card
     * @return bool
     */
    public function create(array $card) {
        $args = $this->getJson('create', [
            'card' => $card
        ]);
        return $args['errcode'] == 0;
    }

    /**
     * 获取卡券详细信息
     * @param $card_id
     * @return mixed
     */
    public function getInfo($card_id) {
        $card = $this->getJson('cardInfo', [
            'card_id' => $card_id
        ]);
        if ($card['errcode'] == 0) {
            return $card['card'];
        }
        throw new Exception($card['errmsg']);
    }

    /**
     * 获取卡券列表
     * @param array $status_list
     * @param int $offset
     * @param int $count
     * @return mixed [  "card_id_list":["ph_gmt7cUVrlRk8swPwx7aDyF-pg"],"total_num":1]
     */
    public function getList(array $status_list, $offset = 0, $count = 20) {
        return $this->getJson('cardList', compact('status_list', 'offset', 'count'));
    }
}