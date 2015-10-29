<?php

namespace Makuta\ClientBundle\Tests\Model;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
/**
* 
*/
class CheckOutTest extends WebTestCase
{
	
	/*public function testOpenTransaction()
	{
		$kernel=static::createKernel();
		$kernel->boot();
	    $container = $kernel->getContainer();
	    $m = $container->get('makuta_client.checkout');
	    $r = $m->openTransaction("moi","toi");
	    $this->assertEquals($r, "http://localhost/emakuta_py/auth.html");
	}*/

	public function testHandleTransaction()
	{
		$kernel=static::createKernel();
		$kernel->boot();
	    $container = $kernel->getContainer();
	    $m = $container->get('makuta_client.checkout');
	    $r = $m->handleTransaction("wesh12223654789azerty");
	    $this->assertEquals($r->getTel(),"243811805208");
	}
}