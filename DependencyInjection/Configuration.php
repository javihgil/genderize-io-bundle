<?php

namespace Jhg\GenderizeIoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('genderize_io');

        $rootNode
            ->children()
                ->scalarNode('endpoint')->defaultValue('http://api.genderize.io/')->end()
                ->scalarNode('api_key')->end()
                ->booleanNode('cache')->defaultValue(false)->end()
                ->scalarNode('cache_handler')->defaultValue('genderize_io.cache_handler_doctrine')->end()
                ->integerNode('cache_expiry_time')->defaultValue(3600*24*90)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
