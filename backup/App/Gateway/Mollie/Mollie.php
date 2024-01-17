<?php

namespace Pemm\Gateway\Mollie;

use Omnipay\Omnipay;
use Pemm\Config;
use Pemm\Core\View;
use Pemm\Model\Order as OrderModel;
use Pemm\Model\OrderItem;
use Pemm\Model\Setting;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class Mollie
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
        $params = [
            'notifyUrl'=> $this->setting->getSiteUrl() . '/mollie-callback?order=' . $order->getNumber(),
            'returnUrl'=> $this->setting->getSiteUrl() . '/mollie-info',
            'amount' =>  $order->getTotal(),
            "description" => "Order",
            'currency' => $order->getCurrency()
        ];

        $this->session->set('mollie', $params);

        $gateway = Omnipay::create('Mollie');
        $gateway->setApiKey($this->setting->getMollie_apikey());

        $response = $gateway->purchase($params)->send();

        if ($response->isSuccessful()) {
            print_r($response);
        } elseif ($response->isRedirect()) {
            $order->setToken($response->getTransactionReference())->store();
            $response->redirect();
        } else {
            echo $response->getMessage();
        }
    }

    public function callback($request)
    {
        global $container;

        $gateway = Omnipay::create('Mollie');
        $gateway->setApiKey($this->setting->getMollie_apikey());


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
