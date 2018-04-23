<?php

namespace Zqhong\FastdAliyunMNS;

use FastD\Config\Config;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;

/**
 * 阿里云消息服务 - 服务注册
 *
 * @package Zqhong\FastdAliyunMNS
 */
class AliyunMNSProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        /** @var Config $config */
        $config = $container->get('config');
        $mnsConfig = $config->get('aliyun.mns');

        $container['aliyun_mns'] = new Client(
            $mnsConfig['endpoint'],
            $mnsConfig['access_id'],
            $mnsConfig['access_key']
        );
    }
}