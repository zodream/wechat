<?php
namespace Zodream\ThirdParty\WeChat;

use Zodream\Http\Http;
use Exception;
/**
 * 自定义个性化菜单
 * User: zx648
 * Date: 2016/8/20
 * Time: 0:01
 */
class PersonalMenu extends BaseWeChat {

    /**
     * @return Http
     * @throws Exception
     */
    public function getCreate() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/menu/addconditional')
            ->maps([
                '#button',
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getDelete() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/menu/delconditional')
            ->maps([
                '#menuid',
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getTest() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/menu/trymatch')
            ->maps([
                '#user_id',
            ]);
    }

    /**
     * @param MenuItem $menu
     * @return bool
     * @throws \Exception
     */
    public function create(MenuItem $menu) {
        $args = $this->getCreate()->parameters($menu->toArray())->json();
        if (array_key_exists('menuid', $args)) {
            return $args['menuid'];
        }
        return false;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function deleteMenu() {
        $arg = $this->getDelete()->json();
        return is_array($arg) && $arg['errcode'] == 0;
    }
}