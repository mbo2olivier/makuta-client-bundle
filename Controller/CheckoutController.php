<?php

namespace Makuta\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CheckoutController extends Controller
{
	const STATUS_TERMINATED=4;

    public function callbackAction()
    {
        $token  = $this->get('request')->query->get('token');
        if($token == '') throw $this->createNotFoundException("Le paiement désigné n'existe pas");
        try {
        	$enabled = $this->container->getParameter('makuta_client.checkout_enabled');
        	if(!$enabled) throw new \LogicException("Makuta Checkout is not enabled in your application.");
        	$trace = $this->get('makuta_client.checkout')->handleTransaction($token);
        	$route = ($trace->getStatus() == self::STATUS_TERMINATED)? $this->container->getParameter("makuta_client.routes.success_callback"): $this->container->getParameter("makuta_client.routes.failure_callback");
        	return $this->redirect($this->generateUrl($route)."?trace=".$trace->getId());
        } catch ( \InvalidArgumenException $e) {
        	throw $this->createNotFoundException($e->getMessage());
        }
    }

    public function paymentAction($gname,$gcode)
    {
    	$url  = $this->get('makuta_client.checkout')->openTransaction($gname,$gcode);
    	return $this->redirect($url);
    }
}
