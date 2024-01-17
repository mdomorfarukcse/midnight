<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Pemm\Model\Setting;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Customer as CustomerModel;
use Symfony\Component\HttpFoundation\JsonResponse;

class Campaign extends CoreController
{
    public function ajaxApplyCoupon()
    {
        try {

            /* @var CustomerModel $customer $*/
            $customer = $this->container->get('customer');

            /* @var Session $session $*/
            $session = $this->container->get('session');

            if ($this->request->request->has('coupon')) {
                $campaing = $session->get('campaing');
                $campaing['discount_coupon']['code'] = $this->request->request->get('coupon');
                $session->set('campaing', $campaing);
            }

        } catch (\Exception $e) {
            return (new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]))->send();
        }

        return (new JsonResponse([
            'success' => true,
            'message' => 'Başarılı!..',
        ]))->send();

    }
}
