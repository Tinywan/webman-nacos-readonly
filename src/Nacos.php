<?php
/**
 * @desc Nacos.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/16 15:39
 */

declare(strict_types=1);

namespace Tinywan\Nacos;

use Tinywan\Nacos\Provider\AuthProvider;
use Tinywan\Nacos\Provider\ConfigProvider;
use Tinywan\Nacos\Provider\InstanceProvider;
use Tinywan\Nacos\Provider\OperatorProvider;
use Tinywan\Nacos\Provider\ServiceProvider;

class Nacos
{
    /**
     * @var array|string[]
     */
    protected array $alias = [
        'auth' => AuthProvider::class,
        'config' => ConfigProvider::class,
        'instance' => InstanceProvider::class,
        'operator' => OperatorProvider::class,
        'service' => ServiceProvider::class
    ];

    /**
     * @var array
     */
    protected array $providers = [];

    /**
     * Nacos constructor.
     */
    public function __construct()
    {
    }

    /**
     * @desc: 方法描述
     * @param $name
     * @return mixed
     * @author Tinywan(ShaoBo Wan)
     */
    public function __get($name)
    {
        if (!isset($name) || !isset($this->alias[$name])) {
            throw new \InvalidArgumentException("{$name} is invalid.");
        }

        if (isset($this->providers[$name])) {
            return $this->providers[$name];
        }

        $class = $this->alias[$name];
        return $this->providers[$name] = new $class($this);
    }
}
