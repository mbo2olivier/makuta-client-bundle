<?php

namespace Makuta\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckoutController extends Controller
{
	const STATUS_TERMINATED=4;

    public function callbackAction()
    {
        $token  = $this->get('request')->query->get('token');
        if($token == '') throw $this->createNotFoundException("Aucun jeton de paiement n'a été trouvé");
        try {
        	$enabled = $this->container->getParameter('makuta_client.checkout_enabled');
        	if(!$enabled) throw new \LogicException("Makuta Checkout is not enabled in your application.");
        	$trace = $this->get('makuta_client.tx_tracer')->findTraceByToken($token);
            if(is_null($trace)) throw $this->createNotFoundException("Le paiement désigné n'existe pas");
        	$route = ($trace->getStatus() == self::STATUS_TERMINATED)? $this->container->getParameter("makuta_client.routes.success_callback"): $this->container->getParameter("makuta_client.routes.failure_callback");
        	return $this->redirect($this->generateUrl($route)."?trace=".$trace->getId());
        } catch ( \InvalidArgumentException $e) {
        	throw $this->createNotFoundException($e->getMessage());
        }
    }

    public function processAction()
    {
        $token  = $this->get('request')->request->get('TOKEN');
        if($token == '') throw $this->createNotFoundException("Le paiement désigné n'existe pas");
        try {
            $enabled = $this->container->getParameter('makuta_client.checkout_enabled');
            if(!$enabled) throw new \LogicException("Makuta Checkout is not enabled in your application.");
            $trace = $this->get('makuta_client.checkout')->handleTransaction($token);
            return new Response("SUCCESS");
        } catch ( \InvalidArgumentException $e) {
            throw $this->createNotFoundException($e->getMessage());
        }
    }

    public function paymentAction($code)
    {
    	$url  = $this->get('makuta_client.checkout')->openTransaction($code);
    	return $this->redirect(urldecode($url));
    }
}
