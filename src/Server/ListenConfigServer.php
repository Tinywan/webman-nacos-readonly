<?php
/**
 * @desc ListenConfig.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/17 14:23
 */

declare(strict_types=1);

namespace Tinywan\Nacos\Server;

use Workerman\Connection\AsyncTcpConnection;
use Workerman\Timer;
use Workerman\Worker;

class ListenConfigServer
{
    /**
     * @desc: 方法描述
     * @param Worker $worker
     */
    public function onWorkerStart(Worker $worker)
    {
        $config = config('plugin.tinywan.nacos.app.nacos');
        if ($config['is_config_listen']) {
            Timer::add($config['listen_timer_interval'], function () use ($config) {
                $taskWork = new AsyncTcpConnection($config['listen_text_address']);
                // 这里应该遍历缓存目录。而不是读取配置文件
                $listenList = $config['config_listen_list'];
                if ($listenList) {
                    foreach ($listenList as $listen) {
                        $taskWork->send(json_encode([
                            'cmd' => 'listen-config',
                            'data' => [
                                'dataId' => $listen[0],
                                'group' => $listen[1],
                                'tenant' => $listen[2]
                            ]
                        ]));
                        // 异步获得结果
                        $taskWork->onMessage = function (AsyncTcpConnection $connection, $result) {
                            // echo ' [x] 获取异步配置信息 ...  '.$result, "\n";
                            // 关闭异步连接
                            $connection->close();
                        };
                    }
                }
                $taskWork->connect();
            });
        }
    }
}
