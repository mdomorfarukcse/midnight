<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\EmailNotification;
use Pemm\Model\Page;
use Pemm\Model\CustomerVehicle as CustomerVehicleModel;
use Pemm\Model\VehicleAdditionalOption;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Customer;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CustomerVehicle extends CoreController
{
    public function create()
    {

    }

    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('customer-vehicle')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('My Files - ' . $this->setting->getSiteName())
                ->setMetaDescription('My Files - ' . $this->setting->getDescription())
        );

        View::render('customer','dosyalarim', []);

    }
    public function detail()
    {
        $this->container->set('page',
            (new Page())
                ->setType('customer-vehicle')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('My Files - ' . $this->setting->getSiteName())
                ->setMetaDescription('My Files - ' . $this->setting->getDescription())
        );

        if (empty($id = $this->route_params['id'])) {
            return new RedirectResponse('/panel');
        }

        /* @var Customer $customer */
        $customer = $this->container->get('customer');

        $customerVehicle = (new CustomerVehicleModel())->find($id);

        if (empty($customerVehicle) || $customerVehicle->getCustomerId() != $customer->getId()) {
            return new RedirectResponse('/panel');
        }

        $this->container->set('customerVehicle', $customerVehicle);

        View::render('customer','dosya-detay', []);

    }


    public function ajaxListForDataTable()
    {
        /* @var Customer $customer */
        $customer = $this->container->get('customer');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');
        $filter = $this->request->request->get('filter');

        $customerVehicleList = [];

        $filter['customer_id'] = $customer->getId();
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
                        'date' => \DateTime::createFromFormat('Y-m-d H:i:s', $customerVehicle->getCreatedAt())->format('d M H:i'),
                        'change' => \DateTime::createFromFormat('Y-m-d H:i:s', $customerVehicle->getChangedAt())->format('d M H:i'),
                        'vehicle' => ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('vehicle_full_name') : $customerVehicle->vehicle->getFullName()),
                        'ecu' => $customerVehicle->getEcu(),
                        'tuning' => $customerVehicle->vehicleTuning->getName(),
                        'options' => $options,
                        'total_credit' => $customerVehicle->getTotalCredit(),
                        'status' => $customerVehicle->getStatus(),
                        'files' => [
                            "ecu"   => $customerVehicle->getSystemEcuFile(),
                            "id"   => $customerVehicle->getSystemIdFile(),
                            'log'   => $customerVehicle->getSystemLogFile(),
                            'dyno'  => $customerVehicle->getSystemDynoFile(),
                        ],
                        'brand_image' => $customerVehicle->vehicle->brand->getImage(true)
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



    public function getVehicleDetail()
    {
        /* @var Customer $customer */
        $customer = $this->container->get('customer');
        $vehicle_id = $this->request->query->get('vehicle_id');

        $customerVehicleList = [];

        $customerVehicleModel = new CustomerVehicleModel();
        $customerVehicle = $customerVehicleModel->findOneBy([
            'filter' => ['customer_id' => $customer->getId(), 'id' => $vehicle_id]
        ]);


        if($customerVehicle){

            $vehicleAdditionalOptions = $customerVehicle->vehicleAdditionalOptions;

            $options = [];
            if (!empty($vehicleAdditionalOptions)) {
                /* @var VehicleAdditionalOption $vehicleTuningAdditionalOption */
                foreach ($customerVehicle->vehicleAdditionalOptions as $_key => $vehicleTuningAdditionalOption) {
                    $options[$_key] = $vehicleTuningAdditionalOption->additionalOption->getName();

                }
            }

            $data = [
                'vehicle_id'        => $customerVehicle->getVehicle()->getId(),
                'vehicle'           => ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('wmv_vehicle_name') : $customerVehicle->vehicle->getFullName()),
                'model'             => $customerVehicle->getModel(),
                'kilometer'         => $customerVehicle->getKilometer(),
                'power'             => $customerVehicle->getPower(),
                'torque'            => $customerVehicle->getTorque(),
                'ecu'               => $customerVehicle->getEcu(),
                'plaka'             => $customerVehicle->getVehicleRegistration(),
                'master_slave'      => $customerVehicle->getMasterSlave(),
                'file_time'         => $customerVehicle->getFileTime(),
                'reading_type'      => $customerVehicle->getReadingType(),
                'reading_device'    => $customerVehicle->getReadingDevice(),
                'notes'             => $customerVehicle->getNote(),
                'admin_note'        => $customerVehicle->getAdminNote(),
                'file_ecu'          => $customerVehicle->getSystemEcuFile(),
                'file_id'           => $customerVehicle->getSystemIdFile(),
                'file_log'          => $customerVehicle->getSystemLogFile(),
                'file_dyno'         => $customerVehicle->getSystemDynoFile(),
                'original_ecu'      => $customerVehicle->getEcuFile(),
                'original_dyno'     => $customerVehicle->getDynoFile(),
                'original_log'      => $customerVehicle->getLogFile(),
                'original_id'       => $customerVehicle->getIdFile(),
                'credit'            => $customerVehicle->getTotalCredit(),
                'changeReference'   => $customerVehicle->getChangedReference(),
                'status'            => $customerVehicle->getStatus(),
                'opt'               => implode(',',$options),
                'tuning'            => [ 'name' => $customerVehicle->getVehicleTuning()->getName(), 'credit' => $customerVehicle->getVehicleTuning()->getCredit()]
            ];
        }

        return (new JsonResponse($data))->send();
    }

    public function pay()
    {
        /* @var Customer $customer */
        $customer = $this->container->get('customer');
        $vehicle_id = $this->route_params['id'];

        $customerVehicleModel = new CustomerVehicleModel();
        $customerVehicle = $customerVehicleModel->findOneBy([
            'filter' => ['customer_id' => $customer->getId(), 'id' => $vehicle_id]
        ]);

        if ($customerVehicle) {

            if ($customer->getCredit() >= $customerVehicle->getTotalCredit()) {
                $customer->setCredit($customer->getCredit() - $customerVehicle->getTotalCredit());
                $customer->save();

                $customerVehicle->setStatus('pending');
                $customerVehicle->store();

                (new EmailNotification())->send('customerVehicle', 'CustomerChangeStatus', $customerVehicle);

                (new RedirectResponse($this->request->headers->get('referer')))->send();
            }
        }
    }

}
