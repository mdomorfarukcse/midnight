<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Customer;
use Pemm\Model\EmailNotification;
use Pemm\Model\Helper;
use Pemm\Model\Page;
use Pemm\Model\Support as SupportModel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Pemm\Controller\Sms;

use Pemm\Core\Language;

class Support extends CoreController
{
    public function index()
    {
        try {

            $user = $this->container->get('user');

            if ($this->request->isMethod('post')) {

                $subject = $this->request->request->get('subject');
                $text = $this->request->request->get('text');
                $vehicleId = $this->request->request->getInt('vehicle');
                $customerId = $this->request->request->getInt('customer');

                if (!empty($customerId) && !empty($subject) && !empty($text)) {
                    $customer = (new Customer())->find($customerId);

                    if (!empty($customer)) {
                        $vehicle = (new Customer())->find($vehicleId);

                        $support = new SupportModel();
                        $support
                            ->setReference($customer->getId() . Helper::generateRandomString(24))
                            ->setSubject($subject)
                            ->setVehicle($vehicleId)
                            ->setText($text)
                            ->setType('admin')
                            ->setAdmin($user)
                            ->setCustomer($customer)
                            ->setCustomerRead(0)
                            ->setAdministratorRead(1)
                            ->setFirstQuestion(1)
                            ->setStatus('pending');

                        if (!empty($this->request->files->get('file'))) {
                            $file = $this->request->files->get('file');
                            $movedFile = $file->move(
                                $_SERVER['DOCUMENT_ROOT'] . '/files/',
                                'support-file-' . time() . '.' . $file->getClientOriginalExtension()
                            );
                            $support->setFile($movedFile->getFilename());
                        }

                        $support->store();

                        //(new EmailNotification())->send('support', 'new', $support);

                        if ($support->getId()) {
                            (new RedirectResponse('/admin/ticket?ticket_id=' . $support->getId()))->send();
                        }
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

        View::render('admin', 'supports', []);
    }

    public function detail()
    {
        $this->container->set('supportType', 'admin');

        $user = $this->container->get('user');

        if ($this->request->isMethod('post')) {

            $subject = $this->request->request->get('subject');
            $text = $this->request->request->get('text');
            $vehicleId = $this->request->request->getInt('vehicle');
            $customerId = $this->request->request->getInt('customer');

            if (!empty($customerId) && !empty($subject) && !empty($text)) {

                $customer = (new Customer())->find($customerId);

                if (!empty($customer)) {

                    $vehicle = (new Customer())->find($vehicleId);

                    $support = new SupportModel();
                    $support
                        ->setReference($customer->getId() . Helper::generateRandomString(24))
                        ->setSubject($subject)
                        ->setVehicle($vehicleId)
                        ->setText($text)
                        ->setType('admin')
                        ->setAdmin($user)
                        ->setCustomer($customer)
                        ->setCustomerRead(0)
                        ->setAdministratorRead(1)
                        ->setFirstQuestion(1)
                        ->setStatus('pending');

                    if (!empty($this->request->files->get('file'))) {
                        $file = $this->request->files->get('file');
                        $movedFile = $file->move(
                            $_SERVER['DOCUMENT_ROOT'] . '/files/',
                            'support-file-' . time() . '.' . $file->getClientOriginalExtension()
                        );
                        $support->setFile($movedFile->getFilename());
                    }

                    $support->store();

                    //(new EmailNotification())->send('support', 'new', $support);

                    if ($support->getId()) {
                        (new RedirectResponse('/admin/ticket?ticket_id=' . $support->getId()))->send();
                    }
                }

            }
        } else {

            $ticket_id = $this->request->query->get('ticket_id');

            $support = (new SupportModel())->find($ticket_id);

            $support->read('admin');
            $this->container->set('support', $support);

            View::render('admin', 'support-detail', []);

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

            $this->container->set('supportType', 'admin');

            $user = $this->container->get('user');

            $support = (new SupportModel())->find($id);

            if (!empty($support)) {

                $support->read('admin');
                $support->setStatus('answered');

                $this->container->set('support', $support);

                if (
                    !empty($this->request->request->has('message')) ||
                    !empty($this->request->files->get('file'))
                ) {

                    $newSupportMessage = new SupportModel();
                    $newSupportMessage
                        ->setReference($support->getReference())
                        ->setVehicle($support->getVehicle())
                        ->setSubject('')
                        ->setType('admin')
                        ->setCustomer($support->getCustomer())
                        ->setAdmin($user)
                        ->setCustomerRead(0)
                        ->setAdministratorRead(1)
                        ->setFirstQuestion(0)
                        ->setStatus('answered');

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

                    (new EmailNotification())->send('support', 'reply', $newSupportMessage);
                    (new Sms(false))->adminReplySupport($support->getCustomer()->getId(), $message);

                    View::render('customer', 'ajax/support-detail', []);
                }
            }
        } catch (\Exception $exception) {
            print_r($exception);
        }
    }

    /**
     * @return void
     */
    public function close()
    {
        $type = 'closed';

        if (!empty($id = $this->route_params['id'])) {

            $support = (new SupportModel())->find($id);

            if (
                !empty($support)
            ) {
                $support->setStatus('closed');
                $support->store();
            }

            (new RedirectResponse('/admin/support?type=closed'))->send();
        }
    }
}
