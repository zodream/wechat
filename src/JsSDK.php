<?php
namespace Zodream\ThirdParty\WeChat;

use Zodream\Helpers\Str;
use Zodream\Http\Http;

use Exception;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2017/1/3
 * Time: 13:46
 */
class JsSDK extends BaseWeChat {

    /**
     * @return \Zodream\Http\Http
     * @throws Exception
     */
    public function getTicket() {
        return $this->getBaseHttp()
            ->url('https://api.weixin.qq.com/cgi-bin/ticket/getticket', [
                '#access_token',
                'type' => 'jsapi'
            ]);
    }


    /**
     * @return mixed
     * @throws \Exception
     */
    public function ticket() {
        return static::getOrSetCache('jsApi_ticket'.$this->get('appid'),
            function (callable $next) {
            $args = $this->getTicket()->json();
            if (!is_array($args)) {
                throw new \Exception('HTTP ERROR!');
            }
            if (!array_key_exists('ticket', $args)) {
                throw new \Exception(isset($args['errmsg']) ? $args['errmsg'] : 'GET JS API TICKET ERROR!');
            }
            return call_user_func($next, $args['ticket'], $args['expires_in']);
        });
    }

    protected function getJsSign(array $data) {
        ksort($data);
        reset($data);
        $arg = [];
        foreach ($data as $key => $item) {
            if (Http::isEmpty($item) || $key == 'sign') {
                continue;
            }
            $arg[] = $key.'='.$item;
        }
        return sha1(implode('&', $arg));
    }

    /**
     * @param array $apiList
     * @return string
     * @throws \Exception
     */
    public function apiConfig($apiList = array()) {
        if (function_exists('view')) {
            view()->registerJsFile('http://res.wx.qq.com/open/js/jweixin-1.2.0.js');
        }
        $appId = $this->get('appid');
        $data = [
            'noncestr' => Str::random(),
            'jsapi_ticket' => $this->ticket(),
            'timestamp' => time(),
            'url' => url()->current()
        ];
        $sign = $this->getJsSign($data);
        $apiList = implode("','", $apiList);
        $debug = defined('DEBUG') && DEBUG ? 'true' : 'false';
        return <<<JS
wx.config({
    debug: {$debug}, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '{$appId}', // 必填，公众号的唯一标识
    timestamp: {$data['timestamp']}, // 必填，生成签名的时间戳
    nonceStr: '{$data['noncestr']}', // 必填，生成签名的随机串
    signature: '{$sign}',// 必填，签名，见附录1
    jsApiList: ['{$apiList}'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});
JS;
    }
}