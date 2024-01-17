<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Setting as SettingModel;

class Reports extends CoreController
{
    public function general()
    {
        $this->container->set('page',
            (new Page())
                ->setType('setting')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Settings - ' . $this->setting->getSiteName())
                ->setMetaDescription('Settings - ' . $this->setting->getDescription())
        );

        View::render('admin','reports', []);
    }

}
