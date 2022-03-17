<?php
/**
 * @desc process.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/17 14:21
 */

use Tinywan\Nacos\Server\ListenConfigServer;
use \Tinywan\Nacos\Protocol\NacosListenTextProtocol;

return [
    // 自定义Text协议
    'listen.text.protocol' => [
        'handler'=> NacosListenTextProtocol::class,
        'listen' => 'text://0.0.0.0:9511',
        'count'  => 10, // 根据配置文件调整
    ],
    'listen.config.server' => [
        'handler'     => ListenConfigServer::class,
        'count'       => 1, // 必须是1
    ]
];
