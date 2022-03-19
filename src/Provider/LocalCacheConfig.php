<?php
/**
 * @desc ConfigProvider.php 会缓存到本地内存以及磁盘中
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/16 13:39
 */

declare(strict_types=1);

namespace Tinywan\Nacos\Provider;

class LocalCacheConfig extends AbstractProvider
{
    /**
     * @param $dataId
     * @param $group
     * @param $tenant
     * @return false|string|null
     */
    public static function getSnapshot($dataId, $group, $tenant)
    {
        $snapshotFile = self::getSnapshotFile($dataId, $group, $tenant);
        if (!is_file($snapshotFile)) {
            return null;
        }
        return file_get_contents($snapshotFile);
    }

    /**
     * 保存快照
     * @param $dataId
     * @param $group
     * @param $tenant
     * @param $config
     * @return bool|int
     */
    public static function saveSnapshot($dataId, $group, $tenant, $config)
    {
        $snapshotFile = self::getSnapshotFile($dataId, $group, $tenant);
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
     * @param $group
     * @param $tenant
     * @return string
     */
    private static function getSnapshotFile($dataId, $group, $tenant): string
    {
        $snapshotFile = runtime_path() . DIRECTORY_SEPARATOR  . 'nacos' . DIRECTORY_SEPARATOR;
        if ($tenant) {
            $snapshotFile .= "snapshot-tenant" . DIRECTORY_SEPARATOR . $tenant . DIRECTORY_SEPARATOR;
        } else {
            $snapshotFile .= "snapshot" . DIRECTORY_SEPARATOR;
        }
        return $snapshotFile . $dataId;
    }
}
