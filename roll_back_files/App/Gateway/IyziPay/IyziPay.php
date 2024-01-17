<?php

namespace Pemm\Gateway\IyziPay;

use Pemm\Model\Product;
use IyzipayBootstrap;
use Pemm\Config;
use Pemm\Model\Order as OrderModel;
use Pemm\Model\Currency;
use Pemm\Model\Customer;
use Pemm\Model\OrderItem;
use Pemm\Core\Core;




require_once __DIR__ . '/iyzipay/IyzipayBootstrap.php';

class IyziPay
{
    private $options;
    private $setting;
    private $request2;

    public function __construct()
    {
        global $container;

        $this->setting = $container->get('setting');
        $this->request2 = $container->get('request');


        IyzipayBootstrap::init();
        $this->options = new \Iyzipay\Options();
        $this->options->setApiKey($this->setting->getIyzico_apikey());
        $this->options->setSecretKey($this->setting->getIyzico_apisecret());
        $this->options->setBaseUrl($this->setting->getIyzico_testmode() ? 'https://sandbox-api.iyzipay.com' : 'https://api.iyzipay.com');
       // $this->options->setBaseUrl('https://sandbox-api.iyzipay.com');


    }

    public function pay($order)
    {
        global $container;

        /** @var Customer $customer */
        $customer = $container->get('customer');

        /** @var Currency $currency */
        $currency = $container->get('currency');


        $session = $container->get('session');


        /** @var Order $order */

        $birim = $session->get('currency');


        $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
        $request->setLocale(\Iyzipay\Model\Locale::EN);
        $request->setPrice($order->getTotal());
        $request->setPaidPrice($order->getTotal());

        if($birim=="TRY") {
            $request->setCurrency(\Iyzipay\Model\Currency::TL);
        }elseif($birim=="USD") {
            $request->setCurrency(\Iyzipay\Model\Currency::USD);
        }elseif($birim=="EUR") {
            $request->setCurrency(\Iyzipay\Model\Currency::EUR);
        }elseif($birim=="GBP") {
            $request->setCurrency(\Iyzipay\Model\Currency::GBP);
        }

        $request->setBasketId($order->getNumber());
        $request->setCallbackUrl($this->setting->getSiteUrl() . '/iyzipay/callback');

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId($customer->getId());
        $buyer->setName($customer->getFirstName());
        $buyer->setSurname($customer->getLastName());
        $buyer->setGsmNumber($customer->getContactNumber());
        $buyer->setEmail($customer->getEmail());
       $buyer->setIdentityNumber(rand(11111111112,87896545688));
         $buyer->setRegistrationAddress("Mustafa Mahallesi. Apo Sokak NO:69");
        $buyer->setIp($customer->getIp());
		$buyer->setCity("Istanbul");
$buyer->setCountry("Turkey");
$buyer->setZipCode("34732");
        $request->setBuyer($buyer);


$shippingAddress = new \Iyzipay\Model\Address();
$shippingAddress->setContactName("Jane Doe");
$shippingAddress->setCity("Istanbul");
$shippingAddress->setCountry("Turkey");
$shippingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
$shippingAddress->setZipCode("34742");
$request->setShippingAddress($shippingAddress);

$billingAddress = new \Iyzipay\Model\Address();
$billingAddress->setContactName("Jane Doe");
$billingAddress->setCity("Istanbul");
$billingAddress->setCountry("Turkey");
$billingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
$billingAddress->setZipCode("34742");
$request->setBillingAddress($billingAddress);

        $basketItems = [];

        /** @var OrderItem $orderItem */
        foreach ($order->items as $key => $orderItem) {
            
            $item = new \Iyzipay\Model\BasketItem();
            $item->setId($orderItem->getId());
            $item->setName($orderItem->getProductName());
            $item->setCategory1("credit");
            $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
            $item->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $item->setPrice($orderItem->getUnitsTotal() + $orderItem->getTaxAmount());
            $basketItems[$key] = $item;
        }

        $request->setBasketItems($basketItems);

        $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $this->options);

        print_r($checkoutFormInitialize->getErrorMessage());
        print_r($checkoutFormInitialize->getCheckoutFormContent());

        echo '<div id="iyzipay-checkout-form" class="responsive"></div>';
    }





    public function callback2($params)
    {
        global $container;

		$token = $this->request2->request->get('token');

        $result = new \stdClass();
        $result->success = false;
        $result->errorMessage = '';

        if (!empty($token)) {


            $request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
            $request->setLocale(\Iyzipay\Model\Locale::EN);
            $request->setToken($token);

            $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, $this->options);
            $response = json_decode($checkoutForm->getRawResult(), true);
            $result->orderNumber = $response['basketId'];

			         $orderModel = new Order();

            $order = $orderModel->findBy(['filter' => ['number' => $result->orderNumber]]);


            if (!empty($order) && $checkoutForm->getPaymentStatus() == "SUCCESS" ) {

                $orderModel->setPaymentStatus('completed')->store();
                $orderModel->createInvoice();
                $container->set('order', $orderModel);
                $result->success = true;



            } else {
                $result->success = false;

                $orderModel
                    ->setPaymentStatus('failure')
                    ->store();

                $container->set('order', $orderModel);

                $result->errorMessage = @$response['errorMessage'];
            }
        }

        return $result;
    }



    public function callback($params)
    {



        global $container;

        $token = $this->request2->request->get('token');

        $result = new \stdClass();
        $result->success = false;
        $result->errorMessage = '';

        if (!empty($token)) {


            $request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
            $request->setLocale(\Iyzipay\Model\Locale::EN);
            $request->setToken($token);

            $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, $this->options);
            $response = json_decode($checkoutForm->getRawResult(), true);
            $result->orderNumber = $response['basketId'];


            $orderNumber = $result->orderNumber;
            $orderModel = new OrderModel();
            $order = $orderModel->findBy(['filter' => ['number' => $orderNumber]],true);


            if (!empty($order) && $checkoutForm->getPaymentStatus() == "SUCCESS" ) {

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
        }

        return $result;
    }





}
