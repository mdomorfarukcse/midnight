<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Setting as SettingModel;

class Setting extends CoreController
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

        View::render('admin','setting', []);
    }
    public function logo()
    {
        $this->container->set('page',
            (new Page())
                ->setType('setting')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Settings - ' . $this->setting->getSiteName())
                ->setMetaDescription('Settings - ' . $this->setting->getDescription())
        );

        View::render('admin','logo', []);
    }

    public function payment()
    {
        $this->container->set('page',
            (new Page())
                ->setType('setting')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Settings - ' . $this->setting->getSiteName())
                ->setMetaDescription('Settings - ' . $this->setting->getDescription())
        );

        View::render('admin','payment', []);
    }

    public function evc()
    {
        $this->container->set('page',
            (new Page())
                ->setType('setting')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Settings - ' . $this->setting->getSiteName())
                ->setMetaDescription('Settings - ' . $this->setting->getDescription())
        );

        View::render('admin','evc', []);
    }

    public function sms()
    {
        $this->container->set('page',
            (new Page())
                ->setType('setting')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Settings - ' . $this->setting->getSiteName())
                ->setMetaDescription('Settings - ' . $this->setting->getDescription())
        );

        View::render('admin','sms', []);
    }

    public function google()
    {
        $this->container->set('page',
            (new Page())
                ->setType('setting')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Settings - ' . $this->setting->getSiteName())
                ->setMetaDescription('Settings - ' . $this->setting->getDescription())
        );

        View::render('admin','google', []);
    }

    public function working()
    {
        $this->container->set('page',
            (new Page())
                ->setType('setting')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Settings - ' . $this->setting->getSiteName())
                ->setMetaDescription('Settings - ' . $this->setting->getDescription())
        );

        View::render('admin','working', []);
    }

    public function mail()
    {
        $this->container->set('page',
            (new Page())
                ->setType('setting')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Settings - ' . $this->setting->getSiteName())
                ->setMetaDescription('Settings - ' . $this->setting->getDescription())
        );

        View::render('admin','mail', []);
    }

    public function policies()
    {
        $this->container->set('page',
            (new Page())
                ->setType('setting')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Settings - ' . $this->setting->getSiteName())
                ->setMetaDescription('Settings - ' . $this->setting->getDescription())
        );

        View::render('admin','policies', []);
    }


}
