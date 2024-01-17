<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Helper;
use Pemm\Model\Page;
use Pemm\Model\TuningAdditionalOption;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\User as UserModel;
use Symfony\Component\HttpFoundation\JsonResponse;

class User extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('user-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Users - ' . $this->setting->getSiteName())
                ->setMetaDescription('Users - ' . $this->setting->getDescription())
        );

        View::render('admin','user-list', []);
    }

    public function form()
    {
        if (!empty($id = @$this->route_params['id'])) {
            $this->container->set('detailId', $id);
        }

        $this->container->set('page',
            (new Page())
                ->setType('user')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('User - ' . $this->setting->getSiteName())
                ->setMetaDescription('User - ' . $this->setting->getDescription())
        );

        View::render('admin','user', []);

    }

    public function registerIndex()
    {
        try {

            /* @var Session $session */
            $session = $this->container->get('session');

            if ($this->request->getMethod() == 'POST') {

                $firstName = $this->request->request->get('firstName');
                $lastName = $this->request->request->get('lastName');
                $email = $this->request->request->get('email');
                $contactNumber = $this->request->request->get('contactNumber');
                $password = $this->request->request->get('password');
                $terms = $this->request->request->get('terms');
                $csrfToken = $this->request->request->get('csrf_token');

                if (
                    empty($firstName) ||
                    empty($lastName) ||
                    empty($email) ||
                    empty($password) ||
                    ($csrfToken != $session->get('csrf_token'))
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
                    ->setPassword(password_hash($password, PASSWORD_DEFAULT))
                    ->setReference(Helper::generateRandomString(64));

                if (!$customer->save())
                    throw new \Exception($this->language::translate('An error occurred during the registration process, please try again.'));

                $customer->sendConfirmationMail();

                $session->getFlashBag()->add('success', $this->language::translate('A confirmation e-mail has been sent to your :email e-mail address. Please follow the instructions on your property!', ['email' => $email]));
                $session->remove('csrf_token');

            }

            Helper::csrfToken();

            $this->container->set('page',
                (new Page())
                    ->setType('register')
                    ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                    ->setMetaTitle('Üye Ol - ' . $this->setting->getSiteName())
                    ->setMetaDescription('Üye Ol - ' . $this->setting->getDescription())
            );

        } catch (\Exception $e) {
            $session->getFlashBag()->add('danger', $e->getMessage());
        }

        View::render('customer','uye-ol', []);

    }

    public function loginIndex()
    {
        try {

            /* @var Session $session */
            $session = $this->container->get('session');

            $user = $this->container->get('user');

            if (!empty($user))
                header('location: /admin');

            if ($this->request->isMethod('post')) {

                $email = $this->request->request->get('email');
                $password = $this->request->request->get('password');
                $csrfToken = $this->request->request->get('csrf_token');

                if (
                    empty($email) ||
                    empty($password)
                ) {
                    throw new \Exception($this->language::translate('You entered missing information'));
                }

                $user = (new UserModel())->findOneBy(['filter' => ['email' => $email]]);

                if (
                    empty($user) ||
                    !(password_verify($password, $user->getPassword()))
                ) {
                    throw new \Exception($this->language::translate('Username or password is incorrect!'));
                }

                if (!$user->getStatus()) {
                    throw new \Exception($this->language::translate('Account verification pending!'));
                }

                if (!$user->getAllowLogin()) {
                    throw new \Exception($this->language::translate('Your account has been suspended'));
                }

                $token = Helper::generateRandomString(64);

                $user
                    ->setIp($this->request->getClientIp())
                    ->setToken($token)
                    ->save();

                if ($this->request->request->has('remember_me'))
                    setcookie('user_token', $token, time() + (86400 * 1), '/');

                $user->session();
                $session->remove('csrf_token');

                header('location: /admin');
            }

            Helper::csrfToken();

            $this->container->set('page',
                (new Page())
                    ->setType('admin-login')
                    ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                    ->setMetaTitle('Admin Login - ' . $this->setting->getSiteName())
                    ->setMetaDescription('Admin Login - ' . $this->setting->getDescription())
            );

        } catch (\Exception $e) {
            $session->getFlashBag()->add('danger', $e->getMessage());
        }

        View::render('admin','login', []);

    }

    public function fileUpload()
    {
        try {

            /* @var Session $session */
            $session = $this->container->get('session');

            if ($this->request->getMethod() == 'POST') {

                header('location: /panel');
            }

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

    public function logout()
    {
        $session = $this->container->get('session');
        $session->remove('userLogin');
        $session->remove('userId');
        setcookie('user_token', '', time() + 1, '/');
        header('location: /admin/login');
    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $userList = [];

        $userModel = new UserModel();
        $users = $userModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($users)) {
            /* @var UserModel $user */
            foreach ($users as $key => $user) {
                $userList[$key] = [
                    'id' => $user->getId(),
                    'user_role' => $user->getUserRole(),
                    'first_name' => $user->getFirstName(),
                    'last_name' => $user->getLastName(),
                    'email' => $user->getEmail(),
                    'allow_login' => $user->getAllowLogin(),
                    'status' => $user->getStatus(),
                    'contact_number' => $user->getContactNumber(),
                    'avatar' => $user->getAvatar(),
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $userModel->queryTotalCount,
            'recordsFiltered' => $userModel->queryTotalCount,
            'data' => $userList
        ]))->send();
    }


}
