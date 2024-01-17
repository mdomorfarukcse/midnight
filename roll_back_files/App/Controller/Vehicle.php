<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Customer;
use Pemm\Model\CustomerVehicle as CustomerVehicleModel;
use Pemm\Model\Page;
use Pemm\Model\Setting;
use Pemm\Model\Vehicle as VehicleModel;
use Symfony\Component\HttpFoundation\JsonResponse;

class Vehicle extends CoreController
{
    public function index()
    {

    }

    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('vehicle-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Auto Tuner - ' . $this->setting->getSiteName())
                ->setMetaDescription('Auto Tuner - ' . $this->setting->getDescription())
        );

        View::render('customer','auto-tuner', []);
    }

    public function ajaxGetEcuByEngine()
    {
        try {

            if (!isset($this->route_params['id']))
                throw new \Exception('Eksik parametre');

            $result = [];

            $vehicleModel = new VehicleModel();
            $vehicles = $vehicleModel->findBy(['filter' => ['engine_id' => intval($this->route_params['id'])]]);

            if (!empty($vehicles)) {
                /* @var VehicleModel $vehicle */
                foreach ($vehicles as $key => $vehicle) {
                    $result[$key] = [
                        'id' => $vehicle->getId(),
                        'name' => !empty($vehicle->getEcu()) ? $vehicle->getEcu() : $this->language::translate('Default Ecu')
                    ];
                }
            }

        } catch (\Exception $e) {
            return (new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]))->send();
        }

        return (new JsonResponse([
            'success' => true,
            'result' => $result
        ]))->send();

    }

    public function ajaxGetVehicleByIdWithHtmlForHome()
    {
        try {

            if (!isset($this->route_params['id']))
                throw new \Exception('Eksik parametre');

            $result = [];

            $vehicleModel = new VehicleModel();
            if (is_numeric($this->route_params['id'])) {
                $vehicle = $vehicleModel->find(intval($this->route_params['id']));
            }else {
                $vehicle = $vehicleModel->find(177);
            }

            $vehicleBlockHtml = '';

            if (!empty($vehicle)) {
                $vehicle->getBrandByEngineId();
                $this->container->set('vehicle', $vehicle);
                View::render('customer','ajax/home-vehicle-block-html');
            }

        } catch (\Exception $e) {
            return (new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]))->send();
        }
    }

    public function ajaxListForDataTable()
    {
        /* @var Customer $customer */
        $customer = $this->container->get('customer');
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $vehicleList = [];
     $filter['is_active'] = 1;

        $vehicleModel = new VehicleModel();
        $vehicles = $vehicleModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($vehicles)) {
            /* @var VehicleModel $vehicle */
            foreach ($vehicles as $key => $vehicle) {
                $vehicleList[$key] = [
                    'brand_image' =>  $vehicle->brand ? $vehicle->brand->getImage(true) : '',
                    'full_name' => $vehicle->getFullName(),
                    'version' => '',
                    'power' => $vehicle->getStandardPower(),
                    'torque' => $vehicle->getStandardTorque(),
                    'fuel' => $vehicle->getFuel(),
                    'ecu' => $vehicle->getEcu(),
                    'mcu' => '',
                    'method' => ''
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $vehicleModel->queryTotalCount,
            'recordsFiltered' => $vehicleModel->queryTotalCount,
            'data' => $vehicleList
        ]))->send();
    }
}
