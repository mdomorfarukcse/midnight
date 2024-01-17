<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Cart as CartModel;
use Pemm\Model\EmailNotification;
use Pemm\Model\Helper;
use Pemm\Model\Page;
use Pemm\Model\CustomerVehicle as CustomerVehicleModel;
use Pemm\Model\Product;
use Pemm\Model\VehicleAdditionalOption;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Customer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Pemm\Controller\Sms;

class CustomerVehicle extends CoreController
{

    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('customer-vehicle-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Customer Vehicles - ' . $this->setting->getSiteName())
                ->setMetaDescription('Customer Vehicles - ' . $this->setting->getDescription())
        );

        View::render('admin','customer-vehicle-list', []);
    }

    public function form()
    {
        if (empty($id = $this->route_params['id'])) {
            return new RedirectResponse('/admin');
        }

        $this->container->set('detailId', $id);

        $this->container->set('page',
            (new Page())
                ->setType('customer-vehicle-form')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Customer Vehicle - ' . $this->setting->getSiteName())
                ->setMetaDescription('Customer Vehicle - ' . $this->setting->getDescription())
        );

        View::render('admin','customer-vehicle', []);
    }

    public function delete()
    {
        if (empty($id = $this->route_params['id'])) {
            return new RedirectResponse('/admin');
        }

        $this->container->set('detailId', $id);

        $customerVehicleModel = new CustomerVehicleModel();
        $customerVehicles = $customerVehicleModel->find($id);
        if (!empty($customerVehicles)) {
            $customerVehicles
                ->setDeleted(1)
                ->store();
        }

        header('location: /admin/customer/vehicle/list');
    }

    public function ajaxListForDatatable()
    {

        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $customerVehicleList = [];

        $filter['deleted'] = 0;

        $customerVehicleModel = new CustomerVehicleModel();
        $customerVehicles = $customerVehicleModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($customerVehicles)) {
            /* @var CustomerVehicleModel $customerVehicle */
            foreach ($customerVehicles as $key => $customerVehicle) {
                $options = [];
                if (!empty($customerVehicle->vehicleAdditionalOptions)) {
                    /* @var VehicleAdditionalOption $vehicleTuningAdditionalOption */
                    foreach ($customerVehicle->vehicleAdditionalOptions as $_key => $vehicleTuningAdditionalOption) {
                        $options[$_key] = $vehicleTuningAdditionalOption->additionalOption->getName();
                    }
                }

                $customerVehicleList[$key] = [
                    'id' => $customerVehicle->getId(),
                    'vehicle_id' => $customerVehicle->getVehicle(),
                    'vehicle_full_name' => ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('vehicle_full_name') : $customerVehicle->vehicle->getFullName()),
                    'status' => $customerVehicle->getStatus(),
                    'model' => $customerVehicle->getModel(),
                    'manufacturer' => $customerVehicle->getManufacturer(),
                    'kilometer' => $customerVehicle->getKilometer(),
                    'gear' => $customerVehicle->getGear(),
                    'torque' => $customerVehicle->getTorque(),
                    'power' => $customerVehicle->getPower(),
                    'vehicle_registration' => $customerVehicle->getVehicleRegistration(),
                    'reading_device' => $customerVehicle->getReadingDevice(),
                    'master_slave' => $customerVehicle->getMasterSlave(),
                    'file_time' => $customerVehicle->getFileTime(),
                    'reading_type' => $customerVehicle->getReadingType(),
                    'tuning' => $customerVehicle->getTuning(),
                    'options' => $options,
                    'equipment' => $customerVehicle->getEquipment(),
                    'software' => $customerVehicle->getSoftware(),
                    'note' => $customerVehicle->getNote(),
                    'ecu_file' => $customerVehicle->getEcuFile(),
                    'log_file' => $customerVehicle->getLogFile(),
                    'id_file' => $customerVehicle->getIdFile(),
                    'dyno_file' => $customerVehicle->getDynoFile(),
                    'total_credit' => $customerVehicle->getTotalCredit(),
                    'customer_id' => $customerVehicle->getCustomerId(),
                    'customer_full_name' => (!empty($customer = $customerVehicle->getCustomer()) ? $customer->getFullName() : ''),
                    'ecu' => $customerVehicle->getEcu(),
                    'system_ecu_file' => $customerVehicle->getSystemEcuFile(),
                    'system_id_file' => $customerVehicle->getSystemIdFile(),
                    'system_log_file' => $customerVehicle->getSystemLogFile(),
                    'system_dyno_file' => $customerVehicle->getSystemDynoFile(),
                    'tuning_name' => $customerVehicle->vehicleTuning->getName()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $customerVehicleModel->queryTotalCount,
            'recordsFiltered' => $customerVehicleModel->queryTotalCount,
            'data' => $customerVehicleList
        ]))->send();
    }

    public function changeCredit()
    {
        if (empty($id = $this->route_params['id'])) {
            (new RedirectResponse('/admin'))->send();
        }

        if (empty($newCredit = $this->request->request->get('new-credit'))) {
            (new RedirectResponse('/admin'))->send();
        }

        $customerVehicleModel = new CustomerVehicleModel();
        $customerVehicles = $customerVehicleModel->find($id);

        if (empty($customerVehicles)) {
            (new RedirectResponse('/admin'))->send();
        }

        $customer = $customerVehicles->getCustomer();

        $totalCredit = $customerVehicles->getTotalCredit();

        if ($newCredit <= $totalCredit) {
            $this->container->get('session')->getFlashBag()->add('danger', $this->language::translate('New credit can be a minimum of ' . $totalCredit));
            (new RedirectResponse('/admin/customer/vehicle/detail/' . $customerVehicles->getId()))->send();
        }

        $diff = $newCredit-$totalCredit;
        $customerCredit = $customer->getCredit();
        $beforeStatus = $customerVehicles->getStatus();

        if ($diff <= $customerCredit) {

            try {
                $customerVehicles->setTotalCredit($newCredit);
                $customerVehicles->store();
                if ('awaiting_payment' != $beforeStatus) {
                    $customer->setCredit($customerCredit - $diff);
                    $customer->save();
                }
            } catch (\Exception $e) {
                $customerVehicles->setTotalCredit($totalCredit);
                $customerVehicles->store();
                $customer->setCredit($customerCredit);
                $customer->save();
            }

        } else {

            try {

                $customerVehicles->setTotalCredit($newCredit);
                $customerVehicles->setChangedReference(Helper::generateRandomString(64));
                $customerVehicles->setStatus('awaiting_payment');
                $customerVehicles->store();

                if ('awaiting_payment' != $beforeStatus) {
                    $customer->setCredit($customerCredit + $totalCredit);
                    $customer->save();
                }

                $cartModel = new CartModel();
                $customerCarts = $cartModel->findBy(['filter' => ['customer_id' => $customer->getId()]]);

                if (!empty($customerCarts)) {
                    /** @var CartModel $cart */
                    foreach ($customerCarts as $cart) {
                        $cart->remove();
                    }
                }

                $productModel = new Product();
                $data = $productModel->findOneByGreaterThanValue($diff - $customerCredit);

                if (!empty($data)) {
                    foreach ($data as $item){
                        $cart = (new CartModel())
                            ->setProduct($item['product'])
                            ->setCustomer($customer)
                            ->setQuantity($item['quantity']);

                        $cart->store();
                    }
                }

                (new EmailNotification())->send('customerVehicle', 'awaitingPayment', $customerVehicles);

            } catch (\Exception $e) {

            }

        }

        (new RedirectResponse('/admin/customer/vehicle/detail/' . $customerVehicles->getId()))->send();
    }

    public function ajaxGetVehicles()
    {
        $customerId = $this->request->query->getInt('customer_id');
        $customerVehicles = (new CustomerVehicleModel())->findBy(['filter' => ['customer_id' => $customerId]]);

        $result = [];

        /** @var CustomerVehicleModel $customerVehicle */
        foreach ($customerVehicles as $key => $customerVehicle) {
            $result[$key] = [
                'id' => $customerVehicle->getId(),
                'fullname' => ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('vehicle_full_name') : $customerVehicle->vehicle->getFullName())
            ];
        }
        return (new JsonResponse($result))->send();
    }

}
