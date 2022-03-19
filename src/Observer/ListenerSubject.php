<?php
/**
 * @desc ListenerSubject 发布者 （publisher）
 * @author Tinywan(ShaoBo Wan)
 * @email 756684177@qq.com
 * @date 2022/3/19 15:03
 */

declare(strict_types=1);

namespace Tinywan\Nacos\Observer;


use SplObjectStorage;
use SplObserver;

class ListenerSubject implements \SplSubject
{
    /**
     * @var int 为了简单起见，主题的状态，对于
     * 所有订阅者，都存储在此变量中。
     */
    public int $state;

    /**
     * @var SplObjectStorage 订阅者列表。在现实生活中，名单订阅者可以存储更全面（按事件分类类型等）。
     */
    private SplObjectStorage $observers;

    /**
     * ListenerSubject constructor.
     * @param SplObjectStorage $objectStorage
     */
    public function __construct(SplObjectStorage $objectStorage)
    {
        $this->observers = $objectStorage;
    }

    /**
     * @desc 添加观察者
     * @param SplObserver $observer
     */
    public function attach(SplObserver $observer)
    {
        echo "Subject: Attached an observer.\n";
        $this->observers->attach($observer);
    }

    /**
     * @desc 剔除观察者
     * @param SplObserver $observer
     */
    public function detach(SplObserver $observer)
    {
        $this->observers->detach($observer);
        echo "Subject: Detached an observer.\n";
    }

    /**
     * @desc 通知观察者，在每个订阅者中触发更新
     */
    public function notify()
    {
        echo "Subject: Notifying observers...\n";
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}