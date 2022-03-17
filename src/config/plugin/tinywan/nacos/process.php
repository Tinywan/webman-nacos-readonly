<?php
/**
 * @desc process.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/17 14:21
 */

use Tinywan\Nacos\Server\ListenConfigServer;

return [
    'listen.config' => [
        'handler'     => ListenConfigServer::class,
        'count'       => 1, // 必须是1
    ]
];
