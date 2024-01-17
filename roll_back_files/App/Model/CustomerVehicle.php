<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Pemm\Model\Setting;


class CustomerVehicle extends BaseModel
{
    private $id;

    /* @var Vehicle */
    public $vehicle;

    private $customerId;
    private $status;
    private $model;
    private $manufacturer;
    private $kilometer;
    private $gear;
    private $torque;
    private $power;
    private $vehicleRegistration;
    private $readingDevice;
    private $masterSlave;
    private $readingType;
    private $ecuFile;
    private $tuning;
    private $options;
    private $equipment;
    private $software;
    private $note;
    private $logFile;
    private $idFile;
    private $dynoFile;
    private $totalCredit;
    private $updatedAt;
    private $createdAt;
    private $ecu;
    private $systemEcuFile;
    private $systemIdFile;
    private $systemLogFile;
    private $systemDynoFile;
    private $admin_note;
    private $deleted = 0;
    private $notification;
    private $changedAt;
    private $changedReference;

    /* @var VehicleTuning */
    public $vehicleTuning;

    public $vehicleAdditionalOptions = [];

    public static function situations()
    {
        return [
            'awaiting_payment' => 'Awaiting Payment',
            'pending' => 'Pending',
            'process' => 'Process',
            'completed' => 'Completed',
            'cancel' => 'Canceled',
        ];
    }

    public function find($id)
    {
        return $this->findOneBy(['filter' => ['id' => $id]]);
    }

    public function findOneBy($criteria)
    {
        return $this->findBy($criteria,true);
    }

    public function findAll($pagination = [])
    {
        return $this->findBy($pagination);
    }

    public function findBy($criteria, $findOne = false)
    {
        $where = $executeData = [];

        if (isset($criteria['filter']['id'])) {
            if (is_array($criteria['filter']['id'])) {
                $where[] = 'customer_vehicle.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'customer_vehicle.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['deleted'])) {
            $where[] = 'customer_vehicle.deleted=:deleted';
            $executeData[':deleted'] = $criteria['filter']['deleted'];
        }

        if (isset($criteria['filter']['vehicle_id'])) {
            $where[] = 'customer_vehicle.vehicle_id=:vehicle_id';
            $executeData[':vehicle_id'] = $criteria['filter']['vehicle_id'];
        }

        if (isset($criteria['filter']['notification'])) {
            $where[] = 'customer_vehicle.notification=:notification';
            $executeData[':notification'] = $criteria['filter']['notification'];
        }

        if (isset($criteria['filter']['customer_id'])) {
            $where[] = 'customer_vehicle.customer_id=:customer_id';
            $executeData[':customer_id'] = $criteria['filter']['customer_id'];
        }


        if (isset($criteria['filter']['status'])) {
            if (is_array($criteria['filter']['status'])) {
                $where[] = 'customer_vehicle.status IN ("' . implode('","', $criteria['filter']['status']) . '")';
            } else {
                $where[] = 'customer_vehicle.status=:status';
                $executeData[':status'] = $criteria['filter']['status'];
            }
        }

        $sql = 'SELECT customer_vehicle.* FROM customer_vehicle';

        if (!empty($criteria['filter']['datatable_query'])) {
            $q = $criteria['filter']['datatable_query'];
            $sql .= ' LEFT JOIN customer ON customer.id=customer_vehicle.customer_id
            LEFT JOIN vehicle ON vehicle.id=customer_vehicle.vehicle_id ';
            $where[] = '(CONCAT(customer.first_name, " ",  customer.last_name) LIKE "%' . $q . '%"
            OR LOWER(vehicle.full_name) LIKE "%' . strtolower($q) .   '%"
            OR LOWER(customer_vehicle.status) LIKE "%' . strtolower($q) .   '%"
            OR LOWER(customer_vehicle.options) LIKE "%' . strtolower($q) .   '%"
            OR LOWER(customer_vehicle.ecu) LIKE "%' . strtolower($q) .   '%"
            OR LOWER(customer_vehicle.reading_device) LIKE "%' . strtolower($q) .   '%"
            OR LOWER(customer_vehicle.equipment) LIKE "%' . strtolower($q) .   '%"
            OR LOWER(customer_vehicle.software) LIKE "%' . strtolower($q) .   '%"
            OR LOWER(customer_vehicle.master_slave) LIKE "%' . strtolower($q) .   '%"
            OR LOWER(customer_vehicle.file_time) LIKE "%' . strtolower($q) .   '%"
            OR LOWER(customer_vehicle.total_credit) LIKE "%' . strtolower($q) .   '%"
            OR LOWER(customer_vehicle.vehicle_registration) LIKE "%' . strtolower($q) . '%")';
        }

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        if (isset($criteria['pagination'])) {

            try {

                $prepare = $this->database->prepare($sql);
                $prepare->execute($executeData);

                $this->queryTotalCount = $prepare->rowCount();

            } catch (\Exception $e) {}

        }

        if (isset($criteria['order'])) {
            switch ($criteria['order']['field']) {
                case 'id':
                    $sql .= ' ORDER BY customer_vehicle.id ';
                    break;
                case 'changed_at':
                    $sql .= ' ORDER BY customer_vehicle.changed_at ';
                    break;
                case 'vehicle_registration':
                    $sql .= ' ORDER BY customer_vehicle.vehicle_registration ';
                    break;
                case 'created_at':
                    $sql .= ' ORDER BY customer_vehicle.created_at ';
                    break;
                case 'vehicle_id':
                    $sql .= ' ORDER BY customer_vehicle.vehicle_id ';
                    break;
                case 'tuning':
                    $sql .= ' ORDER BY customer_vehicle.tuning ';
                    break;
                case 'options':
                    $sql .= ' ORDER BY customer_vehicle.options ';
                    break;
                case 'total_credit':
                    $sql .= ' ORDER BY customer_vehicle.total_credit ';
                    break;
                case 'ecu':
                    $sql .= ' ORDER BY customer_vehicle.ecu ';
                    break;

                case 'reading_device':
                    $sql .= ' ORDER BY customer_vehicle.reading_device ';
                    break;

                case 'equipment':
                    $sql .= ' ORDER BY customer_vehicle.equipment ';
                    break;

                case 'software':
                    $sql .= ' ORDER BY customer_vehicle.software ';
                    break;

                case 'master_slave':
                    $sql .= ' ORDER BY customer_vehicle.master_slave ';
                    break;

                case 'file_time':
                    $sql .= ' ORDER BY customer_vehicle.file_time ';
                    break;
                case 'total_credit':
                    $sql .= ' ORDER BY customer_vehicle.total_credit ';
                    break;
                case 'status':
                    $sql .= ' ORDER BY customer_vehicle.status ';
                    break;
                default:
                    $sql .= ' ORDER BY customer_vehicle.id ';
                    $criteria['order']['sort'] = 'ASC';
                    break;
            }
            $sql .= $criteria['order']['sort'];
        }

        if (isset($criteria['pagination'])) {

            if (isset($criteria['pagination']['limit'])) {
                $sql .= ' LIMIT ' . $criteria['pagination']['limit'];
                $this->queryTotalPage = ceil($this->queryTotalCount / $criteria['pagination']['limit']);

                if (isset($criteria['pagination']['page'])) {
                    $sql .= ' OFFSET ' . (($criteria['pagination']['page'] - 1) * $criteria['pagination']['limit']);
                }
            }

        }

        try {

            $prepare = $this->database->prepare($sql);
            $prepare->execute($executeData);
            if ($findOne) {
                $result = null;
                if (!empty($data = $prepare->fetchObject())) {
                    $data->vehicle_id = $data->vehicle_id == 0 ? NULL : $data->vehicle_id;
                    $result = $this->initialize($data);
                }
            } else {
                $result = [];
                $list = $prepare->fetchAll(PDO::FETCH_OBJ);

                if (!empty($list)) {
                    foreach ($list as $key => $data) {
                        $data->vehicle_id = $data->vehicle_id == 0 ? NULL : $data->vehicle_id;

                        $result[$key] = $this->initialize($data);
                    }
                }
            }

        } catch (\Exception $e) {print_r($e);die;}

        return $result;
    }

    /**
     * @param $data
     * @return CustomerVehicle
     */
    public function initialize($data)
    {
        $wmvdata = [
            'wmv_vehicle_name' => $data->wmv_vehicle_name,
            'wmv_brand_name' => $data->wmv_brand_name,
            'wmv_generation_name' => $data->wmv_generation_name,
            'wmv_engine_name' => $data->wmv_engine_name,
        ];
        $data->vehicle_id = $data->vehicle_id == 0 ? NULL : $data->vehicle_id;

        $customerVehicle = (new self())
            ->setId($data->id)
            ->setWMVdata($wmvdata)
            ->setVehicle((new Vehicle())->find($data->vehicle_id))
            ->setCustomerId($data->customer_id)
            ->setStatus($data->status)
            ->setNotification($data->notification)
            ->setChangedAt($data->changed_at)
            ->setModel($data->model)
            ->setManufacturer($data->manufacturer)
            ->setKilometer($data->kilometer)
            ->setGear($data->gear)
            ->setTorque($data->torque)
            ->setPower($data->power)
            ->setVehicleRegistration($data->vehicle_registration)
            ->setReadingDevice($data->reading_device)
            ->setMasterSlave($data->master_slave)
            ->setFileTime($data->file_time)
            ->setReadingType($data->reading_type)
            ->setEcuFile($data->ecu_file)
            ->setEquipment($data->equipment)
            ->setSoftware($data->software)
            ->setNote($data->note)
            ->setLogFile($data->log_file)
            ->setIdFile($data->id_file)
            ->setDynoFile($data->dyno_file)
            ->setTotalCredit($data->total_credit)
            ->setDeleted($data->deleted)
            ->setCreatedAt($data->created_at)
            ->setUpdatedAt($data->update_at)
            ->setTuning($data->tuning)
            ->setVehicleTuning((new Tuning())->find($data->tuning))
            ->setEcu($data->ecu)
            ->setSystemEcuFile($data->system_ecu_file)
            ->setSystemIdFile($data->system_id_file)
            ->setSystemLogFile($data->system_log_file)
            ->setSystemDynoFile($data->system_dyno_file)
            ->setAdminNote($data->admin_note)
            ->setChangedReference($data->change_reference);

        $options = [];

        if (!empty($data->options)) {

            $options = explode(',', $data->options);
            $options = array_filter($options);
            if (!empty($options)) {

                $customerVehicle->setVehicleAdditionalOptions((new TuningAdditionalOption())->findBy(['filter' => ['id' => $options] ]));
            }
        }

        return $customerVehicle;
    }

    public function store($wmvmanual_data = array())
    {
        $data = [
            'vehicle_id' => $this->vehicle->getId(),
            'customer_id' => $this->customerId,
            'status' => $this->status,
            'model' => $this->model,
            'manufacturer' => $this->manufacturer,
            'kilometer' => $this->kilometer,
            'gear' => $this->gear,
            'torque' => $this->torque,
            'power' => $this->power,
            'vehicle_registration' => $this->vehicleRegistration,
            'reading_device' => $this->readingDevice,
            'master_slave' => $this->masterSlave,
            'file_time' => $this->fileTime,
            'reading_type' => $this->readingType,
            'ecu_file' => $this->ecuFile,
            'tuning' => $this->tuning,
            'equipment' => $this->equipment,
            'software' => $this->software,
            'note' => $this->note,
            'log_file' => $this->logFile,
            'id_file' => $this->idFile,
            'dyno_file' => $this->dynoFile,
            'ecu' => $this->ecu,
            'system_id_file' => $this->systemIdFile,
            'system_ecu_file' => $this->systemEcuFile,
            'system_log_file' => $this->systemLogFile,
            'system_dyno_file' => $this->systemDynoFile,
            'admin_note' => $this->admin_note,
            'deleted' => $this->deleted,
            'notification' => $this->notification,
            'changed_at' => $this->changedAt,
            'change_reference' => $this->changedReference,
            'total_credit' => $this->totalCredit,
            'update_at' => date('Y-m-d H:i:s')
        ];

        if (@$wmvmanual_data['status'] == true) {
            if ($wmvmanual_data['wmv_vehicleId'] == NULL) {
                unset($data['vehicle_id']);
            }
            if ($wmvmanual_data['wmv_vehicle_name'] != NULL) {
                $data['wmv_vehicle_name'] = $wmvmanual_data['wmv_vehicle_name'];
            }
            if ($wmvmanual_data['wmv_brand_name'] != NULL) {
                $data['wmv_brand_name'] = $wmvmanual_data['wmv_brand_name'];
            }
            if ($wmvmanual_data['wmv_generation_name'] != NULL) {
                $data['wmv_generation_name'] = $wmvmanual_data['wmv_generation_name'];
            }
            if ($wmvmanual_data['wmv_engine_name'] != NULL) {
                $data['wmv_engine_name'] = $wmvmanual_data['wmv_engine_name'];
            }
        }

        $options = [];

        if (!empty($this->options)) {
            foreach ($this->options as $option) {
                $options[] = $option->getId();
            }
        }

        $data['options'] = implode(',', $options);

        if (empty($this->id)) {

            $data['changed_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');

            if ($this->database->insert('customer_vehicle', $data)) {
                $this->id = $this->database->lastInsertId();
            }

        } else {

            $data['id'] = $this->id;
            $data['created_at'] = $this->createdAt;

            $this->database->update('customer_vehicle', $data);

        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWMVdata($what='')
    {
        if ($what == 'vehicle_full_name') {
            if (!isset($this->wmvdata)) {
                return false;
            }else {
                if ($this->wmvdata['wmv_vehicle_name'] !== 'Cars') {
                    return $this->wmvdata['wmv_vehicle_name'] . " " . $this->wmvdata['wmv_brand_name'] . " " . $this->wmvdata['wmv_generation_name'] . " " . $this->wmvdata['wmv_engine_name'] . " - " . $this->power . " BHP - " . $this->torque . " KW";
                }else {
                    return $this->wmvdata['wmv_brand_name'] . " " . $this->getModel() . " " . $this->wmvdata['wmv_generation_name'] . " " . $this->wmvdata['wmv_engine_name'] . " - " . $this->power . " BHP - " . $this->torque . " KW";
                }
            }
        }else {
            if (isset($this->wmvdata)) {
                return $this->wmvdata[$what];
            }else {
                return false;
            }
            
        }
    }
    /**
     * @param mixed $wmvdata
     * @return CustomerVehicle
     */
    public function setWMVdata($wmvdata=array())
    {
        $this->wmvdata = $wmvdata;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return CustomerVehicle
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Vehicle
     */
    public function getVehicle(): Vehicle
    {
        return $this->vehicle;
    }

    /**
     * @param Vehicle $vehicle
     * @return CustomerVehicle
     */
    public function setVehicle(Vehicle $vehicle): CustomerVehicle
    {
        $this->vehicle = $vehicle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param mixed $customerId
     * @return CustomerVehicle
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return CustomerVehicle
     */
    public function setStatus($status)
    {
        if ($this->status != $status) {
            $this->notification = 1;
            $this->changedAt = date('Y-m-d H:i:s');
        }

        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     * @return CustomerVehicle
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @param mixed $manufacturer
     * @return CustomerVehicle
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKilometer()
    {
        return $this->kilometer;
    }

    /**
     * @param mixed $kilometer
     * @return CustomerVehicle
     */
    public function setKilometer($kilometer)
    {
        $this->kilometer = $kilometer;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getGear()
    {
        return $this->gear;
    }

    /**
     * @param mixed $gear
     * @return CustomerVehicle
     */
    public function setGear($gear)
    {
        $this->gear = $gear;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTorque()
    {
        return $this->torque;
    }

    /**
     * @param mixed $torque
     * @return CustomerVehicle
     */
    public function setTorque($torque)
    {
        $this->torque = $torque;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPower()
    {
        return $this->power;
    }

    /**
     * @param mixed $power
     * @return CustomerVehicle
     */
    public function setPower($power)
    {
        $this->power = $power;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVehicleRegistration()
    {
        return $this->vehicleRegistration;
    }

    /**
     * @param mixed $vehicleRegistration
     * @return CustomerVehicle
     */
    public function setVehicleRegistration($vehicleRegistration)
    {
        $this->vehicleRegistration = $vehicleRegistration;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReadingDevice()
    {
        return $this->readingDevice;
    }

    /**
     * @param mixed $readingDevice
     * @return CustomerVehicle
     */
    public function setReadingDevice($readingDevice)
    {
        $this->readingDevice = $readingDevice;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMasterSlave()
    {
        return $this->masterSlave;
    }

    /**
     * @param mixed $masterSlave
     * @return CustomerVehicle
     */
    public function setMasterSlave($masterSlave)
    {
        $this->masterSlave = $masterSlave;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFileTime()
    {
        return $this->fileTime;
    }

    /**
     * @param mixed $fileTime
     * @return CustomerVehicle
     */
    public function setFileTime($fileTime)
    {
        $this->fileTime = $fileTime;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReadingType()
    {
        return $this->readingType;
    }

    /**
     * @param mixed $readingType
     * @return CustomerVehicle
     */
    public function setReadingType($readingType)
    {
        $this->readingType = $readingType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEcuFile($fullPath = false)
    {
        $ecuFile = $this->ecuFile;

        if ($fullPath) {
            $ecuFile = '/files/' . $fullPath;
        }

        return $ecuFile;
    }

    /**
     * @param mixed $ecuFile
     * @return CustomerVehicle
     */
    public function setEcuFile($ecuFile)
    {
        $this->ecuFile = $ecuFile;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     * @return CustomerVehicle
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEquipment()
    {
        return $this->equipment;
    }

    /**
     * @param mixed $equipment
     * @return CustomerVehicle
     */
    public function setEquipment($equipment)
    {
        $this->equipment = $equipment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSoftware()
    {
        return $this->software;
    }

    /**
     * @param mixed $software
     * @return CustomerVehicle
     */
    public function setSoftware($software)
    {
        $this->software = $software;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $software
     * @return CustomerVehicle
     */
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }


    /**
     * @param $admin_note
     * @return $this
     */
    public function setAdminNote($admin_note)
    {
        $this->admin_note = $admin_note;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdminNote()
    {
        return $this->admin_note;
    }

    /**
     * @return mixed
     */
    public function getTuning()
    {
        return $this->tuning;
    }

    /**
     * @param mixed $tuning
     * @return CustomerVehicle
     */
    public function setTuning($tuning)
    {
        $this->tuning = $tuning;
        return $this;
    }

    /**
     * @return VehicleTuning
     */
    public function getVehicleTuning(): Tuning
    {
        return $this->vehicleTuning;
    }

    /**
     * @param VehicleTuning $vehicleTuning
     * @return CustomerVehicle
     */
    public function setVehicleTuning(Tuning $vehicleTuning): CustomerVehicle
    {
        $this->vehicleTuning = $vehicleTuning;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogFile()
    {
        return $this->logFile;
    }

    /**
     * @param mixed $logFile
     * @return CustomerVehicle
     */
    public function setLogFile($logFile)
    {
        $this->logFile = $logFile;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdFile()
    {
        return $this->idFile;
    }

    /**
     * @param mixed idFile
     * @return CustomerVehicle
     */
    public function setIdFile($idFile)
    {
        $this->idFile = $idFile;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDynoFile()
    {
        return $this->dynoFile;
    }

    /**
     * @param mixed $dynoFile
     * @return CustomerVehicle
     */
    public function setDynoFile($dynoFile)
    {
        $this->dynoFile = $dynoFile;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalCredit()
    {
        return $this->totalCredit;
    }

    /**
     * @param mixed $totalCredit
     * @return CustomerVehicle
     */
    public function setTotalCredit($totalCredit)
    {
        $this->totalCredit = $totalCredit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return CustomerVehicle
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @return CustomerVehicle
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getEcu()
    {
        return $this->ecu;
    }

    /**
     * @param mixed $ecu
     * @return CustomerVehicle
     */
    public function setEcu($ecu)
    {
        $this->ecu = $ecu;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSystemEcuFile()
    {
        return $this->systemEcuFile;
    }

    /**
     * @param mixed $systemEcuFile
     * @return CustomerVehicle
     */
    public function setSystemEcuFile($systemEcuFile)
    {
        $this->systemEcuFile = $systemEcuFile;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSystemIdFile()
    {
        return $this->systemIdFile;
    }

    /**
     * @param mixed $systemIdFile
     * @return CustomerVehicle
     */
    public function setSystemIdFile($systemIdFile)
    {
        $this->systemIdFile = $systemIdFile;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getSystemLogFile()
    {
        return $this->systemLogFile;
    }

    /**
     * @param mixed $systemLogFile
     * @return CustomerVehicle
     */
    public function setSystemLogFile($systemLogFile)
    {
        $this->systemLogFile = $systemLogFile;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSystemDynoFile()
    {
        return $this->systemDynoFile;
    }

    /**
     * @param mixed $systemDynoFile
     * @return CustomerVehicle
     */
    public function setSystemDynoFile($systemDynoFile)
    {
        $this->systemDynoFile = $systemDynoFile;
        return $this;
    }


    public function upload($type, UploadedFile $file)
    {


        // return $file->move($_SERVER['DOCUMENT_ROOT'] . '/files/', $type.   '-' . rand(1000,9999) . '-' . (strlen($file->getClientOriginalName()) > 35 ? str_replace(' ','_',mb_substr($file->getClientOriginalName(),0,35)) . '.'. @end(explode('.',$file->getClientOriginalName()))  : str_replace(' ','_',$file->getClientOriginalName())));

            $extension = $file->getClientOriginalExtension();

            $allowedExtensions = ['php', 'php7', 'php8', 'php5', 'php4'];
            if (!in_array($extension, $allowedExtensions)) {
                return $file->move($_SERVER['DOCUMENT_ROOT'] . '/files/', $type.   '-' . rand(1000,9999) . '-' . (strlen($file->getClientOriginalName()) > 35 ? str_replace(' ','_',mb_substr($file->getClientOriginalName(),0,35)) . '.'. @end(explode('.',$file->getClientOriginalName()))  : str_replace(' ','_',$file->getClientOriginalName())));
            } else {
                return false;
            }

    }

    /**
     * @return array|Customer|void
     */
    public function getCustomer()
    {
        return (new Customer())->find($this->customerId);
    }

    /**
     * @return array
     */
    public function getVehicleAdditionalOptions(): array
    {
        return $this->vehicleAdditionalOptions;
    }

    /**
     * @param array $vehicleAdditionalOptions
     * @return CustomerVehicle
     */
    public function setVehicleAdditionalOptions(array $vehicleAdditionalOptions): CustomerVehicle
    {
        $this->vehicleAdditionalOptions = $vehicleAdditionalOptions;
        $this->options = $vehicleAdditionalOptions;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param mixed $deleted
     * @return CustomerVehicle
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param mixed $notification
     * @return CustomerVehicle
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChangedAt()
    {
        return $this->changedAt;
    }

    /**
     * @param mixed $changedAt
     * @return CustomerVehicle
     */
    public function setChangedAt($changedAt)
    {
        $this->changedAt = $changedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChangedReference()
    {
        return $this->changedReference;
    }

    /**
     * @param mixed $changedReference
     * @return CustomerVehicle
     */
    public function setChangedReference($changedReference)
    {
        $this->changedReference = $changedReference;

        return $this;
    }

}
