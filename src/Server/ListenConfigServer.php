<?php
/**
 * @desc ListenConfig.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/17 14:23
 */

declare(strict_types=1);

namespace Tinywan\Nacos\Server;

use Tinywan\Nacos\Nacos;
use Workerman\Timer;

class ListenConfigServer
{
    public function onWorkerStart($worker)
    {
        if ($worker) {
            $config = config('plugin.tinywan.nacos.app.nacos');
            if ($config['is_config_listen']) {
                $listenList = $config['config_listen_list'];
                if ($listenList) {
                    $nacos = new Nacos();
                    Timer::add(10, function () use ($nacos, $listenList) {
                        foreach ($listenList as $listen) {
                            $content = $nacos->config->get($listen[0], $listen[1], $listen[2] ?? null);
                            if (false === $content) {
                                break;
                            }
                            $response = $nacos->config->listen($listen[0], $listen[1], md5($content), $listen[2] ?? null);
                            if (false === $response) {
                                break;
                            }
                            if ($response) {
                                $responseArr = json_decode($response, true);
                                $configFile = config_path() . DIRECTORY_SEPARATOR . $listen[0];
                                file_put_contents($configFile, "<?php\treturn " . var_export($responseArr, true) . ";");
                            }
                        }
                    });
                }
            }
        }
    }
}
