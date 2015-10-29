<?php

namespace Makuta\ClientBundle\Model;

interface Goods
{
	public function getCode();

	public function getLabel();

	public function getPrice();

	public function getDevise();

	public function getName();
	
}