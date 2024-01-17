<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Pemm\Model\AdditionalOption as AdditionalOptionModel;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdditionalOption extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('additional-option-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Additional Options - ' . $this->setting->getSiteName())
                ->setMetaDescription('Additional Options - ' . $this->setting->getDescription())
        );

        View::render('admin','additional-option-list', []);
    }

    public function form()
    {
        if (!empty($id = @$this->route_params['id'])) {
            $this->container->set('detailId', $id);
        }

        $this->container->set('page',
            (new Page())
                ->setType('additional-option-detail')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Additional Option Detail - ' . $this->setting->getSiteName())
                ->setMetaDescription('Additional Option Detail - ' . $this->setting->getDescription())
        );

        View::render('admin','additional-option', []);

    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $additionalOptionList = [];

        $additionalOptionModel = new AdditionalOptionModel();
        $additionalOptions = $additionalOptionModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($additionalOptions)) {
            /* @var AdditionalOptionModel $additionalOption */
            foreach ($additionalOptions as $key => $additionalOption) {
                $additionalOptionList[$key] = [
                    'id' => $additionalOption->getId(),
                    'name' => $additionalOption->getName(),
                    'code' => $additionalOption->getCode(),
                    'is_active' => $additionalOption->getIsActive()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $additionalOptionModel->queryTotalCount,
            'recordsFiltered' => $additionalOptionModel->queryTotalCount,
            'data' => $additionalOptionList
        ]))->send();
    }
}
