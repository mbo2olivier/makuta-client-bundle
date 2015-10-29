<?php 

namespace Makuta\ClientBundle\twig;

use Makuta\ClientBundle\Model\Goods;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
* 
*/
class CheckoutExtension extends \Twig_Extension
{
	protected $router;
	
	function __construct(Router $r)
	{
		$this->router = $r;
	}

	public function generateCheckoutUrl(Goods $g)
	{
		return $this->router->generate('makuta_client_checkout',
										array("gname"=>$g->getName(),
											  "gcode"=>$g->getCode()),
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