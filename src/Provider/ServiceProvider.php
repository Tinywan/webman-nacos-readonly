<?php
/**
 * @desc ServiceProvider.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/16 15:46
 */

declare(strict_types=1);

namespace Tinywan\Nacos\Provider;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class ServiceProvider extends AbstractProvider
{
    /**
     * @param string $serviceName
     * @param array $optional = [
     *     'groupName' => '',
     *     'namespaceId' => '',
     *     'protectThreshold' => 0.99,
     *     'metadata' => '',
     *     'selector' => '', // json字符串
     * ]
     * @return bool|string
     * @throws GuzzleException
     */
    public function create(string $serviceName, array $optional = [])
    {
        return $this->request('POST', 'nacos/v1/ns/service', [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
            ])),
        ]);
    }

    /**
     * @desc: 方法描述
     * @param string $serviceName
     * @param string|null $groupName
     * @param string|null $namespaceId
     * @return bool|string
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function delete(string $serviceName, ?string $groupName = null, ?string $namespaceId = null)
    {
        return $this->request('DELETE', 'nacos/v1/ns/service', [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $serviceName,
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
            ]),
        ]);
    }

    /**
     * @param string $serviceName
     * @param array $optional = [
     *     'groupName' => '',
     *     'namespaceId' => '',
     *     'protectThreshold' => 0.99,
     *     'metadata' => '',
     *     'selector' => '', // json字符串
     * ]
     * @return bool|string
     * @throws GuzzleException
     */
    public function update(string $serviceName, array $optional = [])
    {
        return $this->request('PUT', 'nacos/v1/ns/service', [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
            ])),
        ]);
    }

    /**
     * @desc: 方法描述
     * @param string $serviceName
     * @param string|null $groupName
     * @param string|null $namespaceId
     * @return bool|string
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function detail(string $serviceName, ?string $groupName = null, ?string $namespaceId = null)
    {
        return $this->request('GET', 'nacos/v1/ns/service', [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $serviceName,
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
            ]),
        ]);
    }

    /**
     * @desc: 方法描述
     * @param int $pageNo
     * @param int $pageSize
     * @param string|null $groupName
     * @param string|null $namespaceId
     * @return bool|string
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function list(int $pageNo, int $pageSize, ?string $groupName = null, ?string $namespaceId = null)
    {
        return $this->request('GET', 'nacos/v1/ns/service/list', [
            RequestOptions::QUERY => $this->filter([
                'pageNo' => $pageNo,
                'pageSize' => $pageSize,
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
            ]),
        ]);
    }
}
