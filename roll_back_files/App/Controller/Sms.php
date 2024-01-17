<?php

namespace Pemm\Controller; 
use Pemm\Core\Controller as CoreController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Pemm\Model\Customer;
use Pemm\Model\Order;
use Symfony\Component\HttpFoundation\JsonResponse;
use Twilio\Rest\Client;
use Pemm\Model\SmsProvider;
use Pemm\Model\User;
use Pemm\Model\CustomerVehicle;


class Sms
{
    public function __construct($check_user=true) {
        global $container;
        $this->container = $container;
        $this->language = $container->get('language');
        if ($check_user == true) {
            if ((new Customer())->check()) {
                $this->customer = $this->container->get('customer');
                if (!empty($this->customer)) {
                    $this->userId = $this->container->get('customer')->getId();
                }
            }
        }
    }

    /**
     * Send Sms Main Function
     *
     * @param $number
     * @param $message
     * @return void
     * @throws \Twilio\Exceptions\ConfigurationException
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function sendSms($number, $message){

        $smsProvider = (new SmsProvider())->find(1);

        if ($smsProvider->getStatus() !== '1') {
            return TRUE;
            exit;
        }

        // Your Account SID and Auth Token from twilio.com/console
        $account_sid = $smsProvider->getToken();
        $auth_token = $smsProvider->getToken2();
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

        // A Twilio number you own with SMS capabilities
        $twilio_number = '+'.$smsProvider->getNumber();

        $client = new Client($account_sid, $auth_token);
        $message = $client->messages->create(
        // Where to send a text message (your cell phone?)
            $number,
            array(
                'from' => $twilio_number,
                'body' => $message
            )
        );

        // print_r($message);
    }

    /**
     * Customer Send File sms to Admin
     *
     * @return void
     * @throws \Twilio\Exceptions\ConfigurationException
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function customerSendFile(){
        $user = (new Customer())->find($this->userId);
        $admin = (new Customer())->findOneBy(['filter' => ['role' => 1]]);
        $message_text = \Pemm\Core\Language::translate(strip_tags($user->getfirstName()." ".$user->getlastName()).' uploaded a new file.');
        if (!empty($admin->getContactNumber())) {
            $this->sendSms($admin->getContactNumber(), $message_text);
        }
    }

    /**
     * Customer Create Support sms to Admin
     *
     * @return void
     * @throws \Twilio\Exceptions\ConfigurationException
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function customerCreateSupport($wmvmessage){

        $user = (new Customer())->find($this->userId);
        $admin = (new Customer())->findOneBy(['filter' => ['role' => 1]]);

        $message_text = \Pemm\Core\Language::translate(strip_tags($user->getfirstName()." ".$user->getlastName()).' sent a new support request. Message: ' . $wmvmessage);

        $this->sendSms($admin->getContactNumber(), $message_text);
    }

    /**
     * Customer Reply Current Support sms to Admin
     *
     * @return void
     * @throws \Twilio\Exceptions\ConfigurationException
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function customerReplySupport($wmvmessage){

        $user = (new Customer())->find($this->userId);
        $admin = (new Customer())->findOneBy(['filter' => ['role' => 1]]);

        $message_text = \Pemm\Core\Language::translate(strip_tags($user->getfirstName()." ".$user->getlastName()).' replied. Message: ' . $wmvmessage);

        $this->sendSms($admin->getContactNumber(), $message_text);
    }


    /**
     * Register Sms
     * @param $to_user
     * @return void
     */
    public function registerSms($to_user){

        $customer = (new Customer())->find($to_user);

        $message_text = \Pemm\Core\Language::translate(strip_tags($customer->getfirstName()." ".$customer->getlastName()).', Welcome to Join Us.');

        $this->sendSms($customer->getContactNumber(), $message_text);
    }

    /**
     * Success Order Sms
     *
     * @param $to_user
     * @return void
     * @throws \Twilio\Exceptions\ConfigurationException
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function orderSms($to_user){

        $customer = (new Customer())->find($to_user);

        $order = (new Order())->findOneBy(['filter' => ['customer_id' => $to_user]]);

        $message_text = \Pemm\Core\Language::translate($order->number.' has been created. Order amount: '.$order->totalCredit.' '.$order->currency.'- Order status: '.$this->language::translate($order->paymentStatus));

        $this->sendSms($customer->getContactNumber(), $message_text);
    }


    /**
     * Admin Change File Status sms to Customer
     *
     * @param $to_user
     * @return void
     * @throws \Twilio\Exceptions\ConfigurationException
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function adminChangeFileStatus($vdetail){

        $customerVehicle = (new CustomerVehicle())->find($vdetail);
        $to_user = $customerVehicle->getCustomer()->getId();

        $customer = (new Customer())->find($to_user);

        $status = $customerVehicle->getStatus();

        $full_name = $customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('vehicle_full_name') : $customerVehicle->getVehicle()->getFullName();

        $message_text = \Pemm\Core\Language::translate('The file status of your '.$full_name . ' Vehicle has changed. File Status: ' . $this->language::translate($status));

        $this->sendSms($customer->getContactNumber(), $message_text);
    }

    /**
     * Admin Reply Support sms to Customer
     *
     * @param $to_user
     * @return void
     * @throws \Twilio\Exceptions\ConfigurationException
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function adminReplySupport($to_user, $wmvmessage){

        $customer = (new Customer())->find($to_user);
		
         $message_text = \Pemm\Core\Language::translate('Your Support Request Has Been Answered. Message: ' . $wmvmessage);

        $this->sendSms($customer->getContactNumber(), $message_text);
    }

}
