<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Customer as CustomerModel;

class Customer extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('customer-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Customers - ' . $this->setting->getSiteName())
                ->setMetaDescription('Customers - ' . $this->setting->getDescription())
        );

        View::render('admin','customer-list', []);
    }

    public function form()
    {
        if (!empty($id = $this->route_params['id'])) {
            $this->container->set('detailId', $id);
        }

        $this->container->set('page',
            (new Page())
                ->setType('customer-form')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Customer - ' . $this->setting->getSiteName())
                ->setMetaDescription('Customer - ' . $this->setting->getDescription())
        );

        View::render('admin','customer', []);
    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $customerList = [];

        $customerModel = new CustomerModel();
        $customers = $customerModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($customers)) {
            /* @var CustomerModel $customer */
            foreach ($customers as $key => $customer) {
                $customerList[$key] = [
                    'id' => $customer->getId(),
                    'customer_group' => $customer->getCustomerGroup()->getName(),
                    'first_name' => $customer->getFirstName(),
                    'last_name' => $customer->getLastName(),
                    'email' => $customer->getEmail(),
                    'avatar' => $customer->getAvatar(true),
                    'contact_number' => $customer->getContactNumber(),
                    'credit' => $customer->getCredit(),
                    'allow_login' => $customer->getAllowLogin(true),
                    'status' => $customer->getStatus(true),
                    'ip' => $customer->getIp()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $customerModel->queryTotalCount,
            'recordsFiltered' => $customerModel->queryTotalCount,
            'data' => $customerList
        ]))->send();
    }
}
