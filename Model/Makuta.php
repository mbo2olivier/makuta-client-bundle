<?php

namespace Makuta\ClientBundle\Model;

/**
* 
*/
class Makuta
{
	private $makuta;

	function __construct($app_id,$secret,$method)
	{
		$method = strtolower($method);
		switch ($method) {
			case 'post':
				$m = new \Makuta\RequestMaker\Post($app_id,$secret);
				break;
			
			default:
				$m = new \Makuta\RequestMaker\Curl($app_id,$secret);
				break;
		}
		$this->makuta = new \Makuta\Makuta($app_id,$secret,$m);
	}

	public function openTransaction($montant, $devise, $code, $buyer = null, $account = null)
	{
		return $this->makuta->openTransaction($montant, $devise, $code, $buyer, $account);
	}

	public function getStatus($token)
	{
		return $this->makuta->getStatus($token);
	}
}