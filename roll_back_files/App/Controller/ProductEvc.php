<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Pemm\Model\Setting;
use Pemm\Model\ProductEvc as ProductModel;
use Pemm\Model\ProductExtra;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Pemm\Model\Category;

class ProductEvc extends CoreController
{
    public function index()
    {
        try {

            if (empty($this->route_params['slug']))
                throw new \Exception('Not found');

            $products = (new ProductModel())
                ->setFilterParams([
                    'filter' => [
                        'slug' => trim($this->route_params['slug']),
                        'status' => 1
                    ]
                ])
                ->filter();

            if(empty($products))
                throw new \Exception('Not found');

            $this->container->set('category', new Category());

            $product = (new ProductModel())->build((object) $products[0]);

            $product->getAttributes(true);
            if (!empty($this->request->query->has('varyant'))) {
                $product->selectAttribute($this->request->query->getInt('varyant'));
            }

            $product->getCampaigns();
            $product->increaseHit();

            $this->container->set('product', $product);

            $this->container->set('page',
                (new Page())
                    ->setType('product')
                    ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                    ->setMetaTitle($product->getSeoTitle())
                    ->setMetaDescription($product->getSeoDescription())
                    ->setMetaKeywords($product->getTitle())
                    ->setOgTitle($product->getSeoTitle())
                    ->setOgUrl($product->getUrl(true))
                    ->setOgDescription($product->getSeoDescription())
                    ->setOgImage($product->getImageUrl($product->getImage()), true)
                    ->business()
                    ->service()
                    ->keyword()
                    ->keywords()
                    ->query()
            );

        } catch (\Exception $e) {
            print_r($e);die;
            return (new RedirectResponse($this->setting::get('url')))->send();
        }

        View::render('customer','product_evc', []);

    }
}
