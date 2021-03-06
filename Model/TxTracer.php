<?php 
namespace Makuta\ClientBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Makuta\ClientBundle\Entity\Trace;
/**
* 
*/
class TxTracer
{

    const TX_STATUS_OPENED=0;
    const TX_STATUS_WAITING_PAYMENT=1;
    const TX_STATUS_CANCELLED=2;
    const TX_STATUS_INVALID=3;
    const TX_STATUS_TERMINATED=4;
    const TX_STATUS_ANONYMOUSLY_CANCELLED=5;
    const TX_STATUS_CONFLICT=6;

	protected $em;

	protected $buyers;

	protected $goods;
	
	function __construct(EntityManager $manager,$buyers,$goods)
	{
		$this->em = $manager;
		$this->buyers = $buyers;
		$this->goods = array();
		foreach ($goods as $a) {
			$this->goods[] = ((object)$a);
		}
	}

	public function saveTrace(Trace $trace)
	{
		$this->em->persist($trace);
		$this->em->flush();
	}

	public function checkPayment(Buyer $b,Goods $g)
	{
		$qb = $this->em->createQueryBuilder();
		$qb->select('t')
		   ->from("MakutaClientBundle:Trace","t");
		$bid = $this->getBuyerId($b);
		$qb->andWhere('t.buyerId = :bid')
		   ->setParameter("bid",$bid);
		$gc = $this->getGoodsCode($g);
		$qb->andWhere('t.goodsCode = :gc')
		   ->setParameter("gc",$gc);
        $qb->andWhere('t.status = :st')
           ->setParameter("st",self::TX_STATUS_TERMINATED);

		return $qb->getQuery()->getOneOrNullResult();
	}

    public function traceAchived(Buyer $b,Goods $g)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('t')
            ->from("MakutaClientBundle:Trace","t");
        $bid = $this->getBuyerId($b);
        $qb->andWhere('t.buyerId = :bid')
            ->setParameter("bid",$bid);
        $gc = $this->getGoodsCode($g);
        $qb->andWhere('t.goodsCode = :gc')
            ->setParameter("gc",$gc);
        $qb->andWhere($qb->expr()->in('t.status',':tab'))
            ->setParameter("tab",array(self::TX_STATUS_TERMINATED,self::TX_STATUS_INVALID,self::TX_STATUS_CANCELLED,self::TX_STATUS_ANONYMOUSLY_CANCELLED));
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function traceUnclosed(Buyer $b,Goods $g)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('t')
            ->from("MakutaClientBundle:Trace","t");
        $bid = $this->getBuyerId($b);
        $qb->andWhere('t.buyerId = :bid')
            ->setParameter("bid",$bid);
        $gc = $this->getGoodsCode($g);
        $qb->andWhere('t.goodsCode = :gc')
            ->setParameter("gc",$gc);
        $qb->andWhere($qb->expr()->in('t.status',':tab'))
            ->setParameter("tab",array(self::TX_STATUS_OPENED,self::TX_STATUS_CONFLICT,self::TX_STATUS_WAITING_PAYMENT));
        return $qb->getQuery()->getOneOrNullResult();
    }

	public function findTrace($id)
	{
		$qb = $this->em->createQueryBuilder();
		$qb->select('t')
		   ->from("MakutaClientBundle:Trace","t")
		   ->where('t.id = :id')
		   ->setParameter("id",$id);
		return $qb->getQuery()->getOneOrNullResult();
	}

	public function findTraceByToken($token)
	{
		$qb = $this->em->createQueryBuilder();
		$qb->select('t')
		   ->from("MakutaClientBundle:Trace","t")
		   ->where('t.token = :token')
		   ->setParameter("token",$token);
		return $qb->getQuery()->getOneOrNullResult();
	}

	public function getTrace($criteria = array())
	{
		$qb = $this->em->createQueryBuilder();
		$qb->select('t')
		   ->from("MakutaClientBundle:Trace","t")
		   ->orderBy("t.date","DESC");
		if(isset($criteria['buyer'])){
			$b = $this->getBuyerId($criteria['buyer']);
			$qb->andWhere('t.buyerId = :bid')
			   ->setParameter("bid",$b);
		}
		if(isset($criteria['by_buyers'])){
			$b = $criteria['by_buyers'];
			$qb->andWhere('t.buyerId LIKE :bname')
			   ->setParameter("bname",$b."\_%");
		}
		if(isset($criteria['goods'])){
			$g = $this->getGoodsCode($criteria['goods']);
			$qb->andWhere('t.goodsCode = :gc')
			   ->setParameter("gc",$g);
		}
		if(isset($criteria['goods_sort'])){
			$qb->addOrderBy('t.goodsLabel', $criteria['goods_sort']);
		}
		if(isset($criteria['buyers_sort'])){
			$qb->addOrderBy('t.buyerName', $criteria['buyers_sort']);
		}
		if(isset($criteria['by_goods'])){
			$g = $criteria['by_goods'];
			$qb->andWhere('t.goodsCode = :gname')
			   ->setParameter("gname",$g."\_%");
		}
		if(isset($criteria["from"])){
			$qb->andWhere('t.date >= :from')
			   ->setParameter("from",$criteria["from"]);
		}
		if(isset($criteria["to"])){
			$qb->andWhere('t.date < :to')
			   ->setParameter("to",$criteria["to"]);
		}
		$st = (isset($criteria["status"]))? $criteria["status"]: 4;
		$qb->andWhere('t.status = :st')
		   ->setParameter("st",$st);
		$query= $qb->getQuery();

		if(isset($criteria['limit'])){
			$first = $criteria['limit']['first'];
			$max = $criteria['limit']['max'];
			$query->setFirstResult($first)
				  ->setMaxResults($max);
			return new Paginator($query);
		}else{
			return $query->getResult();
		}
	}

	public function getBuyerId(Buyer $b)
	{
		foreach ($this->buyers as $classe => $name) {
			if($b instanceof $classe){
				return $name."_".$b->getIdentifiant();
			}
		}
		return null;
	}

	public function getGoodsCode(Goods $g)
	{
		foreach ($this->goods as $goods) {
			$classe = $goods->entity;
			if($g instanceof $classe){
				return ($goods->name)."_".$g->getCode();
			}
		}
		return null;
	}

	public function getPrice(Goods $g)
	{
		$price = $g->getPrice();
		if(is_null($price)){
			foreach ($this->goods as $goods) {
				$classe = $goods->entity;
				if($g instanceof $classe){
					$price = $goods->price;
					break;
				}
			}
		}
		return $price;
	}

	public function getDevise(Goods $g)
	{
		$currency = $g->getDevise();
		if(is_null($currency)){
			foreach ($this->goods as $goods) {
				$classe = $goods->entity;
				if($g instanceof $classe){
					$currency = $goods->currency;
					break;
				}
			}
		}
		return $currency;
	}

	public function getClassFor($gcode)
	{
		$name = (preg_split("/_/", $gcode));
		$name = $name[0];
		foreach ($this->goods as $goods) {
			if($name == $goods->name){
				return $goods->entity;
			}
		}
		return null;
	}

	public function getNameFor($gcode)
	{
		if(preg_match("/_/", $gcode)){
			$name = (preg_split("/_/", $gcode));
			return $name[0];
		}
		return null;
	}
}