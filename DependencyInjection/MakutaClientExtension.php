<?php

namespace Makuta\ClientBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MakutaClientExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $configs = $configs[0];
        $container->setParameter('makuta_client.id',$configs['client_app']['id']);
        $container->setParameter('makuta_client.secret',$configs['client_app']['secret']);
        $container->setParameter('makuta_client.method',$configs['client_app']['method']);
        $this->loadTraceData($configs,$container);
        $this->loadCheckout($configs,$container);
    }

    public function loadCheckout(array $configs, ContainerBuilder $container)
    {
        $checkout_enabled = false;
        if(isset($configs['tx_tracer']['checkout'])) $checkout_enabled = true;
        if($checkout_enabled){
            $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('checkout.yml');
            $container->setAlias('makuta_client.checkout.entity_provider',$configs['tx_tracer']['checkout']['entity_provider']);
            $container->setParameter('makuta_client.routes.success_callback',$configs['tx_tracer']['checkout']['callback_routes']['success_route']);
            $container->setParameter('makuta_client.routes.failure_callback',$configs['tx_tracer']['checkout']['callback_routes']['failure_route']);
            $account = (isset($configs['tx_tracer']['checkout']['account']))? isset($configs['tx_tracer']['checkout']['account']): null; 
            $container->setParameter('makuta_client.checkout.account',$account);
        }

        $container->setParameter('makuta_client.checkout_enabled',$checkout_enabled);
    }

    private function loadTraceData(array $configs, ContainerBuilder $container)
    {
        $buyers = array();
        $goods = array();

        $tx_enabled = isset($configs['tx_tracer']);
        if($tx_enabled){
            //load buyers from config file
            $t = $configs['tx_tracer']['buyers'];
            foreach ($t as $key => $className) {
                $buyers[$className] = $key;
            }
            $t = $configs['tx_tracer']['goods'];
            foreach ($t as $key => $data) {
                $g = array();
                $g["name"]=$key;
                $g["entity"]=$data["class"];
                $g["price"]=$data["default_price"];
                $g["currency"]=$data["default_currency"];
                $goods[] = ($g);
            }
        }
        $container->setParameter('makuta_client.trace_enable',$tx_enabled);
        $container->setParameter('makuta_client.buyers',$buyers);
        $container->setParameter('makuta_client.goods',$goods);
    }

}
