<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class VehicleTuning extends BaseModel
{
    private $id;
    private $vehicleId;

    /* @var Tuning */
    public $tuning;

    private $differencePower = 0;
    private $differenceTorque = 0;
    private $methods;
    private $method;
    private $maxPower = 0;
    private $maxTorque = 0;
    private $isActive = 1;
    private $credit;
    private $createdAt;
    private $updatedAt;

    public $options = [];

    /* @var Vehicle */
    public $vehicle;

    public $powerChart = null;
    public $torqueChart = null;

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
                $where[] = 'vehicle_tuning.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'vehicle_tuning.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['vehicle_id'])) {
            $where[] = 'vehicle_tuning.vehicle_id=:vehicle_id';
            $executeData[':vehicle_id'] = $criteria['filter']['vehicle_id'];
        }

        if (isset($criteria['filter']['tuning_id'])) {
            $where[] = 'vehicle_tuning.tuning_id=:tuning_id';
            $executeData[':tuning_id'] = $criteria['filter']['tuning_id'];
        }

        if (isset($criteria['filter']['is_active'])) {
            $where[] = 'vehicle_tuning.is_active=:is_active';
            $executeData[':is_active'] = $criteria['filter']['is_active'];
        }

        $sql = 'SELECT * FROM vehicle_tuning';

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
            $sql .= ' ORDER BY vehicle_tuning.' . $criteria['order']['field'] . ' ' . $criteria['order']['sort'];
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
     * @return VehicleTuning
     */
    public function initialize($data): VehicleTuning
    {
        return (new self())
            ->setId($data->id)
            ->setVehicleId($data->vehicle_id)
            ->setTuning((new Tuning())->find($data->tuning_id))
            ->setDifferencePower($data->difference_power)
            ->setDifferenceTorque($data->difference_torque)
            ->setMethods($data->methods)
            ->setMethod($data->method)
            ->setMaxPower($data->max_power)
            ->setMaxTorque($data->max_torque)
            ->setIsActive($data->is_active)
            ->setCredit($data->credit)
            ->setPowerChart($data->power_chart)
            ->setTorqueChart($data->torque_chart)
            ->setCreatedAt($data->created_at)
            ->setUpdatedAt($data->updated_at);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('vehicle_tuning', [
                'vehicle_id' => $this->vehicleId,
                'tuning_id' => $this->tuning->getId(),
                'difference_power' => $this->differencePower,
                'difference_torque' => $this->differenceTorque,
                'methods' => $this->methods,
                'method' => $this->method,
                'max_power' => $this->maxPower,
                'max_torque' => $this->maxTorque,
                'is_active' => $this->isActive,
                'credit' => $this->credit,
                'power_chart' => $this->powerChart,
                'torque_chart' => $this->torqueChart,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('vehicle_tuning', [
                'id' => $this->id,
                'vehicle_id' => $this->vehicleId,
                'tuning_id' => $this->tuning->getId(),
                'difference_power' => $this->differencePower,
                'difference_torque' => $this->differenceTorque,
                'methods' => $this->methods,
                'method' => $this->method,
                'max_power' => $this->maxPower,
                'max_torque' => $this->maxTorque,
                'is_active' => $this->isActive,
                'power_chart' => $this->powerChart,
                'torque_chart' => $this->torqueChart,
                'credit' => $this->credit,
                'created_at' => $this->createdAt,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

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
     * @return VehicleTuning
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVehicleId()
    {
        return $this->vehicleId;
    }

    /**
     * @param mixed $vehicleId
     * @return VehicleTuning
     */
    public function setVehicleId($vehicleId)
    {
        $this->vehicleId = $vehicleId;
        return $this;
    }

    /**
     * @return Tuning
     */
    public function getTuning(): Tuning
    {
        return $this->tuning;
    }

    /**
     * @param Tuning|null $tuning
     * @return VehicleTuning
     */
    public function setTuning(?Tuning $tuning): VehicleTuning
    {
        $this->tuning = $tuning;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDifferencePower()
    {
        return $this->differencePower;
    }

    /**
     * @param mixed $differencePower
     * @return VehicleTuning
     */
    public function setDifferencePower($differencePower)
    {
        $this->differencePower = $differencePower;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDifferenceTorque()
    {
        return $this->differenceTorque;
    }

    /**
     * @param mixed $differenceTorque
     * @return VehicleTuning
     */
    public function setDifferenceTorque($differenceTorque)
    {
        $this->differenceTorque = $differenceTorque;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param mixed $methods
     * @return VehicleTuning
     */
    public function setMethods($methods)
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     * @return VehicleTuning
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxPower()
    {
        return $this->maxPower;
    }

    /**
     * @param mixed $maxPower
     * @return VehicleTuning
     */
    public function setMaxPower($maxPower)
    {
        $this->maxPower = $maxPower;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxTorque()
    {
        return $this->maxTorque;
    }

    /**
     * @param mixed $maxTorque
     * @return VehicleTuning
     */
    public function setMaxTorque($maxTorque)
    {
        $this->maxTorque = $maxTorque;
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
     * @return VehicleTuning
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
     * @return VehicleTuning
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
     * @return VehicleTuning
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * @param mixed $credit
     * @return VehicleTuning
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;
        return $this;
    }

    public function getOptions()
    {
        return $this->options = (new VehicleAdditionalOption())->findBy(['filter' => ['vehicle_id' => $this->vehicleId, 'tuning_id' => $this->id]]);
    }

    public function getVehicle()
    {
        return $this->vehicle = (new Vehicle())->find($this->vehicleId);
    }

    public function getTuningAdditionalOptionsIds()
    {
        $result = [];
        if (!empty($this->options)) {
            /* @var VehicleAdditionalOption $option */
            foreach ($this->options as $option) {
                if ($option->getIsActive()) {
                    $result[] = $option->getTuningAdditionalOption()->getId();
                }
            }
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function hasPowerChart()
    {
        return !empty($this->powerChart);
    }

    /**
     * @return mixed
     */
    public function getPowerChart()
    {
        return $this->powerChart;
    }

    /**
     * @param mixed $powerChart
     * @return VehicleTuning
     */
    public function setPowerChart($powerChart)
    {
        $this->powerChart = $powerChart;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasTorqueChart()
    {
        return !empty($this->torqueChart);
    }

    /**
     * @return mixed
     */
    public function getTorqueChart()
    {
        return $this->torqueChart;
    }

    /**
     * @param mixed $torqueChart
     * @return VehicleTuning
     */
    public function setTorqueChart($torqueChart)
    {
        $this->torqueChart = $torqueChart;
        return $this;
    }

}
