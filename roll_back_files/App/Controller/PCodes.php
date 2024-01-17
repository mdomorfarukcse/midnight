<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Customer;
use Pemm\Model\PCodes as PCodesModel;

class PCodes extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('p-codes')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('P Codes - ' . $this->setting->getSiteName())
                ->setMetaDescription('P Codes - ' . $this->setting->getDescription())
        );

        View::render('customer','p-codes', []);
    }

    public function ajaxListForDataTable()
    {
        /* @var Customer $customer */
        $customer = $this->container->get('customer');
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
                    'code' => $pCode->getCode(),
                    'description' => $pCode->getDescription()
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
