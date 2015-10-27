<?php

namespace Makuta\ClientBundle\Tests\Model;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
/**
* 
*/
class TxTracerTest extends WebTestCase
{
	
	public function testCheckPayment()
	{
		$kernel=static::createKernel();
		$kernel->boot();
	    $container = $kernel->getContainer();
	    $tx = $container->get('makuta_client.tx_tracer');
	    
	    $buyer = new \Makuta\ClientBundle\Tests\Entity\Client("011","olivier");
	    $goods = new \Makuta\ClientBundle\Tests\Entity\Box("001","la box");

	    $r = $tx->checkPayment($buyer,$goods);
	    $this->assertTrue(is_null($r));
	}

	public function testGetBuyerId()
	{
		$kernel=static::createKernel();
		$kernel->boot();
	    $container = $kernel->getContainer();
	    $tx = $container->get('makuta_client.tx_tracer');
	    
	    $buyer = new \Makuta\ClientBundle\Tests\Entity\Client("001","olivier");
	    $r = $tx->getBuyerId($buyer);
	    $this->assertEquals($r,"client_001");
	}

	public function testGetTrace()
	{
		$kernel=static::createKernel();
		$kernel->boot();
	    $container = $kernel->getContainer();
	    $tx = $container->get('makuta_client.tx_tracer');
	    $box = new \Makuta\ClientBundle\Tests\Entity\Box("001","la box");
	    $r = $tx->getTrace(array("from"=>new \DateTime("2014-12-01"),"to"=>new \DateTime("2015-12-30")));
	    /*$r = $r[0];*/
	    $this->assertEquals(1,count($r));
	}

	public function testGetDevise()
	{
		$kernel=static::createKernel();
		$kernel->boot();
	    $container = $kernel->getContainer();
	    $tx = $container->get('makuta_client.tx_tracer');
	    $box = new \Makuta\ClientBundle\Tests\Entity\Box("001","la box");
	    $r = $tx->getDevise($box);
	    $this->assertEquals("USD",$r);
	}

	public function testGetPrice()
	{
		$kernel=static::createKernel();
		$kernel->boot();
	    $container = $kernel->getContainer();
	    $tx = $container->get('makuta_client.tx_tracer');
	    $box = new \Makuta\ClientBundle\Tests\Entity\Box("001","la box");
	    $r = $tx->getPrice($box);
	    $this->assertEquals(100,$r);
	}
}