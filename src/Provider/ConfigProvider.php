<?php
/**
 * @desc ConfigProvider.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/16 13:39
 */

declare(strict_types=1);


namespace Tinywan\Nacos\Provider;


use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class ConfigProvider extends AbstractProvider
{
    /**
     * @desc: 方法描述
     * @param string $dataId
     * @param string $group
     * @param string|null $tenant
     * @return bool|string
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function get(string $dataId, string $group, ?string $tenant = null)
    {
        return $this->request('GET', 'nacos/v1/cs/configs', [
            RequestOptions::QUERY => $this->filter([
                'dataId' => $dataId,
                'group' => $group,
                'tenant' => $tenant
            ]),
        ]);
    }

    /**
     * @desc: 方法描述
     * @param string $dataId
     * @param string $group
     * @param string $content
     * @param string|null $type
     * @param string|null $tenant
     * @return bool|string
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function set(string $dataId, string $group, string $content, ?string $type = null, ?string $tenant = null)
    {
        return $this->request('POST', 'nacos/v1/cs/configs', [
            RequestOptions::FORM_PARAMS => $this->filter([
                'dataId' => $dataId,
                'group' => $group,
                'tenant' => $tenant,
                'type' => $type,
                'content' => $content
            ]),
        ]);
    }

    /**
     * @desc: 方法描述
     * @param string $dataId
     * @param string $group
     * @param string|null $tenant
     * @return bool|string
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function delete(string $dataId, string $group, ?string $tenant = null)
    {
        return $this->request('DELETE', 'nacos/v1/cs/configs', [
            RequestOptions::QUERY => $this->filter([
                'dataId' => $dataId,
                'group' => $group,
                'tenant' => $tenant
            ]),
        ]);
    }
}