<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use Pemm\Gateway\Mollie\Mollie;
use Pemm\Gateway\IyziPay\IyziPay;
use Pemm\Gateway\Paypal\Paypal;
use Pemm\Model\Setting;
use Symfony\Component\HttpFoundation\Request;

use PDO;

class Payment extends BaseModel
{
    public function method($method = null)
    {
       return $method ?? $this->setting->getDefaultPaymentMethod();
    }

    public function pay($order)
    {
        $gateway = self::gateway($order);
        return $gateway->pay($order);
    }

    public function callback($params)
    {


        global $container;

        /** @var Customer $customer */
        $customer = $container->get('customer');

        switch ($params['gateway']) {
            case 'iyzipay':
                $gateway = new IyziPay();
                break;
            case 'mollie':
                $gateway = new Mollie();
                break;
            case 'paypal':
                $gateway = new Paypal();
                break;

        }

        $result = $gateway->callback($params);

        return $result;

    }



    public static function gateway($order)
    {
        /** @var Order $order */
        if ($order->getId()) {
            switch ($order->getPaymentType()) {
                case 'mollie':
                    return new Mollie();
                case 'paypal':
                    return new Paypal();
                case 'iyzipay':
                    return new IyziPay();
            }
        }
    }
}
