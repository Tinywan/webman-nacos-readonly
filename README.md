# webman nacos plugin

[![Latest Stable Version](http://poser.pugx.org/tinywan/nacos/v)](https://packagist.org/packages/tinywan/nacos) 
[![Total Downloads](http://poser.pugx.org/tinywan/nacos/downloads)](https://packagist.org/packages/tinywan/nacos) 
[![Latest Unstable Version](http://poser.pugx.org/tinywan/nacos/v/unstable)](https://packagist.org/packages/tinywan/nacos) 
[![License](http://poser.pugx.org/tinywan/nacos/license)](https://packagist.org/packages/tinywan/nacos) 
[![PHP Version Require](http://poser.pugx.org/tinywan/nacos/require/php)](https://packagist.org/packages/tinywan/nacos)

## 安装

```php
composer require tinywan/nacos
```

## 基本用法

### 配置

#### 获取配置
```php
use Tinywan\Nacos\Nacos;

$nacos = new Nacos();
$response = $nacos->config->get('database', 'DEFAULT_GROUP');
if (false === $response) {
    var_dump($nacos->config->getMessage());
}
```

#### 发布配置

```php
use Tinywan\Nacos\Nacos;

$nacos = new Nacos();
$content = [
    'listen' => 'http://0.0.0.0:8888',
    'transport' => 'tcp',
    'context' => [],
    'name' => 'webman-admin',
];
$response = $nacos->config->set('aliyun', 'DEFAULT_GROUP',json_encode($content));
if (false === $response) {
    var_dump($nacos->config->getMessage());
}
```

### 实例

#### 注册实例
```php
use Tinywan\Nacos\Nacos;

$nacos = new Nacos();
$optional = [
    'groupName' => 'webman-group',
    'clusterName' => 'webman',
    'namespaceId' => 'webman',
    'weight' => 99.0,
    'metadata' => '',
    'enabled' => true,
    'ephemeral' => false
];
$response = $nacos->instance->register('127.0.0.1', 8888,'webman-admin',$optional);
```

## OpenAPI

- 配置管理
  - [获取配置](https://nacos.io/zh-cn/docs/open-api.html#1.1)
  - [监听配置](https://nacos.io/zh-cn/docs/open-api.html#1.2)
  - [发布配置](https://nacos.io/zh-cn/docs/open-api.html#1.3)
  - [删除配置](https://nacos.io/zh-cn/docs/open-api.html#1.4)
  - [查询历史版本](https://nacos.io/zh-cn/docs/open-api.html#1.5)
  - [查询历史版本详情](https://nacos.io/zh-cn/docs/open-api.html#1.6)
  - [查询配置上一版本信息](https://nacos.io/zh-cn/docs/open-api.html#1.7)
- 服务发现
  - [注册实例](https://nacos.io/zh-cn/docs/open-api.html#2.1)
  - [注销实例](https://nacos.io/zh-cn/docs/open-api.html#2.2)
  - [修改实例](https://nacos.io/zh-cn/docs/open-api.html#2.3)
  - [查询实例列表](https://nacos.io/zh-cn/docs/open-api.html#2.4)
  - [查询实例详情](https://nacos.io/zh-cn/docs/open-api.html#2.5)
  - [发送实例心跳](https://nacos.io/zh-cn/docs/open-api.html#2.6)
  - [创建服务](https://nacos.io/zh-cn/docs/open-api.html#2.7)
  - [删除服务](https://nacos.io/zh-cn/docs/open-api.html#2.8)
  - [修改服务](https://nacos.io/zh-cn/docs/open-api.html#2.9)
  - [查询服务](https://nacos.io/zh-cn/docs/open-api.html#2.10)
  - [查询服务列表](https://nacos.io/zh-cn/docs/open-api.html#2.11)
  - [查询系统开关](https://nacos.io/zh-cn/docs/open-api.html#2.12)
  - [修改系统开关](https://nacos.io/zh-cn/docs/open-api.html#2.13)
  - [查看系统当前数据指标](https://nacos.io/zh-cn/docs/open-api.html#2.14)
  - [查看当前集群Server列表](https://nacos.io/zh-cn/docs/open-api.html#2.15)
  - [查看当前集群leader](https://nacos.io/zh-cn/docs/open-api.html#2.16)
  - [更新实例的健康状态](https://nacos.io/zh-cn/docs/open-api.html#2.17)
  - [批量更新实例元数据(Beta)](https://nacos.io/zh-cn/docs/open-api.html#2.18)
  - [批量删除实例元数据(Beta)](https://nacos.io/zh-cn/docs/open-api.html#2.19)
- 命名空间
  - [查询命名空间列表](https://nacos.io/zh-cn/docs/open-api.html#3.1)
  - [创建命名空间](https://nacos.io/zh-cn/docs/open-api.html#3.2)
  - [修改命名空间](https://nacos.io/zh-cn/docs/open-api.html#3.3)
  - [删除命名空间](https://nacos.io/zh-cn/docs/open-api.html#3.4)

## Other

- `__toString()` 方法用于一个类被当成字符串时应怎样回应。例如 echo $obj; 应该显示些什么。
  > 警告：在 PHP 7.4.0 之前不能在 __toString() 方法中抛出异常。这么做会导致致命错误。
- `__invoke()` 当尝试以调用函数的方式调用一个对象时，__invoke() 方法会被自动调用。
- `__callStatic()` 在静态上下文中调用一个不可访问方法时，__callStatic() 会被调用。
