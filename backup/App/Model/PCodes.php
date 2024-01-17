<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class PCodes extends BaseModel
{
    private $id;
    private $code;
    private $description;
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
                $where[] = 'p_codes.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'p_codes.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['is_active'])) {
            $where[] = 'p_codes.is_active=:is_active';
            $executeData[':is_active'] = $criteria['filter']['is_active'];
        }

        if (!empty($criteria['filter']['query'])) {
            $explode = explode(' ', $criteria['filter']['query']);
            foreach ($explode as $_ex) {
                $where[] = '(LOWER(p_codes.code) LIKE LOWER("%' . $_ex . '%") OR LOWER(p_codes.description) LIKE LOWER("%' . $_ex . '%"))';
            }
        }

        if (!empty($criteria['filter']['datatable_query'])) {
            $q = $criteria['filter']['datatable_query'];
            $where[] = '(LOWER(p_codes.code) LIKE LOWER("%' . $q . '%") OR LOWER(p_codes.description) LIKE LOWER("%' . $q . '%"))';
        }

        $sql = 'SELECT * FROM p_codes';

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
            $sql .= ' ORDER BY p_codes.' . $criteria['order']['field'] . ' ' . $criteria['order']['sort'];
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
     * @return PCodes
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setCode($data->code)
            ->setDescription($data->description)
            ->setIsActive($data->is_active)
            ->setUpdatedAt($data->updated_at)
            ->setCreatedAt($data->created_at);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('p_codes', [
                'code' => $this->code,
                'is_active' => $this->isActive,
                'description' => $this->description,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('p_codes', [
                'id' => $this->id,
                'code' => $this->code,
                'description' => $this->description,
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
     * @return PCodes
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return PCodes
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return PCodes
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * @return PCodes
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
     * @return PCodes
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
     * @return PCodes
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

}
