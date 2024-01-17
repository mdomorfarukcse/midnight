<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class AdditionalOption extends BaseModel
{
    private $id;
    private $name;
    private $code;
    private $layer;
    private $isActive;
    private $image;
    private $createdAt;
    private $updatedAt;
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
                $where[] = 'additional_option.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'additional_option.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['is_active'])) {
            $where[] = 'additional_option.is_active=:is_active';
            $executeData[':is_active'] = $criteria['filter']['is_active'];
        }

        $sql = 'SELECT * FROM additional_option';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        if (isset($criteria['pagination'])) {

            try {

                $prepare = $this->database->prepare($sql);
                $prepare->execute($executeData);

                $criteria['pagination']['total_count'] = $prepare->rowCount();

            } catch (\Exception $e) {}

        }

        if (isset($criteria['order'])) {
            switch ($criteria['order']['field']) {
                case 'id':
                    $sql .= ' ORDER BY additional_option.id ';
                    break;
                default:
                    $sql .= ' ORDER BY additional_option.id ';
                    $criteria['order']['sort'] = 'ASC';
                    break;
            }
            $sql .= $criteria['order']['sort'];
        }

        if (isset($criteria['pagination'])) {

            if (isset($criteria['pagination']['limit'])) {
                $sql .= ' LIMIT ' . $criteria['pagination']['limit'];
                $criteria['pagination']['total_page'] = ceil($criteria['pagination']['total_count'] / $criteria['pagination']['limit']);

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
     * @return AdditionalOption
     */
    public function initialize($data): AdditionalOption
    {
        return (new self())
            ->setId($data->id)
            ->setName($data->name)
            ->setCode($data->code)
            ->setLayer($data->layer)
            ->setImage($data->image)
            ->setIsActive($data->is_active)
            ->setCreatedAt($data->created_at)
            ->setUpdatedAt($data->updated_at)
            ->setBase($data->_id);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('additional_option', [
                'name' => $this->name,
                'code' => $this->code,
                'layer' => $this->layer,
                'image' => $this->image,
                'is_active' => $this->isActive,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                '_id' => $this->_base
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('additional_option', [
                'id' => $this->id,
                'name' => $this->name,
                'code' => $this->code,
                'layer' => $this->layer,
                'image' => $this->image,
                'is_active' => $this->isActive,
                'created_at' => $this->createdAt,
                'updated_at' => date('Y-m-d H:i:s'),
                '_id' => $this->_base
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
     * @return AdditionalOption
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage($fullPath = false)
    {
        $image = $this->image;

        if ($fullPath) {
            $image = '/images/option/' . $image;
        }

        return $image;
    }

    /**
     * @param mixed $image
     * @return AdditionalOption
     */
    public function setImage($image)
    {
        $this->image = $image;
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
     * @return AdditionalOption
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return AdditionalOption
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLayer()
    {
        return $this->layer;
    }

    /**
     * @param mixed $layer
     * @return AdditionalOption
     */
    public function setLayer($layer)
    {
        $this->layer = $layer;
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
     * @return AdditionalOption
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
     * @return AdditionalOption
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
     * @return AdditionalOption
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
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
    public function setBase($base): AdditionalOption
    {
        $this->_base = $base;

        return $this;
    }

}
