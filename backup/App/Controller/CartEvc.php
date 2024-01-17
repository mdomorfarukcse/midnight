<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Pemm\Model\Setting;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Pemm\Model\Customer;
use Pemm\Model\CartEvc as CartModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\ProductEvc;

class CartEvc extends CoreController
{
    public function index()
    {
        try {

            /* @var Customer $customer */
            $customer = $this->container->get('customer');

            $this->container->set('page',
                (new Page())
                    ->setType('cart')
                    ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                    ->setMetaTitle('My Evc Cart - ' . $this->setting->getSiteName())
                    ->setMetaDescription('My Evc Cart - ' . $this->setting->getDescription())
            );

        } catch (\Exception $e) {}

        View::render('customer','evc-sepetim', []);
    }

    public function checkout()
    {
        try {

            $customer = $this->container->get('customer');

            print_r($this->request->request->all());die;

        } catch (\Exception $e) {}

        View::render('customer','', []);

    }

    public function complete()
    {


    }

    public function deleteItem()
    {
        try {

            if (empty($cartId = $this->route_params['cartId'])) {
                throw new \Exception('');
            }

            /* @var CustomerModel $customer */
            $customer = $this->container->get('customer');

            $cart = (new CartModel())->find($cartId);

            if (
                empty($cart) ||
                (!$customer->isLogin() && $cart->getToken() != $customer->getCode()) ||
                ($customer->isLogin() && $cart->getCustomer()->getId() != $customer->getId())
            ) {
                throw new \Exception('');
            }

            $cart->delete();

        } catch (\Exception $e) {}

        return (new RedirectResponse($this->setting::get('url') . '/sepet'))->send();
    }

    public function empty()
    {
        try {

            /* @var CustomerModel $customer */
            $customer = $this->container->get('customer');
            $customer->emptyCart();

        } catch (\Exception $e) {}

        return (new RedirectResponse($this->setting::get('url') . '/sepet'))->send();
    }

    public function ajaxAddToCart()
    {
        try {

            if (
                empty($productId = $this->request->request->get('productId')) ||
                empty($quantity = $this->request->request->get('quantity'))
            ) throw new \Exception('Eksik parametre');

            /* @var CustomerModel $customer */
            $customer = $this->container->get('customer');

            $cart = (new CartModel)->findOneBy(['filter' => ['product_id' => $productId, 'customer_id' => $customer->getId()]]);

            if (empty($cart)) {
                $cart = (new CartModel())
                    ->setProduct((new ProductEvc())->find($productId))
                    ->setCustomer($customer)
                    ->setQuantity($quantity);
            } else {
                $cart->setQuantity($quantity);
            }

            $cart->store();


        } catch (\Exception $e) {
            print_r($e);die;
            return (new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ])
            )->send();
        }

        return (new JsonResponse([
            'success' => true])
        )->send();

    }

    public function ajaxDeleteCart()
    {
        try {

            /* @var CustomerModel $customer */
            $customer = $this->container->get('customer');

            if (
                empty($cartId = $this->request->request->get('cartId'))
            ) throw new \Exception('Eksik parametre');

            $cart = (new CartModel)->find($cartId);

            if (empty($cart))
                throw new \Exception('Cart mevcut değil');

            if ($cart->getCustomer()->getId() != $customer->getId())
                throw new \Exception('Yetkisiz işlem');

            $cart->remove();

        } catch (\Exception $e) {
            return (new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ])
            )->send();
        }

        return (new JsonResponse([
            'success' => true])
        )->send();

    }
}
