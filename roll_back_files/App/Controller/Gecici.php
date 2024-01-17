<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Pemm\Model\Setting;
use Pemm\Model\Category;

class Gecici extends CoreController
{
    public function view()
    {

        $setting = $this->setting;

        $this->container->set('page',
            (new Page())
                ->setType('')
                ->setUrl('')
                ->setMetaTitle('')
                ->setMetaDescription('')
                ->setMetaKeywords('')
                ->setOgTitle('')
                ->setOgUrl('')
                ->setOgDescription('')
                ->setOgImage('')
        );

        View::render('customer',str_replace('/panel/', '', $this->request->getRequestUri()), []);
    }

 


    public function success()
    {
        View::render('customer','payment-success', []);
    }
    public function cancel()
    {
        View::render('customer','payment-failure', []);
    }

    public function info()
    {
        View::render('customer','payment-info', []);
    }
      public function about_us()
   {
       View::render('customer','about_us', []);
   }
   public function contact_us()
   {
       View::render('customer','contact_us', []);
   }
    public function imprint()
    {
        View::render('customer','imprint', []);
    }
    public function termsandconditions()
    {
        View::render('customer','terms-and-conditions', []);
    }
    public function privacypolicy()
    {
        View::render('customer','privacy-policy', []);
    }
    public function returnpoliti()
    {
        View::render('customer','return-policy', []);
    }
    public function delivery()
    {
        View::render('customer','delivery-information', []);
    }
}
