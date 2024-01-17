<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Pemm\Model\PCodes as PCodesModel;
use Symfony\Component\HttpFoundation\JsonResponse;

class PCodes extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('p-codes-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('P Codes - ' . $this->setting->getSiteName())
                ->setMetaDescription('P Codes - ' . $this->setting->getDescription())
        );

        View::render('admin','p-codes-list', []);
    }

    public function form()
    {
        if (!empty($id = @$this->route_params['id'])) {
            $this->container->set('detailId', $id);
        }

        $this->container->set('page',
            (new Page())
                ->setType('p-codes')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('P Codes - ' . $this->setting->getSiteName())
                ->setMetaDescription('P Codes - ' . $this->setting->getDescription())
        );

        View::render('admin','p-codes', []);

    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $pCodesList = [];

        $pCodesModel = new PCodesModel();
        $pCodes = $pCodesModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($pCodes)) {
            /* @var PCodesModel $pCode */
            foreach ($pCodes as $key => $pCode) {
                $pCodesList[$key] = [
                    'id' => $pCode->getId(),
                    'code' => $pCode->getCode(),
                    'description' => $pCode->getDescription(),
                    'is_active' => $pCode->getIsActive()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $pCodesModel->queryTotalCount,
            'recordsFiltered' => $pCodesModel->queryTotalCount,
            'data' => $pCodesList
        ]))->send();
    }
}
