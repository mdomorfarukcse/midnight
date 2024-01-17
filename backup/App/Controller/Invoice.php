<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Customer;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Invoice as InvoiceModel;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Invoice extends CoreController
{
    public function detail()
    {
        if (empty($id = $this->route_params['id'])) {
            return new RedirectResponse('/panel');
        }

        /* @var Customer $customer */
        $customer = $this->container->get('customer');

        $invoice = (new InvoiceModel())->find($id);

        if (empty($invoice) || $invoice->getCustomerId() != $customer->getId()) {
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

        View::render('customer','invoice-detail', []);
    }
}
