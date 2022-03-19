<?php
/**
 * @desc ConcreteObserverA 订阅者 （subscribers）
 * @author Tinywan(ShaoBo Wan)
 * @email 756684177@qq.com
 * @date 2022/3/19 15:16
 */

declare(strict_types=1);

namespace Tinywan\Nacos\Observer;

use SplSubject;

class ConcreteObserverA implements \SplObserver
{
    /**
     * @param SplSubject $subject
     */
    public function update(SplSubject $subject)
    {
        if ($subject->state == 0 || $subject->state >= 2) {
            echo "ConcreteObserverA: Reacted to the event.\n";
        }
    }
}
