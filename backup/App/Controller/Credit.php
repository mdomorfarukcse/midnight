<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;

class Credit extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('credit-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Kredi Yükle - ' . $this->setting->getSiteName())
                ->setMetaDescription('Kredi Yükle - ' . $this->setting->getDescription())
        );

        View::render('customer','kredi-yukle', []);

    }
}
