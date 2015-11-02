<?php 
namespace Makuta\ClientBundle\Model;

/**
* 
*/
interface EntityProvider
{

	public function getThisBuyer();

	public function getGoods($fullCode);
}