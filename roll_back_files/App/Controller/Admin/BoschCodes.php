<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\BoschCodes as BoschCodesModel;

class BoschCodes extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('bosch-codes-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Bosch Codes - ' . $this->setting->getSiteName())
                ->setMetaDescription('Bosch Codes - ' . $this->setting->getDescription())
        );

        View::render('admin','bosch-codes-list', []);
    }

    public function form()
    {
        if (!empty($id = @$this->route_params['id'])) {
            $this->container->set('detailId', $id);
        }

        $this->container->set('page',
            (new Page())
                ->setType('bosch-codes')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Bosch Codes - ' . $this->setting->getSiteName())
                ->setMetaDescription('Bosch Codes - ' . $this->setting->getDescription())
        );

        View::render('admin','bosch-codes', []);

    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $boschCodesList = [];

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
                    'id' => $boschCode->getId(),
                    'manufacturer_number' => $boschCode->getManufacturerNumber(),
                    'ecu_brand' => $boschCode->getEcuBrand(),
                    'ecu' => $boschCode->getEcu(),
                    'is_active' => $boschCode->getIsActive(),
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
