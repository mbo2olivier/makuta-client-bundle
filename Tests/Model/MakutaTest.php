<?php

namespace Makuta\ClientBundle\Tests\Model;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
/**
* 
*/
class MakutaTest extends WebTestCase
{
	
	public function testOpenTransaction()
	{
		$kernel=static::createKernel();
		$kernel->boot();
	    $container = $kernel->getContainer();
	    $m = $container->get('makuta_client.makuta');
	    $r = $m->openTransaction(500,"USD",22);
	    $this->assertEquals($r["TOKEN"], "wesh12223654789azerty");
	}
}