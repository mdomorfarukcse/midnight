<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\Jwt;
use Pemm\Core\View;
use Pemm\Model\CustomerGroup;
use Pemm\Model\CustomerVehicle;
use Pemm\Model\EmailNotification;
use Pemm\Model\Helper;
use Pemm\Model\Page;
use Pemm\Model\Setting;
use Pemm\Model\Tuning;
use Pemm\Model\TuningAdditionalOption;
use Pemm\Model\VehicleAdditionalOption;
use Pemm\Model\VehicleTuning;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Pemm\Model\Category;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Customer as CustomerModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Vehicle;
use Pemm\Controller\Sms;
use Pemm\Model\SmsProvider;

class Customer extends CoreController
{
    public function registerIndex()
    {
        try {

          /* @var Session $session */
                    $session = $this->container->get('session');

                    if ($this->request->getMethod() == 'POST') {

                      $data = array(
                         'secret'    => $this->setting->getGoogleSecret(),
                         'response'  => $this->request->request->get('recaptcha_response')
                     );

                     $verify = curl_init();
                     curl_setopt($verify, CURLOPT_URL,   "https://www.google.com/recaptcha/api/siteverify");
                     curl_setopt($verify, CURLOPT_POST, true);
                     curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
                     curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
                     $verifyResponse = curl_exec($verify);
                     $responseData = json_decode($verifyResponse);

                        if( !$responseData->success || @$responseData->score < 0.5)  {
                            throw new \Exception($this->language::translate('Capctha required'));
                        }



                $firstName = $this->request->request->get('firstName');
                $lastName = $this->request->request->get('lastName');
                $email = $this->request->request->get('email');

                $contactNumber = $this->request->request->get('contactNumber');
                $full_phone = $this->request->request->get('full_phone');
                $contactNumber = empty(trim($full_phone)) ? $contactNumber : $full_phone;

                $password = $this->request->request->get('password');
                $country = $this->request->request->get('country');
                $companyName = $this->request->request->get('companyName');
                $vatNumber = $this->request->request->get('vatNumber');
                $city = $this->request->request->get('city');
                $evcnumber = $this->request->request->get('evcnumber');
                $evccredit = $this->request->request->get('evccredit');
                $address = $this->request->request->get('address');
                $terms = $this->request->request->get('terms');
                $csrfToken = $this->request->request->get('csrf_token');

                if (
                    empty($firstName) ||
                    empty($lastName) ||
                    empty($email) ||
                    empty($password)
                ) {
                    throw new \Exception($this->language::translate('You entered missing information'));
                }

                if (empty($terms)) throw new \Exception($this->language::translate('This user is already registered!'));

                $customer = (new CustomerModel())->findOneBy(['filter' => ['email' => $email]]);

                if (!empty($customer))
                    throw new \Exception($this->language::translate('This user is already registered!'));

                $customer = new CustomerModel();
                $customer
                    ->setFirstName($firstName)
                    ->setLastName($lastName)
                    ->setEmail($email)
                    ->setCustomerGroup((new CustomerGroup())->find(1))
                    ->setIp($this->request->getClientIp())
                    ->setContactNumber($contactNumber)
                    ->setCountry($country)
                    ->setCompanyName($companyName)
                    ->setVatNumber($vatNumber)
                    ->setCity($city)
                    ->setEvcnumber($evcnumber)
                    ->setEvcCredit($evccredit)
                    ->setAddress($address)
                    ->setPassword(password_hash($password, PASSWORD_DEFAULT))
                    ->setReference(Helper::generateRandomString(64));

                if (!$customer->save())
                    throw new \Exception($this->language::translate('An error occurred during the registration process, please try again.'));


                /* @var Setting $session */
                $setting = $this->container->get('setting');

                if($setting->getMailAfterRegister()) {
                    $customer->sendConfirmationMail();

                    $session->getFlashBag()->add('success', $this->language::translate('A confirmation e-mail has been sent to your :email e-mail address. Please follow the instructions on your property!', ['email' => $email]));
                }else {
                    $customer
                        ->setStatus(1)
                        ->setAllowLogin(1)
                        ->save();


                    (new Sms())->registerSms($customer->getId());

                    $session->getFlashBag()->add('success', $this->language::translate('Your subscription has been activated', ['email' => $email]));
                }


                $session->remove('csrf_token');

            }

            Helper::csrfToken();

            $this->container->set('page',
                (new Page())
                    ->setType('register')
                    ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                    ->setMetaTitle('Register - ' . $this->setting->getSiteName())
                    ->setMetaDescription('Register - ' . $this->setting->getDescription())
            );

        } catch (\Exception $e) {
            $session->getFlashBag()->add('danger', $e->getMessage());
        }

        View::render('customer','uye-ol', []);

    }

    public function loginIndex()
    {
		
        try {
            $setting = $this->setting;

            /* @var Session $session */
            $session = $this->container->get('session');

            $customer = $this->container->get('customer');

            if (!empty($customer))
                header('location: /panel');

            if ($this->request->getMethod() == 'POST') {


              $data = array(
                'secret'     => $this->setting->getGoogleSecret(),
                 'response'  => $this->request->request->get('recaptcha_response')
             );

             $verify = curl_init();
             curl_setopt($verify, CURLOPT_URL,   "https://www.google.com/recaptcha/api/siteverify");
             curl_setopt($verify, CURLOPT_POST, true);
             curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
             curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
             $verifyResponse = curl_exec($verify);
             $responseData = json_decode($verifyResponse);
             if( !$responseData->success || @$responseData->score < 0.5)  {
                 throw new \Exception($this->language::translate('Capctha required'));
             }
                $email = $this->request->request->get('email');
                $password = $this->request->request->get('password');
                $csrfToken = $this->request->request->get('csrf_token');

                if (
                    empty($email) ||
                    empty($password)
                ) {
                    throw new \Exception($this->language::translate('You entered missing information'));
                }

                $customer = (new CustomerModel())->findOneBy(['filter' => ['email' => $email]]);

                if (
                    empty($customer) ||
                    !(password_verify($password, $customer->getPassword()))
                ) {
                    throw new \Exception($this->language::translate('Username or password is incorrect!'));
                }

                if (!$customer->getStatus()) {
                    throw new \Exception($this->language::translate('Account verification pending!'));
                }

                if (!$customer->getAllowLogin()) {
                    throw new \Exception($this->language::translate('Your account has been suspended'));
                }

                $token = Helper::generateRandomString(64);

                $customer
                    ->setIp($this->request->getClientIp())
                    ->setToken($token)
                    ->save();

                if ($this->request->request->has('remember_me'))
                    setcookie('token', $token, time() + (86400 * 1), '/');

                $customer->session();
                $session->remove('csrf_token');

                header('location: /panel');
            }

            Helper::csrfToken();

            $this->container->set('page',
                (new Page())
                    ->setType('login')
                    ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                    ->setMetaTitle('Login - ' . $this->setting->getSiteName())
                    ->setMetaDescription('Login - ' . $this->setting->getDescription())
            );

        } catch (\Exception $e) {
            $session->getFlashBag()->add('danger', $e->getMessage());
        }

        View::render('customer','giris-yap', []);

    }

    public function accountActivation()
    {
        try {

            /* @var Session $session */
            $session = $this->container->get('session');

            if ($this->request->query->has('reference')) {

                $customer = (new CustomerModel())->findOneBy(['filter' => ['reference' => $this->request->query->get('reference')]]);

                if (empty($customer)) {
                    throw new \Exception($this->language::translate('Reference number is incorrect!'));
                }

                $customer
                    ->setStatus(1)
                    ->setAllowLogin(1)
                    ->save();

                header('location: /panel/login?confirm_message=' . $this->language::translate('Your account has been activated'));

            }

        } catch (\Exception $e) {
            $session->getFlashBag()->add('danger', $e->getMessage());
        }

        Helper::csrfToken();

        $this->container->set('page',
            (new Page())
                ->setType('register')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Register - ' . $this->setting->getSiteName())
                ->setMetaDescription('Register - ' . $this->setting->getDescription())
        );

        View::render('customer','uye-ol', []);

    }

    public function forgotPasswordIndex()
    {
        try {

            /* @var Session $session */
            $session = $this->container->get('session');

            if($this->request->isMethod('post')) {
                if (
                    !$this->request->request->has('email') ||
                    empty($customer = (new CustomerModel())->findOneBy(['filter' => ['email' => $this->request->request->get('email')]]))
                ) throw new \Exception($this->language::translate('User not found'));
                $session->getFlashBag()->add('success', $this->language::translate('A confirmation e-mail has been sent to your :email e-mail address. Please follow the instructions on your property!', ['email' => $this->request->request->get('email')]));
                $customer->sendForgotEmail();


            }

            if ($this->request->query->has('reference')) {

                $customer = (new CustomerModel())->findOneBy(['filter' => ['reference' => $this->request->query->get('reference')]]);

                if (empty($customer)) {
                    throw new \Exception($this->language::translate('Reference number is incorrect!'));
                }

                $session->set('forgotPasswordConfirmEmail', $customer->getEmail());

                header('location: /panel/reset-password');

            }

        } catch (\Exception $e) {
            $session->getFlashBag()->add('danger', $e->getMessage());
        }

        Helper::csrfToken();

        $this->container->set('page',
            (new Page())
                ->setType('forgot-password')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Forgot Password - ' . $this->setting->getSiteName())
                ->setMetaDescription('Forgot Password - ' . $this->setting->getDescription())
        );

        View::render('customer','forgot-password', []);

    }

    public function resetPasswordIndex()
    {
        try {

            /* @var Session $session */
            $session = $this->container->get('session');

            if (!$session->has('forgotPasswordConfirmEmail')) {
                header('location: /panel/login');
            }

            if($this->request->isMethod('post')) {
                if (
                    !$this->request->request->has('email') ||
                    !$this->request->request->has('password') ||
                    empty($customer = (new CustomerModel())->findOneBy(['filter' => ['email' => $this->request->request->get('email')]]))
                ) throw new \Exception($this->language::translate('User not found'));


                $customer->setPassword(password_hash($this->request->request->get('password'), PASSWORD_DEFAULT))
                        ->setReference('')
                        ->save();

                $session->remove('forgotPasswordConfirmEmail');

                header('location: /panel/login?confirm_message=' . $this->language::translate('Your account password has been successfully updated'));
            }

        } catch (\Exception $e) {
            $session->getFlashBag()->add('danger', $e->getMessage());
        }

        Helper::csrfToken();

        $this->container->set('page',
            (new Page())
                ->setType('reset-password')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Reset Password - ' . $this->setting->getSiteName())
                ->setMetaDescription('Reset Password - ' . $this->setting->getDescription())
        );

        View::render('customer','reset-password', []);

    }

    public function fileUpload()
    {
        try {

            /* @var Session $session */
            $session = $this->container->get('session');


            Helper::csrfToken();

            $this->container->set('page',
                (new Page())
                    ->setType('file-upload')
                    ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                    ->setMetaTitle('Dosya Yükle - ' . $this->setting->getSiteName())
                    ->setMetaDescription('Dosya Yükle - ' . $this->setting->getDescription())
            );

        } catch (\Exception $e) {
            $session->getFlashBag()->add('danger', $e->getMessage());
        }

        View::render('customer','dosya-yukle', []);

    }

    public function ajaxCreateVehicle()
    {
        try {

            /* @var Session $session */
            $session = $this->container->get('session');

            /* @var CustomerModel $customer */
            $customer = $this->container->get('customer');

            if ($this->request->getMethod() != 'POST') {
                header('location: /panel');
            }

            $vehicle = (new Vehicle())->find($this->request->request->get('vehicleId'));
            $vehicleTuning = (new Tuning())->find($this->request->request->get('tuning'));

            // echo '<pre>';
            // print_r($_POST);
            // exit;

            $totalCredit = $vehicleTuning->getCredit();
            $options = [];

            $requestOptions = explode(',', $this->request->request->get('tuningOptions'));

            /* @var VehicleAdditionalOption $vehicleAdditionalOption */
            foreach ($vehicleTuning->getOptions() as $vehicleAdditionalOption) {
                if (in_array($vehicleAdditionalOption->getId(), $requestOptions)) {
                    $options[] = $vehicleAdditionalOption;
                    $totalCredit += $vehicleAdditionalOption->getCredit();
                }
            }


            if ($customer->getCredit() < $totalCredit)
                throw new \Exception($this->language::translate('Insufficient Credit'));

            $customerVehicle = new CustomerVehicle();

            $wmv_model = $this->request->request->get('model');
            $wmv_ecu = $this->request->request->get('ecu');
            if ($_POST['wmvmanual'] == 1) {
                $wmv_model = (is_numeric($this->request->request->get('model'))?$this->request->request->get('option_model'):$this->request->request->get('model'));
                $wmv_ecu = (is_numeric($this->request->request->get('ecu'))?$this->request->request->get('option_ecu'):$this->request->request->get('ecu'));
            }

            $customerVehicle
                ->setCustomerId($customer->getId())
                ->setVehicleTuning($vehicleTuning)
                ->setVehicle($vehicle)
                ->setTuning($vehicleTuning->getId())
                ->setOptions($options)
                ->setStatus('pending')
                ->setEcu($wmv_ecu)
                ->setModel($wmv_model)
                ->setManufacturer($this->request->request->get('manufacturer'))
                ->setKilometer($this->request->request->get('kilometer'))
                ->setGear($this->request->request->get('gear'))
                ->setTorque(str_replace(['nm', 'Nm'], '', $this->request->request->get('torque')))
                ->setPower(str_replace(['hp', 'Hp'], '', $this->request->request->get('power')))
                ->setVehicleRegistration($this->request->request->get('vehicle_registration'))
                ->setReadingDevice($this->request->request->get('read_device'))
                ->setMasterSlave($this->request->request->get('master_slave'))
                ->setFileTime($this->request->request->get('file_time'))
                ->setReadingType($this->request->request->get('read_type'))
                ->setEquipment($this->request->request->get('equipment'))
                ->setSoftware($this->request->request->get('software'))
                ->setNote($this->request->request->get('note'))
                ->setEcuFile('')
                ->setLogFile('')
                ->setIdFile('')
                ->setDynoFile('')
                ->setTotalCredit($totalCredit);

            if ($this->request->files->has('ecuFile')) {
                $ecuFile = $customerVehicle->upload('ecu', $this->request->files->get('ecuFile'));
                if ($ecuFile !== false) {
                    $customerVehicle->setEcuFile($ecuFile->getBasename());
                }
            }

              if ($this->request->files->has('idFile')) {
                  $idFile = $customerVehicle->upload('id', $this->request->files->get('idFile'));
                  
                if ($idFile !== false) {
                    $customerVehicle->setIdFile($idFile->getBasename());
                }
            }

            if ($this->request->files->has('logFile')) {
                $logFile = $customerVehicle->upload('log', $this->request->files->get('logFile'));
                
                if ($logFile !== false) {
                    $customerVehicle->setLogFile($logFile->getBasename());
                }
            }

            if ($this->request->files->has('dynoFile')) {
                $dynoFile = $customerVehicle->upload('dyno', $this->request->files->get('dynoFile'));
                
                if ($dynoFile !== false) {
                    $customerVehicle->setDynoFile($dynoFile->getBasename());
                }
            }

            $wmvmanual_data = [
                'status' => false,
            ];

            if ($_POST['wmvmanual'] == 1) {
                $wmv_main = (is_numeric($this->request->request->get('main'))?$this->request->request->get('option_main'):$this->request->request->get('main'));
                $wmv_brand = (is_numeric($this->request->request->get('brand'))?$this->request->request->get('option_brand'):$this->request->request->get('brand'));
                $wmv_generation = (is_numeric($this->request->request->get('generation'))?$this->request->request->get('option_generation'):$this->request->request->get('generation'));
                $wmv_engine = (is_numeric($this->request->request->get('engine'))?$this->request->request->get('option_engine'):$this->request->request->get('engine'));

                $wmvmanual_data = [
                    'status' => true,
                    'wmv_vehicle_name' => $wmv_main,
                    'wmv_brand_name' => $wmv_brand,
                    'wmv_generation_name' => $wmv_generation,
                    'wmv_engine_name' => $wmv_engine,
                ];
                if ($this->request->request->get('vehicleId') == 177) {
                    $wmvmanual_data['wmv_vehicleId'] = NULL;
                }
            }

            // echo '<pre>';
            // print_r($wmvmanual_data);
            // print_r($_POST);
            // exit;

            $customerVehicle->store($wmvmanual_data);

            $smsProvider = (new SmsProvider())->find(1);
            $account_sid = trim($smsProvider->getToken());
            if (!empty($account_sid)) {
                (new Sms())->customerSendFile();
            }

            (new EmailNotification())->send('customerVehicle', 'CustomerChangeStatus', $customerVehicle, $wmvmanual_data);

            $customer->setCredit($customer->getCredit() - $totalCredit);
            $customer->save();

        } catch (\Exception $e) {
            print_r($e);
            return (new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]))->send();
        }


        return (new JsonResponse([
            'success' => true,
            'message' => $this->language::translate('Vehicle registration successful')
        ]))->send();

    }

    public function logout()
    {
        $session = $this->container->get('session');
        $session->remove('customerLogin');
        $session->remove('customerId');
        setcookie('token', '', time() + 1, '/');
        header('location: /panel/login');
    }
}
