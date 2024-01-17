<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;
use Pemm\Model\DTO\AdditionalOptionDTO;
use Pemm\Model\DTO\ReadMethodDTO;
use Pemm\Model\DTO\TuningDTO;
use Pemm\Model\DTO\VehicleTuningDTO;

class Vehicle extends BaseModel
{
    private $id;
    private $engineId;
    private $fullName;
    private $standardPower = 0;
    private $standardTorque = 0;
    private $fuel;
    private $cylinder;
    private $compression;
    private $bore;
    private $engineNumber;
    private $ecu = 'Unknown ECU';
    private $isActive;
    private $createdAt;
    private $updatedAt;

    public $tunings = [];
    public $tuningsAll = [];
    public $readMethods = [];

    public $tmpDTO = ['tunings' => [], 'read_methods' => []];

    /* @var Category $type */
    public $type;
    /* @var Category $brand */
    public $brand;
    /* @var Category $brand */
    public $model;
    /* @var Category $brand */
    public $generation;
    /* @var Category $engine */
    public $engine;

    private $rpm = '0, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000, 5500, 6000, 6500, 7000,';
    private $oemPowerChart;
    private $oemTorqueChart;
    private $_base;

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
                $where[] = 'vehicle.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'vehicle.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (!empty($criteria['filter']['full_name'])) {
            $explode = explode(' ', $criteria['filter']['full_name']);
            $explode = array_filter($explode);
            foreach ($explode as $_ex) {
                $where[] = 'LOWER(vehicle.full_name) LIKE LOWER("%' . $_ex . '%")';
            }
        }

        if (!empty($criteria['filter']['datatable_query'])) {
            $explode = explode(' ', $criteria['filter']['datatable_query']);
            $explode = array_filter($explode);
            foreach ($explode as $_ex) {
                $where[] = 'LOWER(vehicle.full_name) LIKE LOWER("%' . $_ex . '%")';
            }
        }

        if (!empty($criteria['filter']['query'])) {
            $explode = explode(' ', $criteria['filter']['query']);
            $explode = array_filter($explode);
            foreach ($explode as $_ex) {
                $where[] = '(LOWER(vehicle.full_name) LIKE LOWER("%' . $_ex . '%") OR LOWER(vehicle.ecu) LIKE LOWER("%' . $_ex . '%"))';
            }
        }


        if (!empty($criteria['filter']['select_query'])) {
            $explode = explode(' ', $criteria['filter']['select_query']);
            $explode = array_filter($explode);
            foreach ($explode as $_ex) {
                $where[] = 'LOWER(vehicle.full_name) LIKE LOWER("%' . $_ex . '%")';
            }
        }

        if (isset($criteria['filter']['engine_id'])) {
            $where[] = 'vehicle.engine_id=:engine_id';
            $executeData[':engine_id'] = $criteria['filter']['engine_id'];
        }

        if (isset($criteria['filter']['is_active'])) {
            $where[] = 'vehicle.is_active=:is_active';
            $executeData[':is_active'] = $criteria['filter']['is_active'];
        }

        if (isset($criteria['filter']['base'])) {
            $where[] = 'vehicle._id=:base';
            $executeData[':base'] = $criteria['filter']['base'];
        }

        $sql = 'SELECT * FROM vehicle';

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

        if (!empty($criteria['order']['field']) && !empty($criteria['order']['sort'])) {
            $sql .= ' ORDER BY vehicle.' . $criteria['order']['field'] . ' ' . $criteria['order']['sort'];
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
                    $result = $this->initialize($data);
                }
            } else {
                $result = [];
                $list = $prepare->fetchAll(PDO::FETCH_OBJ);

                if (!empty($list)) {
                    foreach ($list as $key => $data) {
                        $result[$key] = $this->initialize($data);
                    }
                }
            }

        } catch (\Exception $e) {print_r($e);die;}

        return $result;
    }

    /**
     * @param $data
     * @return Vehicle
     */
    public function initialize($data): Vehicle
    {
        $vehicle =  (new self())
            ->setId($data->id)
            ->setEngineId($data->engine_id)
            ->setFullName($data->full_name)
            ->setStandardPower($data->standard_power)
            ->setStandardTorque($data->standard_torque)
            ->setFuel($data->fuel)
            ->setCylinder($data->cylinder)
            ->setCompression($data->compression)
            ->setBore($data->bore)
            ->setEngineNumber($data->engine_number)
            ->setEcu($data->ecu)
            ->setIsActive($data->is_active)
            ->setCreatedAt($data->created_at)
            ->setUpdatedAt($data->updated_at)
            ->setRpm($data->rpm)
            ->setOemPowerChart($data->oem_power_chart)
            ->setOemTorqueChart($data->oem_torque_chart)
            ->setBase($data->_id);

        $vehicle->buildCategoryChainByVehicle();
        $vehicle->getReadMethods();
        $vehicle->getTunings();

        return $vehicle;
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('vehicle', [
                'engine_id' => $this->engineId,
                'full_name' => $this->fullName,
                'standard_power' => $this->standardPower,
                'standard_torque' => $this->standardTorque,
                'fuel' => $this->fuel,
                'cylinder' => $this->cylinder,
                'compression' => $this->compression,
                'bore' => $this->bore,
                'engine_number' => $this->engineNumber,
                'ecu' => $this->ecu,
                'is_active' => $this->isActive,
                'rpm' => $this->rpm,
                'oem_power_chart' => $this->oemPowerChart,
                'oem_torque_chart' => $this->oemTorqueChart,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                '_id' => $this->_base
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('vehicle', [
                'id' => $this->id,
                'engine_id' => $this->engineId,
                'full_name' => $this->fullName,
                'standard_power' => $this->standardPower,
                'standard_torque' => $this->standardTorque,
                'fuel' => $this->fuel,
                'cylinder' => $this->cylinder,
                'compression' => $this->compression,
                'bore' => $this->bore,
                'engine_number' => $this->engineNumber,
                'ecu' => $this->ecu,
                'is_active' => $this->isActive,
                'rpm' => $this->rpm,
                'oem_power_chart' => $this->oemPowerChart,
                'oem_torque_chart' => $this->oemTorqueChart,
                'created_at' => $this->createdAt,
                'updated_at' => date('Y-m-d H:i:s'),
                '_id' => $this->_base
            ]);
        }

        return $this;
    }

    public function getBrandByEngineId()
    {
        return $this->brand = (new Category())->getBrandByEngineId($this->engineId);
    }

    public function getEngineByEngineId()
    {
        return $this->engine = (new Category())->getEngineByEngineId($this->engineId);
    }

    public function getTunings()
    {
        return $this->tunings = (new VehicleTuning())->findBy(['filter' => ['vehicle_id' => $this->id]]);
    }

    public function getTuningsAll()
    {
        return $this->tuningsAll = (new Tuning())->findBy(['filter' => ['is_active' => 1],'order' => ['field' => 'sort_order', 'sort' => 'asc']]);
    }

    public function getReadMethods()
    {
        return $this->readMethods = (new VehicleReadMethod())->findBy(['filter' => ['vehicle_id' => $this->id]]);
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
     * @return Vehicle
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEngineId()
    {
        return $this->engineId;
    }

    /**
     * @param mixed $engineId
     * @return Vehicle
     */
    public function setEngineId($engineId)
    {
        $this->engineId = $engineId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $fullName
     * @return Vehicle
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStandardPower()
    {
        return $this->standardPower;
    }

    /**
     * @param mixed $standardPower
     * @return Vehicle
     */
    public function setStandardPower($standardPower)
    {
        $this->standardPower = $standardPower;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStandardTorque()
    {
        return $this->standardTorque;
    }

    /**
     * @param mixed $standardTorque
     * @return Vehicle
     */
    public function setStandardTorque($standardTorque)
    {
        $this->standardTorque = $standardTorque;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFuel()
    {
        return $this->fuel;
    }

    /**
     * @param mixed $fuel
     * @return Vehicle
     */
    public function setFuel($fuel)
    {
        $this->fuel = $fuel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCylinder()
    {
        return $this->cylinder;
    }

    /**
     * @param mixed $cylinder
     * @return Vehicle
     */
    public function setCylinder($cylinder)
    {
        $this->cylinder = $cylinder;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompression()
    {
        return $this->compression;
    }

    /**
     * @param mixed $compression
     * @return Vehicle
     */
    public function setCompression($compression)
    {
        $this->compression = $compression;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBore()
    {
        return $this->bore;
    }

    /**
     * @param mixed $bore
     * @return Vehicle
     */
    public function setBore($bore)
    {
        $this->bore = $bore;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEngineNumber()
    {
        return $this->engineNumber;
    }

    /**
     * @param mixed $engineNumber
     * @return Vehicle
     */
    public function setEngineNumber($engineNumber)
    {
        $this->engineNumber = $engineNumber;
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
     * @return Vehicle
     */
    public function setEcu($ecu)
    {
        $this->ecu = $ecu;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     * @return Vehicle
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
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
     * @return Vehicle
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
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
     * @return Vehicle
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function hasTuning()
    {
        return (!empty($this->tunings));
    }

    public function hasReadMethod()
    {
        return (!empty($this->readMethods));
    }

    public function buildCategoryChainByVehicle()
    {
        (new Category())->buildCategoryChainByVehicle($this);
    }

    public function forSelect($query)
    {
        $result = [];

        if (!empty($query)) {
            $explode = explode(' ', $query);
            $explode = array_filter($explode);
            $where = [];
            foreach ($explode as $_ex) {
                $where[] = 'LOWER(vehicle.full_name) LIKE LOWER("%' . $_ex . '%")';
            }

            if (!empty($where)) {
                $sql = 'SELECT id, full_name as text FROM vehicle WHERE ' . implode(' AND ', $where);
                $prepare = $this->database->prepare($sql);
                $prepare->execute();
                $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
            }

        }

        return $result;
    }

    public function getReadMethodIds()
    {
        $result = [];
        if (!empty($this->readMethods)) {
            /* @var VehicleReadMethod $readMethod */
            foreach ($this->readMethods as $readMethod) {
                if ($readMethod->getIsActive()) {
                    $result[] = $readMethod->getReadMethod()->getId();
                }
            }
        }

        return $result;
    }

    public function saveTunings($tuningDatas)
    {
        if (!empty($tuningDatas)) {

            foreach ($tuningDatas as $tuningData) {

                $vehicleTuning = new VehicleTuning();

                if (!empty($tuningData['vehicle_tuning']['id'])) {

                    $vehicleTuning = (new VehicleTuning())->find($tuningData['vehicle_tuning']['id']);

                }

                $vehicleTuning->setVehicleId($this->id);
                $vehicleTuning->setTuning((new Tuning())->find($tuningData['id']));
                $vehicleTuning->setCredit(isset($tuningData['vehicle_tuning']['credit']) ? intval($tuningData['vehicle_tuning']['credit']) : 0);
                $vehicleTuning->setIsActive(!empty($tuningData['vehicle_tuning']['is_active']) ? 1 : 0);
                $vehicleTuning->setDifferencePower($tuningData['vehicle_tuning']['difference_power']);
                $vehicleTuning->setMaxPower($tuningData['vehicle_tuning']['max_power']);
                $vehicleTuning->setDifferenceTorque($tuningData['vehicle_tuning']['difference_torque']);
                $vehicleTuning->setMaxTorque($tuningData['vehicle_tuning']['max_torque']);
                $vehicleTuning->setMethods(isset($tuningData['vehicle_tuning']['methods']) ? $tuningData['vehicle_tuning']['methods'] : null);
                $vehicleTuning->setMethod(isset($tuningData['vehicle_tuning']['method']) ? $tuningData['vehicle_tuning']['method'] : null);
                $vehicleTuning->setPowerChart($tuningData['vehicle_tuning']['power_chart']);
                $vehicleTuning->setTorqueChart($tuningData['vehicle_tuning']['torque_chart']);

                $vehicleTuning->store();

                foreach ($tuningData['additional_options'] as $vehicleTuningAdditionalOptionData) {

                    $vehicleTuningAdditionalOption = new VehicleAdditionalOption();

                    if (!empty($vehicleTuningAdditionalOptionData['vehicle_tuning_additional_option']['id'])) {
                        $vehicleTuningAdditionalOption = (new VehicleAdditionalOption())->find(
                            $vehicleTuningAdditionalOptionData['vehicle_tuning_additional_option']['id']);
                    }

                    if (!empty($vehicleTuningAdditionalOptionData['tuning_additional_option']['id'])) {
                        $tuningAdditionalOption = (new TuningAdditionalOption())->find(
                            $vehicleTuningAdditionalOptionData['tuning_additional_option']['id']
                        );
                    } else {
                        $tuningAdditionalOption = new TuningAdditionalOption();
                        $tuningAdditionalOption->setTuningId($tuningData['id']);
                        $tuningAdditionalOption->setIsActive(!empty($vehicleTuningAdditionalOptionData['is_active']) ? 1 : 0);
                        $tuningAdditionalOption->setAdditionalOption((new AdditionalOption())->find($vehicleTuningAdditionalOptionData['id']));
                        $tuningAdditionalOption->setCredit(1);
                        $tuningAdditionalOption->store();
                        $vehicleTuningAdditionalOptionData['tuning_additional_option']['id'] = $tuningData['id'];
                        $vehicleTuningAdditionalOptionData['tuning_additional_option']['is_active'] = !empty($vehicleTuningAdditionalOptionData['is_active']) ? 1 : 0;
                    }

                    $vehicleTuningAdditionalOption->setVehicleId($this->id);
                    $vehicleTuningAdditionalOption->setVehicleTuningId($tuningData['id']);
                    $vehicleTuningAdditionalOption->setTuningAdditionalOption($tuningAdditionalOption);

                    $vehicleTuningAdditionalOption->setIsActive(
                        !empty($vehicleTuningAdditionalOptionData['vehicle_tuning_additional_option']['is_active']) ? 1 : 0
                    );

                    if (empty($vehicleTuningAdditionalOptionData['tuning_additional_option']['is_active'])) {
                        $vehicleTuningAdditionalOption->setIsActive(0);
                    }

                    if (empty($vehicleTuningAdditionalOptionData['is_active'])) {
                        $vehicleTuningAdditionalOption->setIsActive(0);
                    }

                    $vehicleTuningAdditionalOption->store();

                }
            }

        }
    }

    public function saveReadMethods($readMethodDatas)
    {
        $this->resetVehicleReadMethods();

        if (!empty($readMethodDatas)) {

            foreach ($readMethodDatas as $readMethodData) {

                $vehicleReadMethod = new VehicleReadMethod();

                if (!empty($readMethodData['vehicle_read_method']['id'])) {
                    $vehicleReadMethod = (new VehicleReadMethod())->find($readMethodData['vehicle_read_method']['id']);
                }

               $vehicleReadMethod
                    ->setVehicleId($this->id)
                    ->setReadMethod((new ReadMethod())->find($readMethodData['id']))
                    ->setIsActive(!empty($readMethodData['vehicle_read_method']['is_active']) ? 1 : 0)
                    ->store();
            }
        }
    }

    public function resetVehicleAdditionalOptions($vehicleTuningId)
    {
        $prepare = $this->database->prepare('UPDATE vehicle_additional_option SET is_active=:is_active WHERE vehicle_id=:vehicle_id AND vehicle_tuning_id=:vehicle_tuning_id');
        $prepare->execute([
            ':vehicle_id' => $this->id,
            ':vehicle_tuning_id' => $vehicleTuningId,
            ':is_active' => 0
        ]);
    }

    public function resetVehicleReadMethods()
    {
        $prepare = $this->database->prepare('UPDATE vehicle_read_method SET is_active=:is_active WHERE vehicle_id=:vehicle_id');
        $prepare->execute([
            ':vehicle_id' => $this->id,
            ':is_active' => 0
        ]);
    }

    /**
     * @return mixed
     */
    public function getBase()
    {
        return $this->_base;
    }

    /**
     * @param mixed $base
     */
    public function setBase($base): Vehicle
    {
        $this->_base = $base;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasRpm()
    {
        return !empty($this->rpm);
    }

    /**
     * @return mixed
     */
    public function getRpm()
    {
        return $this->rpm;
    }

    /**
     * @param mixed $rpm
     * @return Vehicle
     */
    public function setRpm($rpm)
    {
        $this->rpm = $rpm;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasOemPowerChart()
    {
        return !empty($this->oemPowerChart);
    }

    /**
     * @return mixed
     */
    public function getOemPowerChart()
    {
        return $this->oemPowerChart;
    }

    /**
     * @param mixed $oemPowerChart
     * @return Vehicle
     */
    public function setOemPowerChart($oemPowerChart)
    {
        $this->oemPowerChart = $oemPowerChart;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasOemTorqueChart()
    {
        return !empty($this->oemTorqueChart);
    }

    /**
     * @return mixed
     */
    public function getOemTorqueChart()
    {
        return $this->oemTorqueChart;
    }

    /**
     * @param mixed $oemTorqueChart
     * @return Vehicle
     */
    public function setOemTorqueChart($oemTorqueChart)
    {
        $this->oemTorqueChart = $oemTorqueChart;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategoryChainText()
    {
        if (empty($this->getId())) {
            return null;
        }
        return $this->type->getName() . ' >> ' . $this->brand->getName() . ' >> ' . $this->model->getName() .
            ' >> ' . $this->generation->getName() . ' >> ' . $this->engine->getName();
    }

    /**
     * @return string|null
     */
    public function getBrandImage()
    {
        if (empty($this->getId())) {
            return null;
        }

        return $this->brand->getImage(true);
    }

    public function tmpDecorator()
    {
        $tunings = (new Tuning())->findAll();
        $additionalOptions = (new AdditionalOption())->findAll();
        $readMethods = (new ReadMethod())->findAll();

        /* @var Tuning $tuning */
        /* @var TuningAdditionalOption $tuningAdditionalOption */
        /* @var AdditionalOption $additionalOption */
        /* @var VehicleTuning $vehicleTuning */
        /* @var VehicleAdditionalOption $vehicleTuningOption */
        /* @var ReadMethod $readMethod */
        /* @var VehicleReadMethod $vehicleReadMethod */

        foreach ($tunings as $tKey => $tuning) {

            $tuningDTO = new TuningDTO();
            $tuningDTO->id = $tuning->getId();
            $tuningDTO->code = $tuning->getCode();
            $tuningDTO->name = $tuning->getName();
            $tuningDTO->isActive = $tuning->getIsActive();
            $tuningDTO->credit = $tuning->getCredit();

            foreach ($this->tunings as $vehicleTuning) {
                if ($vehicleTuning->getTuning()->getId() == $tuning->getId()) {
                    $tuningDTO->vehicleTuningDTO->id = $vehicleTuning->getId();
                    $tuningDTO->vehicleTuningDTO->differencePower = $vehicleTuning->getDifferencePower();
                    $tuningDTO->vehicleTuningDTO->differenceTorque = $vehicleTuning->getDifferenceTorque();
                    $tuningDTO->vehicleTuningDTO->methods = $vehicleTuning->getMethods();
                    $tuningDTO->vehicleTuningDTO->method = $vehicleTuning->getMethod();
                    $tuningDTO->vehicleTuningDTO->maxPower = $vehicleTuning->getMaxPower();
                    $tuningDTO->vehicleTuningDTO->maxTorque = $vehicleTuning->getMaxTorque();
                    $tuningDTO->vehicleTuningDTO->isActive = $vehicleTuning->getIsActive();
                    $tuningDTO->vehicleTuningDTO->credit = $vehicleTuning->getCredit();
                    $tuningDTO->vehicleTuningDTO->powerChart = $vehicleTuning->getPowerChart();
                    $tuningDTO->vehicleTuningDTO->torqueChart = $vehicleTuning->getTorqueChart();
                }
            }

            foreach ($additionalOptions as $aOKey => $additionalOption) {

                $additionalOptionDTO = new AdditionalOptionDTO();

                $additionalOptionDTO->id = $additionalOption->getId();
                $additionalOptionDTO->name = $additionalOption->getName();
                $additionalOptionDTO->code = $additionalOption->getCode();
                $additionalOptionDTO->isActive = $additionalOption->getIsActive();
                $additionalOptionDTO->image = $additionalOption->getImage();

                foreach ($tuning->getOptions() as $tuningAdditionalOption) {
                    if ($tuningAdditionalOption->getAdditionalOption()->getId() == $additionalOption->getId()) {
                        $additionalOptionDTO->tuningAdditionalOptionDTO->id = $tuningAdditionalOption->getId();
                        $additionalOptionDTO->tuningAdditionalOptionDTO->isActive = $tuningAdditionalOption->getIsActive();
                        $additionalOptionDTO->tuningAdditionalOptionDTO->credit = $tuningAdditionalOption->getCredit();

                        foreach ($this->tunings as $vehicleTuning) {
                            foreach ($vehicleTuning->getOptions() as $vehicleTuningOption) {
                                if ($vehicleTuning->getTuning()->getId() == $tuning->getId() &&
                                    $vehicleTuningOption->getTuningAdditionalOption()->getId() == $tuningAdditionalOption->getId()) {
                                    $additionalOptionDTO->tuningAdditionalOptionDTO->vehicleTuningAdditionalOptionDTO->id = $vehicleTuningOption->getId();
                                    $additionalOptionDTO->tuningAdditionalOptionDTO->vehicleTuningAdditionalOptionDTO->isActive = $vehicleTuningOption->getIsActive();
                                }
                            }

                        }
                    }
                }

                $tuningDTO->additionalOptions[$aOKey] = $additionalOptionDTO;
            }

            $this->tmpDTO['tunings'][$tKey] = $tuningDTO;

        }

        foreach ($readMethods as $rKey => $readMethod) {
            $readMethodDTO = new ReadMethodDTO();
            $readMethodDTO->id = $readMethod->getId();
            $readMethodDTO->name = $readMethod->getName();
            $readMethodDTO->surname = $readMethod->getSurname();
            $readMethodDTO->isActive = $readMethod->getIsActive();
            $readMethodDTO->image = $readMethod->getImage(true);

            foreach ($this->readMethods as $vehicleReadMethod) {
                $readMethodDTO->vehicleReadMethod->id = $vehicleReadMethod->getId();
                $readMethodDTO->vehicleReadMethod->isActive = $vehicleReadMethod->getIsActive();
            }

            $this->tmpDTO['read_methods'][$rKey] = $readMethodDTO;
        }
    }
}
