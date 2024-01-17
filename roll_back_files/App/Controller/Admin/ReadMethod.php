<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\ReadMethod as ReadMethodModel;

class ReadMethod extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('read-method-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Read Methods - ' . $this->setting->getSiteName())
                ->setMetaDescription('Read Methods - ' . $this->setting->getDescription())
        );

        View::render('admin','read-method-list', []);
    }

    public function form()
    {
        if (!empty($id = @$this->route_params['id'])) {
            $this->container->set('detailId', $id);
        }

        $this->container->set('page',
            (new Page())
                ->setType('read-method')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Read Method - ' . $this->setting->getSiteName())
                ->setMetaDescription('Read Method - ' . $this->setting->getDescription())
        );

        View::render('admin','read-method', []);

    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $readMethodList = [];

        $readMethodModel = new ReadMethodModel();
        $readMethods = $readMethodModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($readMethods)) {
            /* @var ReadMethodModel $readMethod */
            foreach ($readMethods as $key => $readMethod) {
                $readMethodList[$key] = [
                    'id' => $readMethod->getId(),
                    'name' => $readMethod->getName(),
                    'surname' => $readMethod->getSurname(),
                    'image' => $readMethod->getImage(true),
                    'is_active' => $readMethod->getIsActive()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $readMethodModel->queryTotalCount,
            'recordsFiltered' => $readMethodModel->queryTotalCount,
            'data' => $readMethodList
        ]))->send();
    }
}
