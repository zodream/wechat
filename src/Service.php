<?php
declare(strict_types=1);
namespace Zodream\ThirdParty\WeChat;

use Zodream\Http\HttpClient;
use Exception;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/12
 * Time: 14:37
 */
class Service extends BaseWeChat {

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getList(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/customservice/getkflist');
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getOnline(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/customservice/getonlinekflist');
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getAdd(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/customservice/kfaccount/add')
            ->maps([
                '#kf_account',
                '#nickname',
                '#password'
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getUpdate(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/customservice/kfaccount/update')
            ->maps([
                '#kf_account',
                '#nickname',
                '#password'
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getUpload(): HttpClient {
        return $this->getBaseHttp()
            ->url('http://api.weixin.qq.com/customservice/kfaccount/uploadheadimg', [
                '#access_token',
                '#kf_account'
            ])->maps([
                '#media',
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getDelete(): HttpClient {
        return $this->getBaseHttp()
            ->url('https://api.weixin.qq.com/customservice/kfaccount/del', [
                    '#access_token',
                    '#kf_account'
                ]);
    }

    /**
     * 客服会话控制
     * @return HttpClient
     * @throws \Exception
     */
    public function getCreate(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/customservice/kfsession/create')
            ->maps([
                '#openid',
                '#kf_account',
                'text'
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getClose(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/customservice/kfsession/close')
            ->maps([
                '#openid',
                '#kf_account',
                'text'
            ]);
    }

    /**
     * 获取客户的会话状态
     * @return HttpClient
     * @throws \Exception
     */
    public function getSession(): HttpClient {
        return $this->getBaseHttp()
            ->url('https://api.weixin.qq.com/customservice/kfsession/getsession', [
                    '#access_token',
                    '#openid'
                ]);
    }

    /**
     * 获取客服的会话列表
     * @return HttpClient
     * @throws \Exception
     */
    public function getKfSession(): HttpClient {
        return $this->getBaseHttp()
            ->url('https://api.weixin.qq.com/customservice/kfsession/getsessionlist',
                [
                    '#access_token',
                    '#kf_account'
                ]);
    }

    /**
     * 获取未接入会话列表
     * @return HttpClient
     * @throws \Exception
     */
    public function getWait(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/customservice/kfsession/getwaitcase');
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getRecord(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/customservice/msgrecord/getrecord')
            ->maps([
                '#access_token',
                '#starttime',
                '#endtime',
                'pagesize' => 50,
                'pageindex' => 1
            ]);
    }
}