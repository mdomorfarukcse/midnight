<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\CustomerGroup as CustomerGroupModel;

class CustomerGroup extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('customer-group-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Customers - ' . $this->setting->getSiteName())
                ->setMetaDescription('Customers - ' . $this->setting->getDescription())
        );

        View::render('admin','customer-group-list', []);
    }

    public function form()
    {
        if (!empty($this->route_params['id'])) {
            $this->container->set('detailId', $this->route_params['id']);
        }

        $this->container->set('page',
            (new Page())
                ->setType('customer-form')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Customer - ' . $this->setting->getSiteName())
                ->setMetaDescription('Customer - ' . $this->setting->getDescription())
        );

        View::render('admin','customer-group', []);
    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $customerList = [];
        $customerGroupModel = new CustomerGroupModel();
        $customerGroups = $customerGroupModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($customerGroups)) {
            /* @var CustomerGroupModel $customerGroups */
            foreach ($customerGroups as $key => $customerGroup) {
                $customerGroupList[$key] = [
                    'id' => $customerGroup->getId(),
                    'code' => $customerGroup->getCode(),
                    'name' => $customerGroup->getName(),
                    'process_type' => $customerGroup->getProcessType(),
                    'multiplier' => $customerGroup->getMultiplier(),
                    'status' => $customerGroup->getStatus()                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $customerGroupModel->queryTotalCount,
            'recordsFiltered' => $customerGroupModel->queryTotalCount,
            'data' => $customerGroupList
        ]))->send();
    }
}
