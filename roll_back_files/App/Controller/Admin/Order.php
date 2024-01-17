<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Order as OrderModel;
use Pemm\Model\Customer as CustomerModel;

use Symfony\Component\HttpFoundation\RedirectResponse;

class Order extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('order-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Purchases - ' . $this->setting->getSiteName())
                ->setMetaDescription('Purchases - ' . $this->setting->getDescription())
        );

        View::render('admin','purchases', []);

    }

    public function form()
    {
        if (empty($id = $this->route_params['id'])) {
            return new RedirectResponse('/admin');
        }

        $this->container->set('detailId', $id);

        $this->container->set('page',
            (new Page())
                ->setType('order-detail')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Order Detail - ' . $this->setting->getSiteName())
                ->setMetaDescription('Order Detail - ' . $this->setting->getDescription())
        );

        View::render('admin','order', []);

    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $orderList = [];


        $orderModel = new OrderModel();
        $orders = $orderModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($orders)) {
            /* @var OrderModel $order */
            foreach ($orders as $key => $order) {

                $customerModel = new CustomerModel();
                $customer = $customerModel->find(["id"=>$order->getCustomerId()]);


                $orderList[$key] = [
                    'id' => $order->getId(),
                    'customer_id' => $order->getCustomerId(),
                    'customer_full_name' => $customer->getFirstName()." ".$customer->getLastName(),
                    'customer_ip' => $order->getCustomerIp(),
                    'items_total' => $order->getItemsTotal(),
                    'total' => $order->getTotal(),
                    'tax_amount' => $order->getTaxAmount(),
                    'currency' => $order->getCurrency(),
                    /*
                   'notes' => $order->getNotes(),

                    */
                    'state' => $order->getState(),
                   'total_credit' => $order->getTotalCredit(),
                   'payment_type' => $order->getPaymentType(),
                   'payment_status' => $order->getPaymentStatus(),
                   'payment_error' => $order->getPaymentError(),

             'country' => $order->getCountry(), /*
             'city' => $order->getCity(),
             'address' => $order->getAddress()
             */
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $orderModel->queryTotalCount,
            'recordsFiltered' => $orderModel->queryTotalCount,
            'data' => $orderList
        ]))->send();
    }
}
