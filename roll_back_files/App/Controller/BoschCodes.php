<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Customer;
use Pemm\Model\BoschCodes as BoschCodesModel;

class BoschCodes extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('bosch-codes')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Bosch Codes - ' . $this->setting->getSiteName())
                ->setMetaDescription('Bosch Codes - ' . $this->setting->getDescription())
        );

        View::render('customer','bosch-codes', []);
    }

    public function ajaxListForDataTable()
    {
        /* @var Customer $customer */
        $customer = $this->container->get('customer');
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $boschCodesList = [];

        $filter['is_active'] = 1;
        $boschCodesModel = new BoschCodesModel();
        $boschCodes = $boschCodesModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($boschCodes)) {
            /* @var BoschCodesModel $boschCode */
            foreach ($boschCodes as $key => $boschCode) {
                $boschCodesList[$key] = [
                    'manufacturer_number' => $boschCode->getManufacturerNumber(),
                    'ecu_brand' => $boschCode->getEcuBrand(),
                    'ecu' => $boschCode->getEcu()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $boschCodesModel->queryTotalCount,
            'recordsFiltered' => $boschCodesModel->queryTotalCount,
            'data' => $boschCodesList
        ]))->send();
    }

}
