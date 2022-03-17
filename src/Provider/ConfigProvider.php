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
    const WORD_SEPARATOR = "\x02";
    const LINE_SEPARATOR = "\x01";

    /**
     * @desc: 获取配置
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
     * @desc: 发布配置
     * @param string $dataId
     * @param string $group
     * @param string $content
     * @param string|null $type
     * @param string|null $tenant
     * @return bool|string
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function publish(string $dataId, string $group, string $content, ?string $type = null, ?string $tenant = null)
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
     * @desc: 监听配置（监听 Nacos 上的配置，以便实时感知配置变更。如果配置变更，则用获取配置接口获取配置的最新值，动态刷新本地缓存。）
     * @param string $dataId
     * @param string $group
     * @param string|null $tenant
     * @return bool|string
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function listen(string $dataId, string $group,string $contentMD5, ?string $tenant = null)
    {
        // 监听数据报文。格式为 dataId^2Group^2contentMD5^2tenant^1或者dataId^2Group^2contentMD5^1。
        $ListeningConfigs = $dataId. self::WORD_SEPARATOR .$group. self::WORD_SEPARATOR.$contentMD5. self::WORD_SEPARATOR.$tenant.self::LINE_SEPARATOR;
        return $this->request('POST', '/nacos/v1/cs/configs/listener', [
            RequestOptions::QUERY => [
                'Listening-Configs' => $ListeningConfigs,
            ],
        ]);
    }

    /**
     * @desc: 删除配置
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