<?php
/**
 * @desc ListenAsyncTcp.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/17 20:44
 */

declare(strict_types=1);


namespace Tinywan\Nacos\Protocol;

use Tinywan\Nacos\Nacos;
use Workerman\Connection\TcpConnection;

class NacosListenTextProtocol
{
    /**
     * @desc: 方法描述
     * @param TcpConnection $connection
     * @param $data
     * @author Tinywan(ShaoBo Wan)
     */
    public function onMessage(TcpConnection $connection, $data)
    {
        $originArr = json_decode($data,true);
        if ($originArr['cmd'] == 'listen-config') {
            $dataId = $originArr['data']['dataId'];
            // echo ' [x] 异步任务 ..........  '. date('Y-m-d H:i:s').'，$dataId = ',$dataId, "\n";
            $group = $originArr['data']['group'];
            $tenant = $originArr['data']['tenant'];
            $nacos = new Nacos();
            $content = $nacos->config->get($dataId, $group, $tenant);
            if (false === $content) {
                var_dump( $nacos->config->getMessage());
            }
            // 阻塞数秒
            $response = $nacos->config->listen($dataId, $group,md5($content),$tenant);
            if (false === $response) {
                var_dump($nacos->config->getMessage());
            }
            if ($response) {
                $responseArr = json_decode($response, true);
                $configFile = config_path() . DIRECTORY_SEPARATOR . $dataId;
                file_put_contents($configFile, "<?php\treturn " . var_export($responseArr, true) . ";");
                $connection->send($response);
            }
        }
    }
}