<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Pemm\Model\Setting;
use Pemm\Model\Category;

class Home extends CoreController
{
    public function index()
    {
        $this->container->set('page',
            (new Page())
                ->setType('home')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle($this->setting->getSiteName())
                ->setMetaDescription($this->setting->getDescription())
        );

        View::render('customer','home', []);

    }
}
