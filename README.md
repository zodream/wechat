# wechat
微信公众号开发

- [微信公众号](#wechat)
    - [配置](#wechat-config)
    - [消息被动接收回复](#wechat-message)
    - [自定义菜单](#wechat-menu)
- [微信公众号第三方平台](#platform)
    - [事件推送](#platform-notify)
    - [管理](#platform-manage)
    - [授权登录](#platform-oauth)

<a name="wechat"></a>
## 微信公众号

<a name="wechat-config"></a>
### 配置
```PHP
'wechat' => [
    'appid' => '',
    'token' => '',
    'aes_key' => '',
    'secret' => '',
    'redirect_uri' => '',
    'platform' => [  // 第三方平台配置
        'component_appid' => '',
        'aes_key' => '',
        'token' => '',
        'component_appSecret' => ''
    ]
]
```

<a name="wechat-menu"></a>
### 消自定义菜单

获取菜单
```PHP
(new Menu())->getMenu();
```

设置菜单
```PHP
(new Menu())->create(
    MenuItem::menu(MenuItem::name('网址')->setUrl('http://zodream.cn'))
        ->setMenu(
            MenuItem::menu(MenuItem::name('点击')->setKey(1))
            ->setMenu(MenuItem::name('点击')->setKey(1))
        )
        ->setMenu(MenuItem::name('查看')->setMediaId('132123123'))
);
```

删除全部菜单
```PHP
(new Menu())->deleteMenu();
```

<a name="wechat-message"></a>
### 消息被动接收回复

```PHP
$message = new Message();
return $message->on([EventEnum::ScanSubscribe, EventEnum::Subscribe],
    function(Message $message, MessageResponse $response) {
    $response->setText('谢谢关注！');
})->on(EventEnum::Message, function(Message $message, MessageResponse $response) {
    $response->setText(sprintf('您的消息是: %s', $message->content));
})->on(EventEnum::UnSubscribe, function(Message $message, MessageResponse $response) {
    $response->setText('取消关注');
})->on(EventEnum::Click, function(Message $message, MessageResponse $response) {
    $response->setText(sprintf('您点击了 %s', $message->eventKey));
})->run()->sendContent();
```


<a name="platform"></a>
## 微信公众号第三方平台


<a name="platform-notify"></a>
### 事件推送

```PHP

```

<a name="platform-manage"></a>
### 管理

<a name="platform-oauth"></a>
### 授权登录
