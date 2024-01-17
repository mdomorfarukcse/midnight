<?php

namespace Pemm\Model;

use Pemm\Config;
use Pemm\Core\Model as BaseModel;
use Pemm\Model\Setting;
use Pemm\Views\Notification\CustomerFileMail;
use Pemm\Views\Notification\CustomerVehicleChangeMail;
use Pemm\Views\Notification\CustomerVehicleMail;
use Pemm\Views\Notification\OrderMail;
use Pemm\Views\Notification\CustomerMail;
use Pemm\Views\Notification\SupportMail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use PDO;

class EmailNotification extends BaseModel
{
    public function phpMailer()
    {
        global $container;

        /* @var Setting $setting */
        $setting = $container->get('setting');

        $phpMailer = new PHPMailer;

        $phpMailer->IsSMTP();
      //  $phpMailer->SMTPDebug = 1;
        $phpMailer->SMTPAuth = true;
        $phpMailer->SMTPSecure = $setting->getEmailSecureType();
        $phpMailer->SMTPOptions = array(
          'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
          )
          );
        $phpMailer->Host = $setting->getEmailHost();
        $phpMailer->Port = $setting->getEmailPort();
        $phpMailer->IsHTML(true);
        $phpMailer->SetLanguage("tr", "phpmailer/language");
        $phpMailer->CharSet  ="utf-8";
        $phpMailer->Username = $setting->getEmailUsername();
        $phpMailer->Password = $setting->getEmailPassword();
        $phpMailer->SetFrom($setting->getEmailUsername(), $this->setting->getSiteName());


        return $phpMailer;

    }

    public function send($type, $action, $object, $wmvmanual_data = array('status' => false))
    {
        global $container;

        /* @var Setting $setting */
        $setting = $container->get('setting');

        $phpMailer = $this->phpMailer();

        //$phpMailer->addCC($setting->getEmailUsername());
        $phpMailer->addReplyTo($setting->getEmailUsername(), $this->setting->getSiteName());

        switch ($type) {
            case 'customer':
                /* @var Customer $object */;
                $phpMailer->AddAddress($object->getEmail());
                switch ($action) {
                    case 'confirmation':
                        $phpMailer->Subject = 'Welcome ' . $object->getFullName();
                        $phpMailer->Body = CustomerMail::Confirmation($object);
                        break;
                    case 'forgotPassword':
                        $phpMailer->Subject = 'Forgot Password';
                        $phpMailer->Body = CustomerMail::forgotPassword($object);
                        break;
                }
                break;
            case 'customerVehicle':
                /* @var CustomerVehicle $object */;
                switch ($action) {
                    case 'CustomerChangeStatus':
                        // $phpMailer->AddAddress($setting->getEmailUsername());
                        $phpMailer->AddAddress('bsfree@effobe.com');

                        $phpMailer->Subject = 'File Status Change';
                        $phpMailer->Body = CustomerVehicleMail::statusChange($object, $wmvmanual_data);
                        break;
                    case 'statusChange':
                        $phpMailer->AddAddress($object->getCustomer()->getEmail());
                        $phpMailer->Subject = 'File Status Change';
                        $phpMailer->Body = CustomerVehicleMail::statusChange($object, $wmvmanual_data);
                        break;
                    case 'awaitingPayment':
                        $phpMailer->AddAddress($object->getCustomer()->getEmail());

                        $phpMailer->Subject = 'Awaiting Payment';
                        $phpMailer->Body = CustomerVehicleChangeMail::awaitingPayment($object);
                        break;
                }
                break;
            case 'order':
                /* @var Order $object */;
                $phpMailer->AddAddress($object->getCustomer()->getEmail());
                switch ($action) {
                    case 'completed':
                        $phpMailer->Subject = 'Your Payment Has Successfully.';
                        $phpMailer->Body = OrderMail::completed($object);
                        break;
                }
                break;
            case 'support':
                /* @var Support $object */;
                if($object->getType() == 'admin')
                    $phpMailer->AddAddress($object->getCustomer()->getEmail());
                if($object->getType() == 'customer')
                    $phpMailer->AddAddress($setting->getEmailUsername());
                switch ($action) {
                    case 'new':
                        $phpMailer->Subject = 'Support Request Has Created.';
                        $phpMailer->Body = SupportMail::new($object);
                        break;
                    case 'reply':
                        $phpMailer->Subject = 'Support Request Replied.';
                        if($object->getType() == "customer")
                            $phpMailer->Body = SupportMail::replyCustomer($object);
                        else
                            $phpMailer->Body = SupportMail::reply($object);
                        break;
                }
                break;
        }

        $phpMailer->send();

        return true;

    }
}
