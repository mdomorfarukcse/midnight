<?php
namespace Pemm\Gateway\Paypal;

use Omnipay\Omnipay;
use Pemm\Config;
use Pemm\Core\View;
use Pemm\Model\Order as OrderModel;
use Pemm\Model\OrderItem;
use Pemm\Model\Setting;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class Paypal
{
    private $setting;

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
            'cancelUrl'=> $this->setting->getSiteUrl() . '/paypal-cancel?from=paypal&order=' . $order->getNumber(),
            'returnUrl'=> $this->setting->getSiteUrl() . '/paypal-callback?from=paypal&order=' . $order->getNumber(),
            'amount' =>  $order->getTotal(),
            'currency' => $order->getCurrency()
        ];

        $this->session->set('paypal_express', $params);

        $items = [];
        /* @var OrderItem $orderItem */
        foreach ($order->items as $key => $orderItem) {
            $items[$key] = [
                'name' => $orderItem->getProductName(),
                'quantity' => $orderItem->getQuantity(),
                'price' => ($orderItem->getUnitsTotal() + $orderItem->getTaxAmount()) / $orderItem->getQuantity(),
            ];
        }

        /*
                $gateway = Omnipay::create('PayPal_Rest');

                $gateway->initialize(array(
            'clientId' => Config::PAYPAL['clientId'],
            'secret'   => Config::PAYPAL['secret'],
            'testMode' => Config::PAYPAL['test_mode'],
              ));

                $response = $gateway->purchase($params)->setItems($items)->send();


                $order->setToken($response->getTransactionReference())->store();
                $response->redirect();
        */


        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername($this->setting->getPaypal_username());
        $gateway->setPassword($this->setting->getPaypal_password());
        $gateway->setSignature($this->setting->getPaypal_signature());
        $gateway->setTestMode($this->setting->getPaypal_testmode());

        $response = $gateway->purchase($params)->setItems($items)->send();



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

        $result = new \stdClass();
        $result->success = false;
        $result->errorMessage = '';
  /*
                $gateway = Omnipay::create('PayPal_Rest');
                $gateway->initialize(array(
                    'clientId' => Config::PAYPAL['clientId'],
                    'secret'   => Config::PAYPAL['secret'],
                    'testMode' => Config::PAYPAL['test_mode'],
                ));
        */

        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername($this->setting->getPaypal_username());
        $gateway->setPassword($this->setting->getPaypal_password());
        $gateway->setSignature($this->setting->getPaypal_signature());
        $gateway->setTestMode($this->setting->getPaypal_testmode());


        $params = $this->session->get('paypal_express');
        if(isset($params)) {
            $response = $gateway->completePurchase($params)->send();
            $paypalResponse = $response->getData();
        }else{
          $result->success = false;
          $result->errorMessage = 'Failure';
          return $result;
        }

              $orderModel = new OrderModel();
              $order = $orderModel->findBy(['filter' => ['number' => $this->request->query->get('order')]],true);

        if((!empty($order)) && ($paypalResponse['PAYMENTINFO_0_ACK'] == 'Success')) {

          $order->setPaymentStatus('completed')->store();
          $order->createInvoice();
          $container->set('order', $order);
          $result->success = true;

        } else {
          $result->success = false;

          $order->setPaymentStatus('failure')->store();

          $container->set('order', $order);

          $result->errorMessage = 'Failure';
        }

        return $result;

    }

}
