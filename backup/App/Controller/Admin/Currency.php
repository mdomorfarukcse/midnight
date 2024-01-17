<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Currency as CurrencyModel;

class Currency extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('currency-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Currencies - ' . $this->setting->getSiteName())
                ->setMetaDescription('Currencies - ' . $this->setting->getDescription())
        );

        View::render('admin','currency-list', []);
    }

    public function form()
    {
        if (!empty($id = @$this->route_params['id'])) {
            $this->container->set('detailId', $id);
        }

        $this->container->set('page',
            (new Page())
                ->setType('currency')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Currencies - ' . $this->setting->getSiteName())
                ->setMetaDescription('Currencies - ' . $this->setting->getDescription())
        );

        View::render('admin','currency', []);

    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $currencyList = [];

        $currencyModel = new CurrencyModel();
        $currencies = $currencyModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($currencies)) {
            /* @var CurrencyModel $currency */
            foreach ($currencies as $key => $currency) {
                $currencyList[$key] = [
                    'id' => $currency->getId(),
                    'code' => $currency->getCode(),
                    'name' => $currency->getName(),
                    'symbol' => $currency->getSymbol()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $currencyModel->queryTotalCount,
            'recordsFiltered' => $currencyModel->queryTotalCount,
            'data' => $currencyList
        ]))->send();
    }
}
