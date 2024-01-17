<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Pemm\Model\Setting;
use Pemm\Model\ProductEvc as Product;
use Pemm\Model\Tuning;
use Pemm\Model\TuningAdditionalOption;
use Pemm\Model\Vehicle as VehicleModel;
use Symfony\Component\HttpFoundation\JsonResponse;

class PriceEvc extends CoreController
{
    public function index()
    {

    }

    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('price-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Evc Fiyat Listesi - ' . $this->setting->getSiteName())
                ->setMetaDescription($this->setting->getDescription())
        );

        View::render('customer','evc-fiyat-listesi', []);
    }

    public function api()
    {
      header('Access-Control-Allow-Origin: *');
        $json = [];


        $products = (new Product())->findBy(["order"=>["field"=>"sort_order","sort"=>"asc"]]);
        foreach ($products as $product) {
          if($product->getStatus()==0) continue;

          $json['products'][] = ['name' => $product->getName(), 'credit' => $product->getCredit(), 'price' => $product->getPrice(false, true, true, false)];

          }

          $tunings = (new Tuning())->findBy(['filter' => ['is_active' => 1]]);

          foreach ($tunings as $tuning) {
            $options = [];
            foreach ($tuning->getOptions() as $tuningAdditionalOption) {
                if (!$tuningAdditionalOption->isActive()) continue;
                  $options[] = ['id'=> $tuningAdditionalOption->getId(), 'name' => $tuningAdditionalOption->additionalOption->getName(), 'credit' => $tuningAdditionalOption->getCredit()];
              }
              $json['tunings'][] = [
                'id' => $tuning->getId(),
                'name' => $tuning->getName(),
                'credit' => $tuning->getCredit(),
                'options' => $options,
              ];
          }

        echo json_encode($json);
    }
}
