<?php
/**
 * @desc ConfigProvider.php 描述信息
 * @help https://segmentfault.com/a/1190000041562318
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/16 13:39
 */

declare(strict_types=1);

namespace Tinywan\Nacos\Provider;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use support\Log;
use Tinywan\Nacos\Cache\LocalConfigCache;

class ConfigProvider extends AbstractProvider
{
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
        try {
            $options[RequestOptions::QUERY] = [
                'dataId' => $dataId,
                'group' => $group,
                'tenant' => $tenant
            ];
            $token = $this->issueToken();
            $token && $options[RequestOptions::QUERY]['accessToken'] = $token;
            $response = $this->client()->request('GET', 'nacos/v1/cs/configs', $options);
            $config = $response->getBody()->getContents();
            $localConfig = LocalConfigCache::getSnapshot($dataId, $tenant);
            if (empty($localConfig) || (md5($config) != md5($localConfig))) {
                // 当应用程序去访问Nacos动态获取配置源之后，会缓存到本地内存以及磁盘中
                $cacheRes = LocalConfigCache::saveSnapshot($dataId, $tenant, $config);
                Log::info('[nacos] 动态获取配置缓存到本地内存以及磁盘中：'.$cacheRes);

                $responseArr = json_decode($config, true);
                $snapshotFile = config_path() . DIRECTORY_SEPARATOR . $dataId;
                $file = new \SplFileInfo($snapshotFile);
                if (!is_dir($file->getPath())) {
                    mkdir($file->getPath(), 0777, true);
                }
                file_put_contents($snapshotFile, "<?php\t return " . var_export($responseArr, true) . ";");
            }
        } catch (RequestException $exception) {
            $config = LocalConfigCache::getSnapshot($dataId, $tenant);
        }
        return $config;
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
        $configResponse = '';
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
    private function checkUpdateDataIds()
    {
    }
}
