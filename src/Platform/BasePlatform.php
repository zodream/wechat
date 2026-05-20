<?php
declare(strict_types=1);
namespace Zodream\ThirdParty\WeChat\Platform;

use Zodream\Http\Http;
use Zodream\ThirdParty\WeChat\BaseWeChat;

abstract class BasePlatform extends BaseWeChat {
    protected string $configKey = 'wechat.platform';

    public function __construct(array $config = []) {
        parent::__construct($config);
        $this->set('component_appid', $this->get('component_appid'));
        $this->set('component_appsecret', $this->get('component_appsecret'));
    }

    /**
     * @param null $url
     * @return Http
     * @throws \Exception
     */
    public function getBaseHttp(mixed $url = null): Http {
        $token = (new Manage($this->get()))->token();
        return $this->getHttp()
            ->url($url, [
                '#component_access_token'
            ])
            ->parameters($this->merge([
                'component_access_token' => $token
            ]))
            ->encode(Http::JSON);
    }
}