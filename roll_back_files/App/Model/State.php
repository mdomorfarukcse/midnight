<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class State extends BaseModel
{
    private $id;
    private $name;
    private $countryId;
    private $isActive;

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
                $where[] = 'states.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'states.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['is_active'])) {
            $where[] = 'states.is_active=:is_active';
            $executeData[':is_active'] = $criteria['filter']['is_active'];
        }

        if (isset($criteria['filter']['country_id'])) {
            $where[] = 'states.state_id=:country_id';
            $executeData[':country_id'] = $criteria['filter']['country_id'];
        }


        $sql = 'SELECT * FROM states';

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
                    $sql .= ' ORDER BY states.id ';
                    break;
                default:
                    $sql .= ' ORDER BY states.id ';
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
     * @return State
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setName($data->sortname)
            ->setCountryId($data->country_id)
            ->setIsActive($data->is_active);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('states', [
                'name' => $this->name,
                'country_id' => $this->countryId,
                'is_active' => $this->isActive
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('states', [
                'id' => $this->id,
                'name' => $this->name,
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
     * @return State
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
     * @return State
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
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
     * @return State
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;
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
     * @return State
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getByCountryIdForSelectBox($countryId)
    {
        $prepare = $this->database->prepare('SELECT id, name FROM states WHERE country_id=:country_id AND is_active=1');
        $prepare->execute([':country_id' => $countryId]);

        return $prepare->fetchAll();
    }

}
