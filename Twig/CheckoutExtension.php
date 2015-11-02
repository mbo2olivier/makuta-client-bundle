<?php 

namespace Makuta\ClientBundle\twig;

use Makuta\ClientBundle\Model\Goods;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Makuta\ClientBundle\Model\TxTracer;

/**
* 
*/
class CheckoutExtension extends \Twig_Extension
{
	protected $router;

	protected $tracer;
	
	function __construct(Router $r,TxTracer $tracer)
	{
		$this->router = $r;
		$this->tracer = $tracer;
	}

	public function generateCheckoutUrl(Goods $g)
	{
		$code = $this->tracer->getGoodsCode($g);
		return $this->router->generate('makuta_client_checkout',
										array("code"=>$code),
										UrlGeneratorInterface::ABSOLUTE_PATH);
	}

	public function getFunctions(){
		return array(
          'makuta_checkout' => new \Twig_Function_Method($this, 'generateCheckoutUrl'),
		);
	}

	public function getName()
	{
		return "CheckoutExtension";
	}
}