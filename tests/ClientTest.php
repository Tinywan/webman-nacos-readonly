<?php
/**
 * @desc ClientTest.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/16 13:53
 */

declare(strict_types=1);


namespace Tinywan\Nacos\Tests;


use PHPUnit\Framework\TestCase;
use Tinywan\Nacos\Client;

class ClientTest extends TestCase
{
    // 获取配置
    public function getConfig()
    {
        $client = new Client('127.0.0.1',8848);
        $params = [
            'dataId' => 'redis',
            'group' => 'DEFAULT_GROUP',
        ];
        $res = $client->requestHandle('GET','/nacos/v1/cs/configs',$params);
        var_dump($res);
    }
}