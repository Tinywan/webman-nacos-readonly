<?php
/**
 * @desc Listener
 * @author Tinywan(ShaoBo Wan)
 * @email 756684177@qq.com
 * @date 2022/3/19 12:19
 */

declare(strict_types=1);

namespace Tinywan\Nacos\Listener;


class Listener
{
    /**
     * 观察者数组
     * @var array
     */
    protected static array $observers = [];

    /**
     * 增加观察者
     * @param callable $observer
     */
    public static function add(Callable $observer)
    {
        static::$observers[] = $observer;
    }

    /**
     * 删除观察者
     * @param callable $observer
     * @return bool
     */
    public static function delete(Callable $observer)
    {
        foreach (static::$observers as $key => $obs) {
            if ($obs == $observer) {
                unset(static::$observers[$key]);
                return true;
            }
        }
        return false;
    }

    /**
     * 通知观察者
     */
    public static function notify()
    {
        foreach (static::$observers as $observer) {
            call_user_func_array($observer, func_get_args());
        }
    }

    /**
     * 获取观察者
     * @return array
     */
    public static function getObservers(): array
    {
        return static::$observers;
    }
}