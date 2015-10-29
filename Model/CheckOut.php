<?php 
namespace Makuta\ClientBundle\Model;

use Makuta\ClientBundle\Entity\Trace;

/**
* 
*/
class CheckOut
{
	protected $tracer;
	protected $makuta;

	protected $provider;

	protected $account;

	protected $enabled;

	function __construct(Makuta $makuta,TxTracer $tracer,EntityProvider $provider,$account,$enabled)
	{
		$this->makuta = $makuta;
		$this->tracer = $tracer;
		$this->provider = $provider;
		$this->account = $account;
		$this->enabled = $enabled;
	}

	public function openTransaction($gname,$gcode)
	{
		$g = $this->provider->getGoods($gname,$gcode);
		$amount = $this->tracer->getPrice($g);
		$devise = $this->tracer->getDevise($g);
		$buyer = $this->provider->getThisBuyer();
		$response = $this->makuta->openTransaction($amount,$devise,$g->getCode(),$buyer->getFullName(),$this->account);
		if($response['ACK'] === "SUCCESS"){
			$t = new Trace();
			$t->setBuyerId($this->tracer->getBuyerId($buyer));
			$t->setGoodsCode($this->tracer->getGoodsCode($g));
			$t->setBuyerName($buyer->getFullName());
			$t->setGoodsLabel($g->getLabel());
			$t->setAmount($amount);
			$t->setCurrency($devise);
			$t->setDate(new \DateTime());
			$t->setToken($response["TOKEN"]);
			$t->setStatus($response["STATUS"]);
			$this->tracer->saveTrace($t);
			return $response["PAYMENT_PAGE"];
		}else{
			throw new \RuntimeException($response["MESSAGE"]);
		}
	}

	public function handleTransaction($token)
	{
		$t = $this->tracer->findTrace($token);
		if(is_null($t)){
			throw new \InvalidArgumentException("Can not find Trace for given token");	
		}
		$response = $this->makuta->getStatus($token);
		if($response["ACK"]=="SUCCESS"){
			$t->setTel($response["TEL"]);
			$t->setBuyerName($response["NOMS"]);
			$t->setStatus($response["STATUS"]);
			$t->setAmount($response["PAID"]);
			$this->tracer->saveTrace($t);
			return $t;
		}else{
			throw new \RuntimeException($response["MESSAGE"]);
		}
	}

	public function isEnable()
	{
		return $this->enabled;
	}
}