<?php 
namespace Makuta\ClientBundle\Tests\Service;

use Makuta\ClientBundle\Tests\Entity\Client;
use Makuta\ClientBundle\Tests\Entity\Box;
use Makuta\ClientBundle\Model\EntityProvider;
/**
* 
*/
class Provider implements EntityProvider
{

	public function getThisBuyer(){
		return new Client("002","iNeph");
	}

	public function getGoods($name,$code){
		return new Box("002","ceci est une box");
	}
}