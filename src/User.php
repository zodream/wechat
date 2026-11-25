<?php
declare(strict_types=1);
namespace Zodream\ThirdParty\WeChat;

use Zodream\Http\HttpClient;
use Exception;

/**
 * 用户管理
 * User: zx648
 * Date: 2016/8/20
 * Time: 12:55
 */
class User extends BaseWeChat {

    /**
     * 标签
     * @return HttpClient
     * @throws \Exception
     */
    public function getCreateTag(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/tags/create')
            ->maps([
                '#tag' => [
                    '#name'
                ],
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getUpdateTag(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/tags/update')
            ->maps([
                '#tag' => [
                    '#id',
                    '#name'
                ],
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getDeleteTag(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/tags/delete')
            ->maps([
                '#tag' => [
                    '#id',
                ],
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getTags(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/tags/get');
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getTagUsers(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/user/tag/get')
            ->maps([
                '#tagid',
                'next_openid'
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getUserAddTag(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging')
            ->maps([
                '#tagid',
                '#openid_list'
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getUserDeleteTag(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging')
            ->maps([
                '#tagid',
                '#openid_list'
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getUserTags(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/tags/getidlist')
            ->maps([
                '#openid',
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getCreateGroup(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/groups/create')
            ->maps([
                '#group',
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getGroup(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/groups/get');
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getGroupId(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/groups/getid')
            ->maps([
                'openid',
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getUpdateGroup(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/groups/update')
            ->maps([
                '#group',
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getMoveUser(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/groups/members/update')
            ->maps([
                '#openid',
                '#to_groupid'
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getMoveUsers(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/groups/members/batchupdate')
            ->maps([
                '#openid_list',
                '#to_groupid'
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getDeleteGroup(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/groups/delete')
            ->maps([
                '#group',
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getMark(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/user/info/updateremark')
            ->maps([
                '#openid',
                '#remark'
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getInfo(): HttpClient {
        return $this->getBaseHttp()
            ->url('https://api.weixin.qq.com/cgi-bin/user/info', [
                    '#access_token',
                    '#openid',
                    'lang' => 'zh_CN'
                ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getUsersInfo(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/user/info/batchget')
            ->maps([
                '#user_list',
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getUserList(): HttpClient {
        return $this->getBaseHttp()
            ->url('https://api.weixin.qq.com/cgi-bin/user/get', [
                    '#access_token',
                    'next_openid'
                ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getBlackList(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/tags/members/getblacklist')
            ->maps([
                'access_token',
                'begin_openid',
            ]);
    }

    /**
     * 黑名单
     * @return HttpClient
     * @throws \Exception
     */
    public function getAddBlack(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/tags/members/batchblacklist')
            ->maps([
                'access_token',
                '#openid_list',
            ]);
    }

    /**
     * @return HttpClient
     * @throws Exception
     */
    public function getDeleteBlack(): HttpClient {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/tags/members/batchunblacklist')
            ->maps([
                'access_token',
                '#openid_list',
            ]);
    }

    /**
     *
     * @param string $name
     * @return bool|array ['id', 'name']
     * @throws \Exception
     */
    public function createGroup(string $name) {
        $args = $this->getCreateGroup()->parameters([
                'group' => ['name' => $name]
            ])->json();
        if (array_key_exists('group', $args)) {
            return $args['group'];
        }
        return false;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function group() {
        return $this->getGroup()->json();
    }

    /**
     * @param string $openId
     * @return bool|string groupid
     * @throws \Exception
     */
    public function getUserGroup($openId) {
        $args = $this->getGroupId()->parameters([
                'openid' => $openId
            ])->json();
        if (array_key_exists('groupid', $args)) {
            return $args['groupid'];
        }
        return false;
    }

    /**
     * @param string|integer $id
     * @param string $name
     * @return bool
     * @throws \Exception
     */
    public function updateGroup($id, $name) {
        $args = $this->getUpdateGroup()->parameters([
                'group' => [
                    'id' => $id,
                    'name' => $name
                ]
            ])->json();
        return $args['errcode'] == 0;
    }

    /**
     * @param $openId
     * @param $group
     * @return bool
     * @throws \Exception
     */
    public function moveUserGroup($openId, $group) {
        $args = $this->getMoveUser()->parameters([
                'openid' => $openId,
                'to_groupid' => $group
            ])->json();
        return $args['errcode'] == 0;
    }

    /**
     * @param array $openId
     * @param $group
     * @return bool
     * @throws \Exception
     */
    public function moveUsers(array $openId, $group) {
        $args = $this->getMoveUsers()->parameters([
                'openid_list' => $openId,
                'to_groupid' => $group
            ])->json();
        return $args['errcode'] == 0;
    }

    /**
     * @param $group
     * @return bool
     * @throws \Exception
     */
    public function deleteGroup($group) {
        $args = $this->getDeleteGroup()->parameters([
                'group' => [
                    'id' => $group
                ]
            ])->json();
        return $args['errcode'] == 0;
    }

    /**
     * 设置用户备注名
     * @param $openId
     * @param $remark
     * @return bool
     * @throws \Exception
     */
    public function markUser($openId, $remark) {
        $args = $this->getMark()->parameters([
                'openid' => $openId,
                'remark' => $remark
            ])->json();
        return $args['errcode'] == 0;
    }

    /**
     * UnionID 在不同平台是一致
     * @param $openId
     * @return mixed
     * @throws \Exception
     */
    public function userInfo($openId) {
        $user = $this->getInfo()->parameters([
            'openid' => $openId
        ])->json();
        if (!array_key_exists('nickname', $user)) {
            return false;
        }
        $user['username'] = $user['nickname'];
        $user['avatar'] = $user['headimgurl'];
        return $user;
    }

    /**
     * 获取用户基本信息
     * @param array $openId
     * @return mixed|null|string
     * @throws \Exception
     */
    public function usersInfo(array $openId) {
        $data = [];
        foreach ($openId as $item) {
            if (is_array($item)) {
                $data = $openId;
                break;
            }
            $data[] = [
                'openid' => $item,
                'lang' => 'zh-CN'
            ];
        }
        return $this->getUsersInfo()->parameters([
                'user_list' => $data
            ])->json();
    }

    /**
     * 获取用户列表
     * @param null $nextOpenId
     * @return array|mixed|null|string
     * @throws \Exception
     */
    public function userList($nextOpenId = null) {
        return $this->getUserList()->parameters([
            'next_openid' => $nextOpenId
        ])->json();
    }
}