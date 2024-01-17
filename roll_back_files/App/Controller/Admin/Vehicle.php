<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Category;
use Pemm\Model\Page;
use Pemm\Model\VehicleAdditionalOption;
use Pemm\Model\VehicleReadMethod;
use Pemm\Model\VehicleTuning;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Vehicle as VehicleModel;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Vehicle extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('vehicle-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Vehicles - ' . $this->setting->getSiteName())
                ->setMetaDescription('Vehicles - ' . $this->setting->getDescription())
        );

        View::render('admin','vehicle-list', []);
    }

    public function form()
    {
        if (!empty($id = @$this->route_params['id'])) {
            $this->container->set('detailId', $id);
        }

        $this->container->set('page',
            (new Page())
                ->setType('vehicle-form')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Vehicle - ' . $this->setting->getSiteName())
                ->setMetaDescription('Vehicle - ' . $this->setting->getDescription())
        );

        View::render('admin','vehicle', []);
    }

    public function new()
    {

        $this->container->set('page',
            (new Page())
                ->setType('vehicle-form')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Vehicle - ' . $this->setting->getSiteName())
                ->setMetaDescription('Vehicle - ' . $this->setting->getDescription())
        );

        View::render('admin','vehicle-new', []);
    }

    public function brandForm()
    {
        if (!empty($id = @$this->route_params['id'])) {
            $this->container->set('detailId', $id);
        }

        $this->container->set('page',
            (new Page())
                ->setType('vehicle-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Brand - ' . $this->setting->getSiteName())
                ->setMetaDescription('Brand - ' . $this->setting->getDescription())
        );

        View::render('admin','brand', []);
    }

    public function brandList()
    {
        $this->container->set('page',
            (new Page())
                ->setType('brand-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Brands - ' . $this->setting->getSiteName())
                ->setMetaDescription('Brands - ' . $this->setting->getDescription())
        );

        View::render('admin','brand-list', []);
    }

    public function categoryForm()
    {
        if (!empty($id = @$this->route_params['id'])) {
            $this->container->set('detailId', $id);
        }

        $this->container->set('page',
            (new Page())
                ->setType('category-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Categories - ' . $this->setting->getSiteName())
                ->setMetaDescription('Categories - ' . $this->setting->getDescription())
        );

        View::render('admin','category', []);
    }

        public function categoryList()
        {
            $this->container->set('page',
                (new Page())
                    ->setType('category-list')
                    ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                    ->setMetaTitle('Category - ' . $this->setting->getSiteName())
                    ->setMetaDescription('Category - ' . $this->setting->getDescription())
            );

            View::render('admin','category-list', []);
        }


        public function modelForm()
        {
            if (!empty($id = @$this->route_params['id'])) {
                $this->container->set('detailId', $id);
            }

            $this->container->set('page',
                (new Page())
                    ->setType('model-list')
                    ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                    ->setMetaTitle('Models - ' . $this->setting->getSiteName())
                    ->setMetaDescription('Models - ' . $this->setting->getDescription())
            );

            View::render('admin','model', []);
        }

            public function modelList()
            {
                $this->container->set('page',
                    (new Page())
                        ->setType('model-list')
                        ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                        ->setMetaTitle('Models - ' . $this->setting->getSiteName())
                        ->setMetaDescription('Models - ' . $this->setting->getDescription())
                );

                View::render('admin','model-list', []);
            }

        public function yearsForm()
        {
            if (!empty($id = @$this->route_params['id'])) {
                $this->container->set('detailId', $id);
            }

            $this->container->set('page',
                (new Page())
                    ->setType('years-list')
                    ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                    ->setMetaTitle('Years - ' . $this->setting->getSiteName())
                    ->setMetaDescription('Years - ' . $this->setting->getDescription())
            );

            View::render('admin','years', []);
        }

            public function yearsList()
            {
                $this->container->set('page',
                    (new Page())
                        ->setType('years-list')
                        ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                        ->setMetaTitle('Years - ' . $this->setting->getSiteName())
                        ->setMetaDescription('Years - ' . $this->setting->getDescription())
                );

                View::render('admin','years-list', []);
            }

    public function engineForm()
    {
        if (!empty($id = @$this->route_params['id'])) {
            $this->container->set('detailId', $id);
        }

        $this->container->set('page',
            (new Page())
                ->setType('engine-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Engine - ' . $this->setting->getSiteName())
                ->setMetaDescription('Engine - ' . $this->setting->getDescription())
        );

        View::render('admin','engine', []);
    }

    public function engineList()
    {
        $this->container->set('page',
            (new Page())
                ->setType('engine-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Engine - ' . $this->setting->getSiteName())
                ->setMetaDescription('Engine - ' . $this->setting->getDescription())
        );

        View::render('admin','engine-list', []);
    }

    public function tuningList()
    {
        $this->container->set('page',
            (new Page())
                ->setType('vehicle-tuning-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Vehicle Tunings - ' . $this->setting->getSiteName())
                ->setMetaDescription('Vehicle Tunings - ' . $this->setting->getDescription())
        );

        View::render('admin','vehicle-tuning-list', []);
    }


    public function tuningOptionList()
    {
        $this->container->set('page',
            (new Page())
                ->setType('vehicle-tuning-option-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Vehicle Tuning Options - ' . $this->setting->getSiteName())
                ->setMetaDescription('Vehicle Tuning Options - ' . $this->setting->getDescription())
        );

        View::render('admin','vehicle-tuning-option-list', []);
    }

    public function readMethodList()
    {
        $this->container->set('page',
            (new Page())
                ->setType('vehicle-read-method-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Vehicle Read Methods - ' . $this->setting->getSiteName())
                ->setMetaDescription('Vehicle Read Methods - ' . $this->setting->getDescription())
        );

        View::render('admin','vehicle-read-method-list', []);
    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $vehicleList = [];

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
                    'id' => $vehicle->getId(),
                    'type_id' => $vehicle->type->getId(),
                    'type_image' => $vehicle->type->getImage(true),
                    'type_name' => $vehicle->type->getName(),
                    'brand_id' => $vehicle->brand->getId(),
                    'brand_image' => $vehicle->brand->getImage(true),
                    'brand_name' => $vehicle->brand->getName(),
                    'model_id' => $vehicle->model->getId(),
                    'generation_id' => $vehicle->generation->getId(),
                    'engine_id' => $vehicle->getEngineId(),
                    'full_name' => $vehicle->getFullName(),
                    'standard_power' => $vehicle->getStandardPower(),
                    'standard_torque' => $vehicle->getStandardTorque(),
                    'fuel' => $vehicle->getFuel(),
                    'cylinder' => $vehicle->getCylinder(),
                    'compression' => $vehicle->getCompression(),
                    'bore' => $vehicle->getBore(),
                    'engine_number' => $vehicle->getEngineNumber(),
                    'ecu' => $vehicle->getEcu(),
                    'is_active' => $vehicle->getIsActive()
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

    public function ajaxBrandListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $brandList = [];

        $categoryModel = new Category();
        $brands = $categoryModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($brands)) {
            /* @var Category $brand */
            foreach ($brands as $key => $brand) {
                $brandList[$key] = [
                    'id' => $brand->getId(),
                    'parent_id' => $brand->getParentId(),
                    'parent_name' => (!empty($parent = $brand->getParent())) ? $parent->getName() : '',
                    'slug' => $brand->getSlug(),
                    'name' => $brand->getName(),
                    'icon' => $brand->getIcon(),
                    'image' => $brand->getImage(true),
                    'status' => $brand->getStatus(),
                    'sort_order' => $brand->getSortOrder()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $categoryModel->queryTotalCount,
            'recordsFiltered' => $categoryModel->queryTotalCount,
            'data' => $brandList
        ]))->send();
    }

    public function ajaxTuningListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $vehicleTuningList = [];

        $vehicleTuningModel = new VehicleTuning();
        $vehicleTunings = $vehicleTuningModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($vehicleTunings)) {
            /* @var VehicleTuning $vehicleTuning */
            foreach ($vehicleTunings as $key => $vehicleTuning) {
                $vehicleTuning->getVehicle();
                $vehicleTuning->getOptions();
                $options = [];
                if (!empty($vehicleTuning->options)) {
                    /* @var VehicleAdditionalOption $vehicleTuningOption */
                    foreach ($vehicleTuning->options as $vehicleTuningOption) {
                        $options[] = $vehicleTuningOption->tuningAdditionalOption->additionalOption->getName();
                    }
                }
                $vehicleTuningList[$key] = [
                    'id' => $vehicleTuning->getId(),
                    'vehicle_id' => $vehicleTuning->getVehicleId(),
                    'vehicle_type_id' => $vehicleTuning->vehicle->type->getId(),
                    'vehicle_type_name' => $vehicleTuning->vehicle->type->getName(),
                    'vehicle_type_image' => $vehicleTuning->vehicle->type->getImage(true),
                    'vehicle_brand_id' => $vehicleTuning->vehicle->brand->getId(),
                    'vehicle_brand_name' => $vehicleTuning->vehicle->brand->getId(),
                    'vehicle_brand_image' => $vehicleTuning->vehicle->brand->getImage(true),
                    'vehicle_full_name' => $vehicleTuning->vehicle->getFullName(),
                    'tuning_id' => $vehicleTuning->tuning->getId(),
                    'tuning_name' => $vehicleTuning->tuning->getName(),
                    'difference_power' => $vehicleTuning->getDifferencePower(),
                    'difference_torque' => $vehicleTuning->getDifferenceTorque(),
                    'methods' => $vehicleTuning->getMethods(),
                    'method' => $vehicleTuning->getMethod(),
                    'max_power' => $vehicleTuning->getMaxPower(),
                    'max_torque' => $vehicleTuning->getMaxTorque(),
                    'is_active' => $vehicleTuning->getIsActive(),
                    'options' => $options
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $vehicleTuningModel->queryTotalCount,
            'recordsFiltered' => $vehicleTuningModel->queryTotalCount,
            'data' => $vehicleTuningList
        ]))->send();
    }

    public function ajaxTuningOptionListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $vehicleAdditionalOptionList = [];

        $vehicleAdditionalOptionModel = new VehicleAdditionalOption();
        $vehicleAdditionalOptions = $vehicleAdditionalOptionModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($vehicleAdditionalOptions)) {
            /* @var VehicleAdditionalOption $vehicleAdditionalOption */
            foreach ($vehicleAdditionalOptions as $key => $vehicleAdditionalOption) {
                $vehicleAdditionalOption->getVehicle();
                $vehicleAdditionalOption->getVehicleTuning();

                $vehicleAdditionalOptionList[$key] = [
                    'id' => $vehicleAdditionalOption->getId(),
                    'vehicle_id' => $vehicleAdditionalOption->getVehicleId(),
                    'vehicle_full_name' => $vehicleAdditionalOption->vehicle->getFullName(),
                    'vehicle_brand_id' => $vehicleAdditionalOption->vehicle->brand->getId(),
                    'vehicle_brand_name' => $vehicleAdditionalOption->vehicle->brand->getName(),
                    'vehicle_brand_image' => $vehicleAdditionalOption->vehicle->brand->getImage(true),
                    'vehicle_tuning_id' => $vehicleAdditionalOption->getVehicleTuningId(),
                    'tuning_name' => $vehicleAdditionalOption->vehicleTuning->tuning->getName(),
                    'tuning_additional_option_id' => $vehicleAdditionalOption->tuningAdditionalOption->getId(),
                    'additional_option_name' => $vehicleAdditionalOption->tuningAdditionalOption->additionalOption->getName(),
                    'is_active' => $vehicleAdditionalOption->getIsActive()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $vehicleAdditionalOptionModel->queryTotalCount,
            'recordsFiltered' => $vehicleAdditionalOptionModel->queryTotalCount,
            'data' => $vehicleAdditionalOptionList
        ]))->send();
    }

    public function ajaxReadMethodListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $vehicleReadMethodList = [];

        $vehicleReadMethodModel = new VehicleReadMethod();
        $vehicleReadMethods = $vehicleReadMethodModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($vehicleReadMethods)) {
            /* @var VehicleReadMethod $vehicleReadMethod */
            foreach ($vehicleReadMethods as $key => $vehicleReadMethod) {
                $vehicleReadMethod->getVehicle();
                $vehicleReadMethodList[$key] = [
                    'id' => $vehicleReadMethod->getId(),
                    'vehicle_id' => $vehicleReadMethod->getVehicleId(),
                    'vehicle_full_name' => $vehicleReadMethod->vehicle->getFullName(),
                    'vehicle_brand_id' => $vehicleReadMethod->vehicle->brand->getId(),
                    'vehicle_brand_name' => $vehicleReadMethod->vehicle->brand->getName(),
                    'vehicle_brand_image' => $vehicleReadMethod->vehicle->brand->getImage(true),
                    'read_method_id' => $vehicleReadMethod->readMethod->getId(),
                    'read_method_name' => $vehicleReadMethod->readMethod->getName(),
                    'read_method_surname' => $vehicleReadMethod->readMethod->getSurname(),
                    'read_method_image' => $vehicleReadMethod->readMethod->getImage(true),
                    'is_active' => $vehicleReadMethod->getIsActive()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $vehicleReadMethodModel->queryTotalCount,
            'recordsFiltered' => $vehicleReadMethodModel->queryTotalCount,
            'data' => $vehicleReadMethodList
        ]))->send();
    }

    public function ajaxListForSelect()
    {
        $query = $this->request->query->get('q');

        $vehicleModel = new VehicleModel();
        $vehicles = $vehicleModel->forSelect($query);

        return (new JsonResponse($vehicles))->send();

    }
}
