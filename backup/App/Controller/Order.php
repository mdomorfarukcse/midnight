<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Customer as CustomerModel;
use Pemm\Model\Page;
use Pemm\Model\Setting;
use Pemm\Model\Order as OrderModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Pemm\Model\Category;
use Symfony\Component\HttpFoundation\Session\Session;

class Order extends CoreController
{
    public function create()
    {

    }

    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('order-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Kredi Hareketleri - ' . $this->setting->getSiteName())
                ->setMetaDescription('Kredi Hareketleri - ' . $this->setting->getDescription())
        );

        View::render('customer','kredi-haraketleri', []);

    }
}
