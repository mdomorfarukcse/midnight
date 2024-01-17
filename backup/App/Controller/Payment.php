<?php

namespace Pemm\Controller;

use Omnipay\Omnipay;
use Pemm\Config;
use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Gateway\Stripe\Stripe;
use Pemm\Model\Customer;
use Pemm\Model\Payment as PaymentModel;
use Pemm\Gateway\IyziPay\IyziPay;
use Pemm\Gateway\Mollie\Mollie;
use Pemm\Gateway\Paypal\Paypal;
use Pemm\Gateway\btcpayserver\btcpayserver;
use Pemm\Model\Setting;
use Pemm\Controller\Sms;

class Payment extends CoreController
{
    public function ajaxMakePayment()
    {
        try {

            $mode = '';

            /* @var Customer $customer */
            $customer = $this->container->get('customer');

            $payment = new PaymentModel();
            $payment_method = $payment->method($this->request->request->get('payment_method'));

            $customer->cart();
            $order = $customer->cart->toOrder($payment_method, $mode);

                $gateway = PaymentModel::gateway($order);
                $gateway->pay($order);


        } catch (\Exception $e) {
            print_r($e);die;
        }

    }


    public function checkoutEvc()
    {
        try {

            $mode = '';

            /* @var Customer $customer */
            $customer = $this->container->get('customer');

            $payment = new PaymentModel();
            $params['payment_method'] = $payment->method($this->request->request->get('payment_method'));
            $params['country'] = $this->request->request->get('country');
            $params['state'] = $this->request->request->get('state');
            $params['city'] = $this->request->request->get('city');
            $params['address'] = $this->request->request->get('address');

            $customer->cartevc();
            $order = $customer->cartevc->toOrder($params, $mode);

            if ($mode != 'TEST') {

                switch ($params['payment_method']) {
                    case 'master':
                        $gateway = new IyziPay();
                        $gateway->pay($order);
                        break;

                    case 'mollie':
                        $gateway = new Mollie();
                        $gateway->pay($order);
                        break;

                    case 'stripe':
                        $gateway = new Stripe();
                        $gateway->pay($order);
                        break;

                    case 'paypal':
                        $gateway = new Paypal();
                        $gateway->pay($order);
                        break;

                    case 'btcpayserver':
                        $gateway = new btcpayserver();
                        $gateway->pay($order);
                        break;

                }

            } else {

                $order
                    ->setPaymentStatus('completed')
                    ->store();

                $order->createInvoice();

                $customer->setCredit($customer->getCredit() + $order->getTotalCredit());
                $customer->save();

                header('location: /panel');

                //$customer->removeCart();

                //View::render('customer','payment-success', []);

            }

        } catch (\Exception $e) {
            print_r($e);die;
        }

    }

    public function checkout()
    {
        try {

            $mode = '';

            /* @var Customer $customer */
            $customer = $this->container->get('customer');

            $payment = new PaymentModel();
            $params['payment_method'] = $payment->method($this->request->request->get('payment_method'));
            $params['country'] = $this->request->request->get('country');
            $params['state'] = $this->request->request->get('state');
            $params['city'] = $this->request->request->get('city');
            $params['address'] = $this->request->request->get('address');

            $customer->cart();
            $order = $customer->cart->toOrder($params, $mode);

            if ($mode != 'TEST') {

                switch ($params['payment_method']) {
                    case 'master':
                        $gateway = new IyziPay();
                        $gateway->pay($order);
                        break;

                    case 'mollie':
                        $gateway = new Mollie();
                        $gateway->pay($order);
                        break;

                    case 'stripe':
                        $gateway = new Stripe();
                        $gateway->pay($order);
                        break;

                    case 'paypal':
                        $gateway = new Paypal();
                        $gateway->pay($order);
                        break;

                    case 'btcpayserver':
                        $gateway = new btcpayserver();
                        $gateway->pay($order);
                        break;

                }

            } else {

                $order
                    ->setPaymentStatus('completed')
                    ->store();

                $order->createInvoice();

                $customer->setCredit($customer->getCredit() + $order->getTotalCredit());
                $customer->save();

                header('location: /panel');

                //$customer->removeCart();

                //View::render('customer','payment-success', []);

            }

        } catch (\Exception $e) {
            print_r($e);die;
        }

    }

    public function callback()
    {
        View::render('customer','payment-success', []);
        View::render('customer','payment-failure', []);
    }


    public function mollieCalback()
    {
        if (@$_GET['status'] !== 'paid') {
            View::render('customer','payment-failure', []);
            exit;
        }

        try {

            $customer = $this->container->get('customer');

            $params = [
                'gateway' => 'mollie',
                'order' => $this->request->query->get('order'),
            ];

            $payment = new PaymentModel();
            $result = $payment->callback($params);

            if ($result->success) {
                (new Sms())->orderSms($customer->getId());
                View::render('customer','payment-success', []);
            } else {
                View::render('customer','payment-failure', []);
            }

        } catch (\Exception $e) {
            echo "<h1>CONTROLLER/PAYMENT HATA</h1>"; print_r($e); die();
        }

    }


    public function iyzipayCallback()
    {

        try {

             $customer = $this->container->get('customer');

            $params = [
                'gateway' => 'iyzipay',
                'token' => $this->request->query->get('token')
            ];

            $payment = new PaymentModel();
            $result = $payment->callback($params);

            if ($result->success) {
                (new Sms())->orderSms($customer->getId());
                View::render('customer','payment-success', []);
            } else {
                View::render('customer','payment-failure', []);
            }

        } catch (\Exception $e) {
           echo "<h1>CONTROLLER/PAYMENT HATA</h1>"; print_r($e); die();
        }

    }

    public function btcpayserverCallback()
    {
        try {
            $customer = $this->container->get('customer');

            $params = [
                'gateway' => 'btcpayserver',
                'token' => $this->request->query->get('token')
            ];

            $payment = new btcpayserver();
            $result = $payment->callback($params);

            if ($result->success) {
                (new Sms())->orderSms($customer->getId());
                // View::render('customer','payment-success', []);
                echo 'success';
            } else {
                echo 'error';

                // View::render('customer','payment-failure', []);
            }

        } catch (\Exception $e) {
           echo "<h1>CONTROLLER/PAYMENT HATA</h1>"; print_r($e); die();
        }
    }


    public function paypalCallback()
    {

        try {
            $customer = $this->container->get('customer');

            $params = [
                'gateway' => 'paypal',
                'token' => $this->request->query->get('token')
            ];

            $payment = new PaymentModel();
            $result = $payment->callback($params);

            if ($result->success) {
                (new Sms())->orderSms($customer->getId());
                View::render('customer','payment-success', []);
            } else {
               echo 'Failure';
                // View::render('customer','payment-failure', []);
            }

        } catch (\Exception $e) {
           echo "<h1>CONTROLLER/PAYMENT HATA</h1>"; print_r($e); die();
        }

    }


    // Stripe Entegration
    public function StripeCharge() {

        $customer = $this->container->get('customer');


        $paymentMethodId = $this->request->get('paymentMethodId');
        $orderId         = $this->request->get('orderId');

        /* @var Setting $customer */
        $setting = $this->container->get('setting');


        $order = (new \Pemm\Model\Order())->find($orderId);


        $gateway = Omnipay::create('Stripe\PaymentIntents');
        $gateway->initialize([
            'apiKey' => $setting->getStripe_apikey(),
        ]);

        $response = $gateway->purchase([
            'amount'                   => $order->getTotal(),
            'currency'                 => $order->getCurrency(),
            'description'              => $order->getCustomer()->getFullName(),
            'paymentMethod'            => $paymentMethodId,
            'returnUrl'                => $this->setting->getSiteUrl() . '/stripe-callback?order=' . $order->getId(),
            'confirm'                  => true,
            'metadata'          => [
                'order_id' => $orderId,
            ],
        ])->send();

        $order->setNotes($response->getPaymentIntentReference())
            ->store();

        // Stripe order is OK, profit!
        if ($response->isSuccessful()) {

            $order
                ->setPaymentStatus('completed')
                ->store();

            $order->createInvoice();

            (new Sms())->orderSms($customer->getId());

            View::render('customer','payment-success', []);

            // Stripe thinks order needs additional strep
        } elseif ($response->isRedirect()) {
            $response->redirect();
        }else
            View::render('customer','payment-failure', ['message' => $response->getMessage()]);
    }

    public function stripeCallback()
    {


        try {


            /* @var Setting $customer */
            $setting = $this->container->get('setting');

            $gateway = Omnipay::create('Stripe\PaymentIntents');
            $gateway->initialize([
                'apiKey' => $setting->getStripe_apikey(),
            ]);

            $orderId = $this->request->query->get('order');

            $order =   $order = (new \Pemm\Model\Order())->find($orderId);

            $response = $gateway->confirm([
                'paymentIntentReference' => $this->request->query->get('payment_intent'),
                'returnUrl' => $this->setting->getSiteUrl() . '/stripe-callback?order=' . $order->getId(),
            ])->send();

            if ($response->isSuccessful()) {

                $order
                    ->setPaymentStatus('completed')
                    ->store();

                $order->createInvoice();

                (new Sms())->orderSms($customer->getId());

                View::render('customer','payment-success', []);
            }

            View::render('customer','payment-failure', ['message' => $response->getMessage()]);


        } catch (\Exception $e) {
            echo "<h1>CONTROLLER/PAYMENT HATA</h1>"; print_r($e); die();
        }

    }

}
