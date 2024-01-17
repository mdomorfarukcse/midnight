<?php
namespace Pemm\Gateway\btcpayserver;

require __DIR__ . '/src/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Product;
use Pemm\Config;
use Pemm\Model\Order as OrderModel;
use Pemm\Model\Currency;
use Pemm\Model\Customer;
use Pemm\Model\OrderItem;
use Pemm\Core\Core;
use BTCPayServer\Client\Invoice;
use BTCPayServer\Client\InvoiceCheckoutOptions;
use BTCPayServer\Util\PreciseNumber;
use BTCPayServer\Client\Webhook;


// require_once __DIR__ . '/iyzipay/IyzipayBootstrap.php';

class Btcpayserver
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
        global $container;

        $customer = $container->get('customer');



        $apiKey = $this->setting->getbtcpayserver_apikey();
        $host = $this->setting->getbtcpayserver_host();
        $storeId = $this->setting->getbtcpayserver_storeid();
        $amount = $order->getTotal();
        $currency = $order->getCurrency();
        $orderId = $order->getNumber();
        $buyerEmail = $customer->getEmail();

        // Create a basic invoice.
        try {
            $client = new Invoice($host, $apiKey);

            $checkoutOptions = new InvoiceCheckoutOptions();
            $checkoutOptions
              ->setSpeedPolicy($checkoutOptions::SPEED_HIGH)
              // ->setPaymentMethods(['BTC'])
              ->setRedirectURL($this->setting->getSiteUrl());

            $metadata = [];

            $data = $client->createInvoice(
                    $storeId,
                    $currency,
                    PreciseNumber::parseString($amount),
                    $orderId,
                    $buyerEmail,
                    $metadata,
                    $checkoutOptions
                );

            $checkoutLink = $data->getCheckoutLink();
            if (empty($checkoutLink)) {
                echo 'Link creating error! Please try again';
            }else {
                header("Location: {$checkoutLink}");
            }
            exit;
        } catch (\Throwable $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function callback($params)
    {
        global $container;
        $result = new \stdClass();
        $result->success = false;
        $result->errorMessage = NULL;
        $result->orderNumber = NULL;

        $this->apiKey = $this->setting->getbtcpayserver_apikey();
        $this->host = $this->setting->getbtcpayserver_host();
        $this->storeId = $this->setting->getbtcpayserver_storeid();
        $this->secret = $this->setting->getbtcpayserver_apisecret();

        // $myfile = fopen("BTCPay.log", 'ab');
        $raw_post_data = file_get_contents('php://input');
        // file_put_contents("../BTCpay.".rand().".log", $raw_post_data);
        // exit;

        $date = date('m/d/Y h:i:s a');

        if (false === $raw_post_data) {
            $result->errorMessage = 'Error. Could not read from the php://input stream or invalid BTCPayServer payload received.';

            throw new \RuntimeException(
                'Could not read from the php://input stream or invalid BTCPayServer payload received.'
            );
        }

        $payload = json_decode($raw_post_data, false, 512, JSON_THROW_ON_ERROR);

        if (empty($payload)) {
            $result->errorMessage = 'Error. Could not decode the JSON payload from BTCPay.';
            throw new \RuntimeException('Could not decode the JSON payload from BTCPay.');
        }

        // verify hmac256
        $headers = getallheaders();
        foreach ($headers as $key => $value) {
            if (strtolower($key) === 'btcpay-sig') {
                $sig = $value;
            }
        }

        $webhookClient = new Webhook($this->host, $this->apiKey);

        if (!$webhookClient->isIncomingWebhookRequestValid($raw_post_data, $sig, $this->secret)) {
            $result->errorMessage = "Error. Invalid Signature detected! ";
            throw new \RuntimeException(
                'Invalid BTCPayServer payment notification message received - signature did not match.'
            );
        }

        if (true === empty($payload->invoiceId)) {
            $result->errorMessage = "Error. Invalid BTCPayServer payment notification message received - did not receive invoice ID.";
            throw new \RuntimeException(
                'Invalid BTCPayServer payment notification message received - did not receive invoice ID.'
            );
        }

        // Load an existing invoice with the provided invoiceId.
        // Most of the time this is not needed as you can listen to specific webhook events
        // See: https://docs.btcpayserver.org/API/Greenfield/v1/#tag/Webhooks/paths/InvoiceCreated/post
        try {
            $client = new Invoice($this->host, $this->apiKey);
            $invoice = $client->getInvoice($this->storeId, $payload->invoiceId);
        } catch (\Throwable $e) {
            fwrite($myfile, "Error: " . $e->getMessage());
            throw $e;
        }

        // optional: check whether your webhook is of the desired type
        if ($payload->type !== "InvoiceSettled") {
            throw new \RuntimeException(
                'Invalid payload message type. Only InvoiceSettled is supported, check the configuration of the webhook.'
            );
        }

        if ($result->errorMessage != NULL) {
            $result->orderNumber = $payload->originalDeliveryId;
            $orderNumber = $result->orderNumber;
            if (empty($orderNumber)) {
                $result->errorMessage = "Error. Order id not found!";
            }else {
                $orderModel = new OrderModel();
                $order = $orderModel->findBy(['filter' => ['number' => $orderNumber]], true);

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
        }

        // your own processing code goes here!

        return $result;
    }

}
