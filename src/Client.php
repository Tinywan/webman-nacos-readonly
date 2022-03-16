<?php
/**
 * @desc Client.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/16 13:42
 */

declare(strict_types=1);


namespace Tinywan\Nacos;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Tinywan\Nacos\Exception\NacosConfigException;

class Client
{
    /**
     * @var string
     */
    protected string $host;

    /**
     * @var int
     */
    protected int $port;

    /**
     * @var string
     */
    protected string $namespace;

    /**
     * @var int
     */
    protected int $timeout = 10;

    /**
     * __construct function
     *
     * @param string $host
     * @param int $port
     */
    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @desc: 方法描述
     * @param string $method
     * @param string $uri
     * @param array $body
     * @param array $header
     * @return string
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function requestHandle(string $method, string $uri, array $body = [], array $header = [])
    {
        $client = new GuzzleHttpClient([
            'base_uri' => 'http://127.0.0.1:8848'
        ]);
        try {
            $options = ['query' => $body];
            $resp = $client->request($method, $uri, $options);
        } catch (RequestException $e) {
            throw new NacosConfigException($e->getMessage());
        }

        if (404 === $resp->getStatusCode()) {
            throw new NacosConfigException($resp->getReasonPhrase());
        }
        $jsonStr = $resp->getBody()->getContents();
        return $jsonStr;
    }
}