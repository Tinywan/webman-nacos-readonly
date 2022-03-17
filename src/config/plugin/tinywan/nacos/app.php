<?php

return [
    'enable' => true,
    'nacos' => [
        'host' => 'dnmp-nacos',
        'port' => 8848,
        'username' => 'nacos',
        'password' => 'nacos',
        'long_pulling_timeout' => 30000, // 长轮训等待 30s，此处填写 30000
        'is_config_listen' => false, // 是否开启监听配置文件
        'listen_timer_interval' => 30, // 监听配置定时刷新时间隔
        'listen_text_address' => 'text://127.0.0.1:9511', // 自定义Text协议地址
        'config_listen_list' => [ // 监听配置文件列表 $dataId, $group, $tenant = null
            ['database.php','DEFAULT_GROUP','188e48a6-6c98-4563-a76e-e1c70a91e650']
        ],
    ]
];
