<?php

namespace Jhg\GenderizeIoBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class GenderizeIoExtension
 * 
 * @package Jhg\GenderizeIoBundle\DependencyInjection
 */
class GenderizeIoExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // load services
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $config = array_merge(['api_key' => '', 'endpoint' => 'http://api.genderize.io/'], $config);

       
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->getDefinition('genderize_io.client')->addArgument($config['endpoint']);

        if(!empty($config['api_key']))
            $container->getDefinition('genderize_io.genderizer')->addMethodCall('setApiKey',$config['api_key']);

    }
}