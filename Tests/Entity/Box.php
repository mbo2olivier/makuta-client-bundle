<?php 
namespace Makuta\ClientBundle\Tests\Entity;

/**
* 
*/
class Box implements \Makuta\ClientBundle\Model\Goods
{
	public $code;

	public $label;

	function __construct($a,$b) {
		$this->code = $a;
		$this->label = $b;
	}

	public function getCode(){
		return $this->code;
	}

	public function getLabel(){
		return $this->label;
	}

	public function getPrice(){
		return null;
	}

	public function getDevise(){
		return null;
	}
}