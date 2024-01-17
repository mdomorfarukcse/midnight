<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Invoice as InvoiceModel;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Invoice extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('invoice-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Invoices - ' . $this->setting->getSiteName())
                ->setMetaDescription('Invoices - ' . $this->setting->getDescription())
        );

        View::render('admin','invoice-list', []);
    }

    public function detail()
    {
        if (empty($id = $this->route_params['id'])) {
            return new RedirectResponse('/panel');
        }

        $invoice = (new InvoiceModel())->find($id);

        if (empty($invoice)) {
            return new RedirectResponse('/panel');
        }

        $this->container->set('invoice', $invoice);

        $this->container->set('page',
            (new Page())
                ->setType('invoice-detail')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Invoice Detail - ' . $this->setting->getSiteName())
                ->setMetaDescription('Invoice Detail - ' . $this->setting->getDescription())
        );

        View::render('admin','invoice-detail', []);
    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $invoiceList = [];

        $invoiceModel = new InvoiceModel();
        $invoices = $invoiceModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($invoices)) {
            /* @var InvoiceModel $invoice */
            foreach ($invoices as $key => $invoice) {
                if(($invoice->getCustomerId()>0)) {
                    $invoiceList[$key] = [
                        'id' => $invoice->getId(),
                        'number' => $invoice->getNumber(),
                        'customer_id' => $invoice->getCustomerId(),
                        'customer_full_name' => $invoice->getCustomer()->getFullName(),
                        'order_id' => $invoice->getOrder(),
                        'order_number' => $invoice->order->getNumber(),
                        'file' => $invoice->getFile(),
                        'status' => $invoice->getStatus()
                    ];
                }

            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $invoiceModel->queryTotalCount,
            'recordsFiltered' => $invoiceModel->queryTotalCount,
            'data' => $invoiceList
        ]))->send();
    }
}
