<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class VehicleReadMethod extends BaseModel
{
    private $id;
    private $vehicleId;

    /* @var ReadMethod */
    public $readMethod;

    private $isActive;
    private $createdAt;
    private $updatedAt;

    /* @var Vehicle */
    public $vehicle;

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
                $where[] = 'vehicle_read_method.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'vehicle_read_method.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['vehicle_id'])) {
            $where[] = 'vehicle_read_method.vehicle_id=:vehicle_id';
            $executeData[':vehicle_id'] = $criteria['filter']['vehicle_id'];
        }

        if (isset($criteria['filter']['is_active'])) {
            $where[] = 'vehicle_read_method.is_active=:is_active';
            $executeData[':is_active'] = $criteria['filter']['is_active'];
        }

        $sql = 'SELECT * FROM vehicle_read_method';

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
                    $sql .= ' ORDER BY vehicle_read_method.id ';
                    break;
                default:
                    $sql .= ' ORDER BY vehicle_read_method.id ';
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
     * @return VehicleReadMethod
     */
    public function initialize($data): VehicleReadMethod
    {
        return (new self())
            ->setId($data->id)
            ->setVehicleId($data->vehicle_id)
            ->setReadMethod((new ReadMethod())->find($data->read_method_id))
            ->setIsActive($data->is_active)
            ->setCreatedAt($data->created_at)
            ->setUpdatedAt($data->updated_at);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('vehicle_read_method', [
                'vehicle_id' => $this->vehicleId,
                'read_method_id' => $this->readMethod->getId(),
                'is_active' => $this->isActive,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('vehicle_read_method', [
                'id' => $this->id,
                'vehicle_id' => $this->vehicleId,
                'read_method_id' => $this->readMethod->getId(),
                'is_active' => $this->isActive,
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
     * @return VehicleReadMethod
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
     * @return VehicleReadMethod
     */
    public function setVehicleId($vehicleId)
    {
        $this->vehicleId = $vehicleId;
        return $this;
    }

    /**
     * @return ReadMethod
     */
    public function getReadMethod(): ReadMethod
    {
        return $this->readMethod;
    }

    /**
     * @param ReadMethod $readMethod
     * @return VehicleReadMethod
     */
    public function setReadMethod(ReadMethod $readMethod): VehicleReadMethod
    {
        $this->readMethod = $readMethod;
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
     * @return VehicleReadMethod
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
     * @return VehicleReadMethod
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
     * @return VehicleReadMethod
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return array|Vehicle|void
     */
    public function getVehicle()
    {
        return $this->vehicle = (new Vehicle())->find($this->vehicleId);
    }

}
