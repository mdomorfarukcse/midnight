<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class Country extends BaseModel
{
    private $id;
    private $sortName;
    private $name;
    private $phoneCode;
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
                $where[] = 'countries.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'countries.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['is_active'])) {
            $where[] = 'countries.is_active=:is_active';
            $executeData[':is_active'] = $criteria['filter']['is_active'];
        }


        $sql = 'SELECT * FROM countries';

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
                    $sql .= ' ORDER BY countries.id ';
                    break;
                default:
                    $sql .= ' ORDER BY countries.id ';
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
     * @return Country
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setSortName($data->sortname)
            ->setName($data->name)
            ->setIsActive($data->is_active)
            ->setPhoneCode($data->phonecode);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('countries', [
                'sortname' => $this->sortName,
                'name' => $this->name,
                'phonecode' => $this->phoneCode,
                'is_active' => $this->isActive
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('countries', [
                'id' => $this->id,
                'sortname' => $this->sortName,
                'name' => $this->name,
                'phonecode' => $this->phoneCode,
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
     * @return Country
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSortName()
    {
        return $this->sortName;
    }

    /**
     * @param mixed $sortName
     * @return Country
     */
    public function setSortName($sortName)
    {
        $this->sortName = $sortName;
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
     * @return Country
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoneCode()
    {
        return $this->phoneCode;
    }

    /**
     * @param mixed $phoneCode
     * @return Country
     */
    public function setPhoneCode($phoneCode)
    {
        $this->phoneCode = $phoneCode;
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
     * @return Country
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getForSelectBox()
    {
        return $this->database->query('SELECT id, name FROM countries WHERE is_active=1')->fetchAll(PDO::FETCH_OBJ);
    }

    public function forSelect2($query)
    {
        $result = [];

        if (!empty($query)) {
            $explode = explode(' ', $query);
            $explode = array_filter($explode);
            $where = [];
            foreach ($explode as $_ex) {
                $where[] = 'LOWER(countries.name) LIKE LOWER("%' . $_ex . '%")';
            }

            if (!empty($where)) {
                $sql = 'SELECT id, name as text FROM countries WHERE ' . implode(' AND ', $where);
                $prepare = $this->database->prepare($sql);
                $prepare->execute();
                $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
            }

        }

        return $result;
    }

    public function getCitiesForSelect()
    {
        $prepare = $this->database->prepare('SELECT id, name FROM cities WHERE country_id=:country_id');
        $prepare->execute([':country_id' => $this->id]);

        return $prepare->fetchAll();
    }
}
