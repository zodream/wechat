<?php
declare(strict_types=1);
namespace Zodream\ThirdParty\WeChat;


enum MessageEnum: string {
    case Text = 'text';
    case Image = 'image';
    case Voice = 'voice';
    case Video = 'video';
    case Music = 'music';
    case News = 'news';

    /**
     * 小视频
     */
    case ShortVideo = 'shortvideo';
    /**
     * 位置
     */
    case Location = 'location';
    /**
     * 链接
     */
    case Link = 'link';

    /**
     * 转发客服
     */
    case Service = 'transfer_customer_service';
}