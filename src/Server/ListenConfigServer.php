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
            Timer::add(1, function () use ($config) {
                $taskWork = new AsyncTcpConnection('text://127.0.0.1:9511');
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
                        $taskWork->onMessage = function (AsyncTcpConnection $connection, $result) {
                            // 有数据更新
                        };
                    }
                }
                // 执行异步连接操作。此方法会立刻返回。
                $taskWork->connect();
            });
        }
    }
}
