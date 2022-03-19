<?php
/**
 * @desc LocalConfigCache
 * @author Tinywan(ShaoBo Wan)
 * @email 756684177@qq.com
 * @date 2022/3/19 19:37
 */

declare(strict_types=1);

namespace Tinywan\Nacos\Cache;


class LocalConfigCache
{
    /**
     * @param $dataId
     * @param $tenant
     * @return false|string|null
     */
    public static function getSnapshot($dataId, $tenant)
    {
        $snapshotFile = self::getSnapshotFile($dataId, $tenant);
        if (!is_file($snapshotFile)) {
            return null;
        }
        return file_get_contents($snapshotFile);
    }

    /**
     * 保存快照
     * @param $dataId
     * @param $tenant
     * @param $config
     * @return bool|int
     */
    public static function saveSnapshot($dataId, $tenant, $config)
    {
        $snapshotFile = self::getSnapshotFile($dataId, $tenant);
        if (!$config) {
            return @unlink($snapshotFile);
        } else {
            $file = new \SplFileInfo($snapshotFile);
            if (!is_dir($file->getPath())) {
                mkdir($file->getPath(), 0777, true);
            }
            return file_put_contents($snapshotFile, $config);
        }
    }

    /**
     * 获取快照路径
     * @param $dataId
     * @param $tenant
     * @return string
     */
    private static function getSnapshotFile($dataId, $tenant): string
    {
        $snapshotFile = runtime_path() . DIRECTORY_SEPARATOR  . 'nacos' . DIRECTORY_SEPARATOR.'snapshot'.DIRECTORY_SEPARATOR;
        if ($tenant) {
            $snapshotFile .= $tenant . DIRECTORY_SEPARATOR;
        }
        return $snapshotFile . $dataId;
    }

    /**
     * 这个方法主要是向服务器端发起检查请求，判断自己本地的配置和服务端的配置是否一致。
     * (1) 首先从cacheDatas集合中找到isUseLocalConfigInfo为false的缓存
     * (2) 把需要检查的配置项，拼接成一个字符串,调用checkUpdateConfigStr进行验证
     */
    public static function checkUpdateDataIds($dataId, $tenant)
    {
        // 遍历本地缓存目录
    }

    public function checkUpdateConfigStr($dataId, $tenant)
    {
    }
}