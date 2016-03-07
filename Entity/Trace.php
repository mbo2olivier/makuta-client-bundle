<?php

namespace Makuta\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trace
 *
 * @ORM\Table(name="makuta_trace",indexes={@ORM\Index(name="by_idx", columns={"buyer_id"}),
 *                     @ORM\Index(name="gd_idx", columns={"goods_code"})
 *  })
 * @ORM\Entity
 */
class Trace
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="buyer_id", type="string", length=255)
     */
    private $buyerId;

    /**
     * @var string
     *
     * @ORM\Column(name="goods_code", type="string", length=255)
     */
    private $goodsCode;

    /**
     * @var string
     *
     * @ORM\Column(name="buyer_name", type="string", length=255)
     */
    private $buyerName;

    /**
     * @var string
     *
     * @ORM\Column(name="goods_label", type="string", length=255)
     */
    private $goodsLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal",scale=2)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=4)
     */
    private $currency;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255,unique=true)
     */
    private $token;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=15,nullable=true)
     */
    private $tel;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set buyerId
     *
     * @param string $buyerId
     * @return Trace
     */
    public function setBuyerId($buyerId)
    {
        $this->buyerId = $buyerId;

        return $this;
    }

    /**
     * Get buyerId
     *
     * @return string 
     */
    public function getBuyerId()
    {
        return $this->buyerId;
    }

    /**
     * Set goodsCode
     *
     * @param string $goodsCode
     * @return Trace
     */
    public function setGoodsCode($goodsCode)
    {
        $this->goodsCode = $goodsCode;

        return $this;
    }

    /**
     * Get goodsCode
     *
     * @return string 
     */
    public function getGoodsCode()
    {
        return $this->goodsCode;
    }

    /**
     * Set buyerName
     *
     * @param string $buyerName
     * @return Trace
     */
    public function setBuyerName($buyerName)
    {
        $this->buyerName = $buyerName;

        return $this;
    }

    /**
     * Get buyerName
     *
     * @return string 
     */
    public function getBuyerName()
    {
        return $this->buyerName;
    }

    /**
     * Set goodsLabel
     *
     * @param string $goodsLabel
     * @return Trace
     */
    public function setGoodsLabel($goodsLabel)
    {
        $this->goodsLabel = $goodsLabel;

        return $this;
    }

    /**
     * Get goodsLabel
     *
     * @return string 
     */
    public function getGoodsLabel()
    {
        return $this->goodsLabel;
    }

    /**
     * Set amount
     *
     * @param string $amount
     * @return Trace
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set currency
     *
     * @param string $currency
     * @return Trace
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Trace
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Trace
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Trace
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Trace
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string 
     */
    public function getTel()
    {
        return $this->tel;
    }
}
