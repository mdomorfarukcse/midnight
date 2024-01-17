<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;

class Dashboard extends CoreController
{
    public function index()
    {
        $this->container->set('page',
            (new Page())
                ->setType('admin-dashboard')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle($this->setting->getSiteName())
                ->setMetaDescription($this->setting->getDescription())
        );

        View::render('admin','dashboard', []);

    }
}
