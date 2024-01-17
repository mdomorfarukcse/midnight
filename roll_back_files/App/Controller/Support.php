<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Customer;
use Pemm\Model\EmailNotification;
use Pemm\Model\Helper;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Support as SupportModel;
use Pemm\Controller\Sms;
use Pemm\Model\SmsProvider;

use Pemm\Core\Language;

class Support extends CoreController
{
    public function index()
    {
        try {

            /* @var Customer $customer */
            $customer = $this->container->get('customer');

            if ($this->request->isMethod('post')) {

                $subject = $this->request->request->get('subject');
                $text = $this->request->request->get('text');
                $vehicle = $this->request->request->get('vehicle');

                $cvehicleID = 0;
                if (isset($_POST['cvehicle_id'])) {
                    $cvehicleID = $_POST['cvehicle_id'];
                }

                if (!empty($subject) && !empty($text)) {
                    $support = new SupportModel();
                    $support
                        ->setReference($customer->getId() . Helper::generateRandomString(24))
                        ->setSubject($subject)
                        ->setCVId(strip_tags($cvehicleID))
                        ->setVehicle($vehicle)
                        ->setText($text)
                        ->setType('customer')
                        ->setCustomer($customer)
                        ->setCustomerRead(1)
                        ->setAdministratorRead(0)
                        ->setFirstQuestion(1)
                        ->setStatus('pending');

                    if (!empty($this->request->files->get('file'))) {
                        $file = $this->request->files->get('file');
                        $allowedExtensions = ['php', 'php7', 'php8', 'php5', 'php4'];
                        $extension = $file->getClientOriginalExtension();
                        if (!in_array($extension, $allowedExtensions)) {
                            $movedFile = $file->move(
                                $_SERVER['DOCUMENT_ROOT'] . '/files/',
                                'support-file-' . time() . '.' . $extension
                            );
                            $support->setFile($movedFile->getFilename());
                        } else {
                            // handle invalid file type error
                        }
                    }

                    $support->store();

                    $smsProvider = (new SmsProvider())->find(1);
                    $account_sid = trim($smsProvider->getToken());
                    if (!empty($account_sid)) {
                        (new Sms())->customerCreateSupport($text);
                    }
                    (new EmailNotification())->send('support', 'new', $support);

                    if ($support->getId()) {
                        (new RedirectResponse('/panel/ticket?ticket_id=' . $support->getId()))->send();
                    }
                }
            }

            $this->container->set(
                'page',
                (new Page())
                    ->setType('support')
                    ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                    ->setMetaTitle('Destek Talepleri - ' . $this->setting->getSiteName())
                    ->setMetaDescription('Destek Talepleri - ' . $this->setting->getDescription())
            );

        } catch (\Exception $exception) {
            print_r($exception);
            die;
        }

        View::render('customer', 'destek-talepleri', []);
    }

    public function detail()
    {
        /* @var Customer $customer */
        $customer = $this->container->get('customer');

        if ($this->request->isMethod('post')) {
            $subject = $this->request->request->get('subject');
            $text = strip_tags($this->request->request->get('text'));
            $vehicle = $this->request->request->get('vehicle');

            if (!empty($subject) && !empty($text)) {
                $support = new SupportModel();
                $cvehicleID = 0;
                if (isset($_POST['cvehicle_id'])) {
                    $cvehicleID = $_POST['cvehicle_id'];
                }

                $support
                    ->setReference($customer->getId() . Helper::generateRandomString(24))
                    ->setSubject($subject)
                    ->setCVId(strip_tags($cvehicleID)) // customer vehicle id
                    ->setVehicle($vehicle)
                    ->setText($text)
                    ->setType('customer')
                    ->setCustomer($customer)
                    ->setCustomerRead(1)
                    ->setAdministratorRead(0)
                    ->setFirstQuestion(1)
                    ->setStatus('pending');

                if (!empty($this->request->files->get('file'))) {
                        $file = $this->request->files->get('file');
                        $allowedExtensions = ['php', 'php7', 'php8', 'php5', 'php4'];
                        $extension = $file->getClientOriginalExtension();
                        if (!in_array($extension, $allowedExtensions)) {
                            $movedFile = $file->move(
                                $_SERVER['DOCUMENT_ROOT'] . '/files/',
                                'support-file-' . time() . '.' . $extension
                            );
                            $newSupportMessage->setFile($movedFile->getFilename());
                        } else {
                            // handle invalid file type error
                        }
                    }

                $support->store();

                (new Sms())->customerCreateSupport($text);
                (new EmailNotification())->send('support', 'new', $support);

                if ($support->getId()) {
                    (new RedirectResponse('/panel/ticket?ticket_id=' . $support->getId()))->send();
                }
            }
        } else {

            $this->container->set('supportType', 'customer');

            $ticket_id = $this->request->query->get('ticket_id');

            $support = (new SupportModel())->find($ticket_id);

            if (!empty($support) && $support->customer->getId() == $customer->getId()) {
                $support->read('customer');
                $this->container->set('support', $support);
            }


            View::render('customer', 'destek-detay', []);
        }

    }

    public function close()
    {
        $type = 'closed';

        /* @var Customer $customer */
        $customer = $this->container->get('customer');

        if (!empty($id = $this->route_params['id'])) {

            $support = (new SupportModel())->find($id);

            if (
                !empty($support) &&
                $support->customer->getId() == $customer->getId()
            ) {
                $support->setStatus('closed');
                $support->store();
            }

            (new RedirectResponse('/panel/support?type=closed&read=1'))->send();
        }
    }

    public function ajaxSupportDetail()
    {
        $id = $this->route_params['id'];

        /* @var Customer $customer */
        $customer = $this->container->get('customer');

        $this->container->set('supportType', 'customer');

        $support = (new SupportModel())->find($id);

        if (!empty($support) && $support->customer->getId() == $customer->getId()) {
            $support->read('customer');
            $this->container->set('support', $support);
            View::render('customer', 'ajax/support-detail', []);
        }
    }

    public function ajaxSendMessage()
    {
        try {

            $id = $this->route_params['id'];

            /* @var Customer $customer */
            $customer = $this->container->get('customer');

            $this->container->set('supportType', 'customer');

            $support = (new SupportModel())->find($id);

            if (!empty($support) && $support->customer->getId() == $customer->getId()) {
                $support->read('customer');
                $this->container->set('support', $support);

                if (
                    !empty($this->request->request->has('message')) ||
                    !empty($this->request->files->get('file'))
                ) {

                    $vehicle = $support->getVehicle();
                    $cvId = null;
                    if ($vehicle instanceof Vehicle) {
                        $cvId = $vehicle->getId();
                    }

                    $newSupportMessage = new SupportModel();
                    $newSupportMessage
                        ->setReference($support->getReference())
                        ->setCVId($cvId)
                        ->setVehicle($support->getVehicle())
                        ->setSubject('')
                        ->setType('customer')
                        ->setCustomer($customer)
                        ->setCustomerRead(1)
                        ->setAdministratorRead(0)
                        ->setFirstQuestion(0)
                        ->setStatus('pending');

                    $message = trim($this->request->request->get('message'));

                    if (!empty($message)) {
                        $message = strip_tags($message);
                    } else {
                        $message = $this->language::translate('File uploaded!');
                    }

                    $newSupportMessage->setText($message);

                    // if (!empty($this->request->files->get('file'))) {
                    //     $file = $this->request->files->get('file');
                    //     $movedFile = $file->move(
                    //         $_SERVER['DOCUMENT_ROOT'] . '/files/',
                    //         'support-file-' . time() . '.' . $file->getClientOriginalExtension()
                    //     );
                    //     $newSupportMessage->setFile($movedFile->getFilename());
                    // }

                    if (!empty($this->request->files->get('file'))) {
                        $file = $this->request->files->get('file');
                        $allowedExtensions = ['php', 'php7', 'php8', 'php5', 'php4'];
                        $extension = $file->getClientOriginalExtension();
                        if (!in_array($extension, $allowedExtensions)) {
                            $movedFile = $file->move(
                                $_SERVER['DOCUMENT_ROOT'] . '/files/',
                                'support-file-' . time() . '.' . $extension
                            );
                            $newSupportMessage->setFile($movedFile->getFilename());
                        } else {
                            // handle invalid file type error
                        }
                    }

                    $newSupportMessage->store();

                    (new Sms())->customerReplySupport($message);
                    (new EmailNotification())->send('support', 'reply', $newSupportMessage);

                    View::render('customer', 'ajax/support-detail', []);
                }

            }

        } catch (\Exception $exception) {
            print_r($exception);
        }
    }
}
