<?php
namespace Zodream\ThirdParty\WeChat;

use Zodream\Http\Http;
use Exception;

/**
 * 自定义菜单
 * User: zx648
 * Date: 2016/8/19
 * Time: 22:31
 */
class Menu extends BaseWeChat {

    /**
     * @return Http
     * @throws Exception
     */
    public function getCreate() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/menu/create')
            ->maps([
                '#button',
            ]);
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getList() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/menu/get');
    }

    /**
     * @return Http
     * @throws Exception
     */
    public function getDelete() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/menu/delete');
    }

    /**
     * 获取自定义菜单配置接口
     * @return Http
     * @throws \Exception
     */
    public function getInfo() {
        return $this->getBaseHttp('https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info');
    }


    /**
     * CREATE MENU
     * @param MenuItem|array $menu
     * @return bool
     * @throws \Exception
     */
    public function create($menu) {
        $args = $this->getCreate()
            ->parameters(is_object($menu) && method_exists($menu, 'toArray')
                ? $menu->toArray() : $menu)
            ->json();
        if ($args['errcode'] === 0) {
            return true;
        }
        throw new \Exception($args['errmsg']);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function menuList() {
        return $this->getList()->json();
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