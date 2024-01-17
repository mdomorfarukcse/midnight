<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\ExchangeRate as ExchangeRateModel;

class ExchangeRate extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('exchange-rate-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Exchange Rates - ' . $this->setting->getSiteName())
                ->setMetaDescription('Exchange Rates - ' . $this->setting->getDescription())
        );

        View::render('admin','exchange-rate-list', []);
    }

    public function form()
    {
        if (!empty($id = @$this->route_params['id'])) {
            $this->container->set('detailId', $id);
        }

        $this->container->set('page',
            (new Page())
                ->setType('exchange-rate')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Exchange Rate - ' . $this->setting->getSiteName())
                ->setMetaDescription('Exchange Rate - ' . $this->setting->getDescription())
        );

        View::render('admin','exchange-rate', []);

    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $exchangeRateList = [];

        $exchangeRateModel = new ExchangeRateModel();
        $exchangeRates = $exchangeRateModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($exchangeRates)) {
            /* @var ExchangeRateModel $exchangeRate */
            foreach ($exchangeRates as $key => $exchangeRate) {
                $exchangeRateList[$key] = [
                    'base' => $exchangeRate->getBase(),
                    'to_be_exchanged' => $exchangeRate->getToBeExchanged(),
                    'rate' => $exchangeRate->getRate(),
                    'status' => $exchangeRate->getStatus()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $exchangeRateModel->queryTotalCount,
            'recordsFiltered' => $exchangeRateModel->queryTotalCount,
            'data' => $exchangeRateList
        ]))->send();
    }
}
