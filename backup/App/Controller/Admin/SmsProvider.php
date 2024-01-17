<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\SmsProvider as SmsProviderModel;

class SmsProvider extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('sms-provider-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Customers - ' . $this->setting->getSiteName())
                ->setMetaDescription('Customers - ' . $this->setting->getDescription())
        );

        View::render('admin','sms-provider-list', []);
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

        View::render('admin','sms-provider', []);
    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $customerList = [];
        $SmsProviderModel = new SmsProviderModel();
        $SmsProviders = $SmsProviderModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($SmsProviders)) {
            /* @var CustomerGroupModel $customerGroups */
            foreach ($SmsProviders as $key => $SmsProvider) {
                $SmsProviderList[$key] = [
                    'id' => $SmsProvider->getId(),
                     'name' => $SmsProvider->getName(),
                     'header' => $SmsProvider->getHeader(),
                     'token' => $SmsProvider->getToken(),
                     'token2' => $SmsProvider->getToken2(),
                     'createdAt' => $SmsProvider->getCreatedAt(),
                     'updatedAt' => $SmsProvider->getUpdatedAt(),
                     'status' => $SmsProvider->getStatus()
                   ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $SmsProviderModel->queryTotalCount,
            'recordsFiltered' => $SmsProviderModel->queryTotalCount,
            'data' => $SmsProviderList
        ]))->send();
    }
}
