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
    public const WORD_SEPARATOR = "\x02";
    public const LINE_SEPARATOR = "\x01";

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
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function listen(string $dataId, string $group, string $contentMD5, ?string $tenant = null)
    {
        // 监听数据报文。格式为 dataId^2Group^2contentMD5^2tenant^1或者dataId^2Group^2contentMD5^1。
        $ListeningConfigs = $dataId. self::WORD_SEPARATOR .$group. self::WORD_SEPARATOR.$contentMD5. self::WORD_SEPARATOR.$tenant.self::LINE_SEPARATOR;
        $responseStr = $this->request('POST', '/nacos/v1/cs/configs/listener', [
            RequestOptions::QUERY => [
                'Listening-Configs' => $ListeningConfigs,
            ],
            RequestOptions::HEADERS => [
                'Long-Pulling-Timeout' => config('plugin.tinywan.nacos.app.nacos.long_pulling_timeout'), // 长轮训等待 30s，此处填写 30000。
            ],
        ]);
        if (!$responseStr) {
            return [];
        }

        // $responseStr = string(28) "database%02DEFAULT_GROUP%01"
        $lines = explode(self::LINE_SEPARATOR, urldecode($responseStr));
        // 遍历发生了变更的配置项
        foreach ($lines as $line) {
            if (!empty($line)) {
                $parts = explode(self::WORD_SEPARATOR, $line);
                $dataId = $parts[0];
                $group = $parts[1];
                $tenant = null;
                if (count($parts) === 3) {
                    $tenant = $parts[2];
                }
                // 逐项根据这些配置项获取配置信息
                $configResponse = $this->nacos->config->get($dataId, $group, $tenant);
                // 把配置信息保存到CacheData中
                // $changedContent = $this->nacos->config->get($dataId, $group, $tenant);
            }
        }
        return $configResponse;
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

    /**
     * 这个方法主要是向服务器端发起检查请求，判断自己本地的配置和服务端的配置是否一致。
     * （1）首先从cacheDatas集合中找到isUseLocalConfigInfo为false的缓存
     * （2）把需要检查的配置项，拼接成一个字符串,调用checkUpdateConfigStr进行验证
     */
    private function checkUpdateDataIds(){

    }
}
