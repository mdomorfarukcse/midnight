<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Product as ProductModel;

class Product extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('product-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Products - ' . $this->setting->getSiteName())
                ->setMetaDescription('Products - ' . $this->setting->getDescription())
        );

        View::render('admin','product-list', []);
    }

    public function form()
    {
        if (!empty($id = @$this->route_params['id'])) {
            $this->container->set('detailId', $id);
        }

        $this->container->set('page',
            (new Page())
                ->setType('product')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Products - ' . $this->setting->getSiteName())
                ->setMetaDescription('Products - ' . $this->setting->getDescription())
        );

        View::render('admin','product', []);

    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $productList = [];

        $productModel = new ProductModel();
        $products = $productModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($products)) {
            /* @var ProductModel $product */
            foreach ($products as $key => $product) {
                $productList[$key] = [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'credit' => $product->getCredit(),
                    'price' => $product->getPrice(),
                    'currency' => $product->getCurrency(),
                    'tax_rate' => $product->getTaxRate(),
                    'status' => $product->getStatus(),
                    'sort_order' => $product->getSortOrder(),
                    'discount_status' => $product->getDiscountStatus(),
                    'discounted_price' => $product->getDiscountedPrice()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $productModel->queryTotalCount,
            'recordsFiltered' => $productModel->queryTotalCount,
            'data' => $productList
        ]))->send();
    }
}
