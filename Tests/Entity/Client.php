<?php 
namespace Makuta\ClientBundle\Tests\Entity;

/**
* 
*/
class Client implements \Makuta\ClientBundle\Model\Buyer
{
	public $id;

	public $name;

	function __construct($id,$name) {
		$this->id = $id;
		$this->name = $name;
	}

	public function getIdentifiant(){
		return $this->id;
	}

	public function getFullName(){
		return $this->name;
	}
	
}