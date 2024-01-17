<?php

namespace Pemm\Gateway\Stripe;

use Omnipay\Omnipay;
use Pemm\Config;
use Pemm\Core\View;
use Pemm\Model\Helper;
use Pemm\Model\Order as OrderModel;
use Pemm\Model\OrderItem;
use Pemm\Model\Setting;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class Stripe
{
    /* @var Setting */
    private $setting;

    /* @var Session */
    private $session;

    private $request;

    public function __construct()
    {
        global $container;

        $this->setting = $container->get('setting');
        $this->session = $container->get('session');
        $this->request = $container->get('request');
    }

    public function pay(OrderModel $order)
    {
        View::render('customer','checkout-stripe', ['order' => $order, 'stripe_public_key' => $this->setting->getStripe_publickey()]);
    }




    public function callback($request)
    {
        global $container;

        $gateway = Omnipay::create('Stripe');
        $gateway->setApiKey($this->setting->getStripe_apikey());

        $params = [
            'transactionReference' => $this->request->get('id'),
        ];

        $response = $gateway->fetchTransaction($params);

        $orderModel = new OrderModel();
        $order = $orderModel->findBy(['filter' => ['number' => $this->request->query->get('order')]],true);

        $result = new \stdClass();
        $result->success = false;
        $result->errorMessage = '';

        if (!empty($order) ) {

            $order
                ->setPaymentStatus('completed')
                ->store();

            $order->createInvoice();

            $container->set('order', $order);

            $result->success = true;

        } elseif ($response->isCancelled() || $response->isExpired()) {

            $order
                ->setPaymentStatus('failure')
                ->store();

            $container->set('order', $order);

            $result->errorMessage = '';

        }else{
            echo('mollie.php 99');
        }



        return $result;

    }

}
