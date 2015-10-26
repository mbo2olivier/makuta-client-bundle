<?php

namespace Makuta\ClientBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('makuta_client');

        $rootNode
            ->children()
                ->arrayNode('client_app')
                    ->children()
                        ->scalarNode('id')->end()
                        ->scalarNode('secret')->end()
                        ->scalarNode('method')->defaultValue('curl')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
