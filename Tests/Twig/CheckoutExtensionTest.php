<?php

namespace Makuta\ClientBundle\Tests\Twig;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
/**
* 
*/
class CheckoutExtensionTest extends WebTestCase
{

	public function testGenerateCheckoutUrl()
	{
		$kernel=static::createKernel();
		$kernel->boot();
	    $container = $kernel->getContainer();
	    $m = $container->get('makuta_client.checkout_extension');
	    $g = new \Makuta\ClientBundle\Tests\Entity\Box("code","label");
	    $r = $m->generateCheckoutUrl($g);
	    $this->assertEquals($r,"/makuta/checkout/box_code");
	}
}