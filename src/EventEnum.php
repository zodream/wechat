<?php
namespace Zodream\ThirdParty\WeChat;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/8/23
 * Time: 18:01
 */
use Zodream\Helpers\Enum;

class EventEnum extends Enum {
    const Message = 'message';
    /**
     * 普通关注
     * 二维码关注
     */
    const Subscribe = 'subscribe';
    const ScanSubscribe = 'scan_subscribe';
    /**
     * 取消关注
     */
    const UnSubscribe = 'unsubscribe';
    /**
     * 扫描二维码
     */
    const Scan = 'SCAN';
    /**
     * 地理位置
     */
    const Location = 'LOCATION';
    /**
     * 自定义菜单 - 点击菜单拉取消息时的事件推送
     * eventKey
     */
    const Click = 'CLICK';
    /**
     * 自定义菜单 - 点击菜单跳转链接时的事件推送
     * eventKey
     * 
     */
    const View = 'VIEW';
    /**
     * 自定义菜单 - 扫码推事件的事件推送
     */
    const ScanCodePush = 'scancode_push';
    /**
     * 自定义菜单 - 扫码推事件且弹出“消息接收中”提示框的事件推送
     */
    const ScanCodeWaitMsg = 'scancode_waitmsg';
    /**
     * 自定义菜单 - 弹出系统拍照发图的事件推送
     */
    const PicSysPhoto = 'pic_sysphoto';
    /**
     * 自定义菜单 - 弹出拍照或者相册发图的事件推送
     */
    const PicPhotoOrAlbum = 'pic_photo_or_album';
    /**
     * 自定义菜单 - 弹出微信相册发图器的事件推送
     */
    const PicWeChat = 'pic_weixin';
    /**
     * 自定义菜单 - 弹出地理位置选择器的事件推送
     */
    const LOCATION_SELECT = 'location_select';
    /**
     * 群发接口完成后推送的结果
     */
    const MassSendJobFinish = 'MASSSENDJOBFINISH';
    /**
     * 模板消息完成后推送的结果
     */
    const TemplateSendJobFinish = 'TEMPLATESENDJOBFINISH';

    /**
     * 草稿发布结果，
     * PublishEventInfo
     * publish_id
     * publish_status 发布状态，0:成功, 1:发布中，2:原创失败, 3: 常规失败, 4:平台审核不通过, 5:成功后用户删除所有文章, 6: 成功后系统封禁所有文章
     */
    const PublishJobFinish = 'PUBLISHJOBFINISH';

    /**
     * 客服接入会话
     */
    const KFCreateSession = 'kf_create_session';
    /**
     * 客服关闭会话
     */
    const KFCloseSession = 'kf_close_session';
    /**
     * 客服接入会话
     */
    const KFSwitchSession = 'kf_switch_session';

    /**
     * 门店审核事件推送
     */
    const PoiCheckNotify = 'poi_check_notify';
    /**
     * 买单事件推送
     */
    const UserPayFromPayCell = 'user_pay_from_pay_cell';
    /**
     * 生成的卡券通过审核
     */
    const CardPassCheck = 'card_pass_check';
    /**
     * 用户在领取卡券时
     */
    const UserGetCard = 'user_get_card';
    /**
     * 用户在转赠卡券时
     */
    const UserGiftingCard = 'user_gifting_card';
    /**
     * 用户在删除卡券时
     */
    const UserDelCard = 'user_del_card';
    /**
     * 卡券被核销
     */
    const UserConsumeCard = 'user_consume_card';
    /**
     * 用户在进入会员卡时
     */
    const UserViewCard = 'user_view_card';

    /**
     * 从卡券进入公众号会话事件推送
     */
    const UserEnterSessionFromCard = 'user_enter_session_from_card';
    /**
     * 当用户的会员卡积分余额发生变动时
     */
    const UpdateMemberCard = 'update_member_card';
    /**
     * 当某个card_id的初始库存数大于200且当前库存小于等于100时，用户尝试领券会触发发送事件给商户
     */
    const CardSkuRemind = 'card_sku_remind';
    /**
     * 当商户朋友的券券点发生变动时
     */
    const CardPayOrder = 'card_pay_order';
    /**
     * 用户通过一键激活的方式提交信息并点击激活或者用户修改会员卡信息后
     */
    const SubmitMembercardUserInfo = 'submit_membercard_user_info';
}