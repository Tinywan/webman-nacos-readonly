<?php
/**
 * @desc ConcreteObserverB 订阅者 （subscribers）
 * @author Tinywan(ShaoBo Wan)
 * @email 756684177@qq.com
 * @date 2022/3/19 15:16
 */

declare(strict_types=1);

namespace Tinywan\Nacos\Observer;


use SplSubject;

class ConcreteObserverB implements \SplObserver
{
    /**
     * @param SplSubject $subject
     */
    public function update(SplSubject $subject)
    {
        if ($subject->state == 0 || $subject->state >= 2) {
            echo "ConcreteObserverB: Reacted to the event.\n";
        }
    }
}