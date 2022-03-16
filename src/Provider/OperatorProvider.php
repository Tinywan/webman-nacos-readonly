<?php
/**
 * @desc OperatorProvider.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/16 15:43
 */

declare(strict_types=1);


namespace Tinywan\Nacos\Provider;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class OperatorProvider extends AbstractProvider
{
    /**
     * @desc: 方法描述
     * @return ResponseInterface
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function getSwitches(): ResponseInterface
    {
        return $this->request('GET', 'nacos/v1/ns/operator/switches');
    }

    /**
     * @desc: 方法描述
     * @param string $entry
     * @param string $value
     * @param bool|null $debug
     * @return ResponseInterface
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function updateSwitches(string $entry, string $value, ?bool $debug = null): ResponseInterface
    {
        return $this->request('PUT', 'nacos/v1/ns/operator/switches', [
            RequestOptions::QUERY => $this->filter([
                'entry' => $entry,
                'value' => $value,
                'debug' => $debug,
            ]),
        ]);
    }

    /**
     * @desc: 方法描述
     * @return ResponseInterface
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function getMetrics(): ResponseInterface
    {
        return $this->request('GET', 'nacos/v1/ns/operator/metrics');
    }

    /**
     * @desc: 方法描述
     * @param bool|null $healthy
     * @return ResponseInterface
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function getServers(?bool $healthy = null): ResponseInterface
    {
        return $this->request('GET', 'nacos/v1/ns/operator/servers', [
            RequestOptions::QUERY => $this->filter([
                'healthy' => $healthy,
            ]),
        ]);
    }

    /**
     * @desc: 方法描述
     * @return ResponseInterface
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function getLeader(): ResponseInterface
    {
        return $this->request('GET', 'nacos/v1/ns/raft/leader');
    }
}