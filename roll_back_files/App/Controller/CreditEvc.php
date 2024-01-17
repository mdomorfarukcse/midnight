<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;

class CreditEvc extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('credit-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Evc Kredi Yükle - ' . $this->setting->getSiteName())
                ->setMetaDescription('Evc Kredi Yükle - ' . $this->setting->getDescription())
        );

        View::render('customer','evc-kredi-yukle', []);

    }
}
