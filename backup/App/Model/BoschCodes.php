<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class BoschCodes extends BaseModel
{
    private $id;
    private $manufacturerNumber;
    private $ecu;
    private $ecuBrand;
    private $isActive;
    private $updatedAt;
    private $createdAt;

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
                $where[] = 'bosch_codes.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'bosch_codes.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['is_active'])) {
            $where[] = 'bosch_codes.is_active=:is_active';
            $executeData[':is_active'] = $criteria['filter']['is_active'];
        }

        if (isset($criteria['filter']['ecu'])) {
            if (is_array($criteria['filter']['ecu'])) {
                $where[] = 'bosch_codes.ecu IN (' . implode(',', $criteria['filter']['ecu']) . ')';
            } else {
                $where[] = 'bosch_codes.ecu=:ecu';
                $executeData[':ecu'] = $criteria['filter']['ecu'];
            }
        }

        if (isset($criteria['filter']['ecu_brand'])) {
            if (is_array($criteria['filter']['ecu_brand'])) {
                $where[] = 'bosch_codes.ecu_brand IN (' . implode(',', $criteria['filter']['ecu_brand']) . ')';
            } else {
                $where[] = 'bosch_codes.ecu=:ecu_brand';
                $executeData[':ecu_brand'] = $criteria['filter']['ecu_brand'];
            }
        }

        if (!empty($criteria['filter']['query'])) {
            $explode = explode(' ', $criteria['filter']['query']);
            foreach ($explode as $_ex) {
                $where[] = '(LOWER(bosch_codes.manufacturer_number) LIKE LOWER("%' . $_ex . '%") OR LOWER(bosch_codes.ecu_brand) LIKE LOWER("%' . $_ex . '%") OR LOWER(bosch_codes.ecu) LIKE LOWER("%' . $_ex . '%"))';
            }
        }

        if (!empty($criteria['filter']['datatable_query'])) {
            $q = $criteria['filter']['datatable_query'];
            $where[] = '(LOWER(bosch_codes.manufacturer_number) LIKE LOWER("%' . $q . '%") OR LOWER(bosch_codes.ecu_brand) LIKE LOWER("%' . $q . '%") OR LOWER(bosch_codes.ecu) LIKE LOWER("%' . $q . '%"))';
        }

        $sql = 'SELECT * FROM bosch_codes';

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
            $sql .= ' ORDER BY bosch_codes.' . $criteria['order']['field'] . ' ' . $criteria['order']['sort'];
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
     * @return BoschCodes
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setManufacturerNumber($data->manufacturer_number)
            ->setEcu($data->ecu)
            ->setEcuBrand($data->ecu_brand)
            ->setIsActive($data->is_active)
            ->setUpdatedAt($data->updated_at)
            ->setCreatedAt($data->created_at);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('bosch_codes', [
                'manufacturer_number' => $this->manufacturerNumber,
                'ecu' => $this->ecu,
                'ecu_brand' => $this->ecuBrand,
                'is_active' => $this->isActive,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('bosch_codes', [
                'id' => $this->id,
                'manufacturer_number' => $this->manufacturerNumber,
                'ecu' => $this->ecu,
                'ecu_brand' => $this->ecuBrand,
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
     * @return BoschCodes
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getManufacturerNumber()
    {
        return $this->manufacturerNumber;
    }

    /**
     * @param mixed $manufacturerNumber
     * @return BoschCodes
     */
    public function setManufacturerNumber($manufacturerNumber)
    {
        $this->manufacturerNumber = $manufacturerNumber;
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
     * @return BoschCodes
     */
    public function setEcu($ecu)
    {
        $this->ecu = $ecu;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEcuBrand()
    {
        return $this->ecuBrand;
    }

    /**
     * @param mixed $ecuBrand
     * @return BoschCodes
     */
    public function setEcuBrand($ecuBrand)
    {
        $this->ecuBrand = $ecuBrand;
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
     * @return BoschCodes
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
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
     * @return BoschCodes
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
     * @return BoschCodes
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

}
