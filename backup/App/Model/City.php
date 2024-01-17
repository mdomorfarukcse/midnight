<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;

use PDO;

class City extends BaseModel
{
    private $id;
    private $name;
    private $stateId;
    private $countryId;
    private $isActive;

    /* @var Country */
    public $country;

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
                $where[] = 'cities.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'cities.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['is_active'])) {
            $where[] = 'cities.is_active=:is_active';
            $executeData[':is_active'] = $criteria['filter']['is_active'];
        }

        if (isset($criteria['filter']['state_id'])) {
            $where[] = 'cities.state_id=:state_id';
            $executeData[':state_id'] = $criteria['filter']['state_id'];
        }

        if (isset($criteria['filter']['country_id'])) {
            $where[] = 'cities.country_id=:country_id';
            $executeData[':country_id'] = $criteria['filter']['country_id'];
        }

        if (!empty($criteria['filter']['datatable_query'])) {
            $explode = explode(' ', $criteria['filter']['datatable_query']);
            $explode = array_filter($explode);
            foreach ($explode as $_ex) {
                $where[] = 'LOWER(cities.name) LIKE LOWER("%' . $_ex . '%")';
            }
        }

        $sql = 'SELECT * FROM cities';

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
            $sql .= ' ORDER BY cities.' . $criteria['order']['field'] . ' ' . $criteria['order']['sort'];
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
     * @return City
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setName($data->name)
            ->setIsActive($data->is_active)
            ->setCountryId($data->country_id)
            ->setStateId($data->state_id);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('cities', [
                'name' => $this->name,
                'state_id' => $this->stateId,
                'country_id' => $this->countryId,
                'is_active' => $this->isActive
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('cities', [
                'id' => $this->id,
                'name' => $this->name,
                'state_id' => $this->stateId,
                'country_id' => $this->countryId,
                'is_active' => $this->isActive
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
     * @return City
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return City
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStateId()
    {
        return $this->stateId;
    }

    /**
     * @param mixed $stateId
     * @return City
     */
    public function setStateId($stateId)
    {
        $this->stateId = $stateId;
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
     * @return mixed
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * @param mixed $countryId
     * @return City
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * @param mixed $isActive
     * @return City
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getByStateIdForSelectBox($stateId)
    {
        $prepare = $this->database->prepare('SELECT id, name FROM cities WHERE state_id=:state_id AND is_active=1');
        $prepare->execute([':state_id' => $stateId]);

        return $prepare->fetchAll();
    }

    public function getCountry()
    {
        return $this->country = (new Country())->find($this->countryId);
    }

}
