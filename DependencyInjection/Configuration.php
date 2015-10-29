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
                        ->scalarNode('id')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('secret')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('method')->defaultValue('curl')->end()
                    ->end()
                ->end()
                ->arrayNode('tx_tracer')
                    ->canBeEnabled()
                    ->children()
                        ->arrayNode('buyers')
                            ->isRequired()
                            ->prototype('scalar')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                        ->arrayNode('goods')
                            ->isRequired()
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('class')->end()
                                    ->floatNode('default_price')->min(0)->end()
                                    ->scalarNode('default_currency')->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('checkout')
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('account')->end()
                                ->scalarNode('entity_provider')->isRequired()->cannotBeEmpty()->end()
                                ->arrayNode('callback_routes')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                    ->children()
                                        ->scalarNode('success_route')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('failure_route')->isRequired()->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
