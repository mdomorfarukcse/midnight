<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class ReadMethod extends BaseModel
{
    private $id;
    private $name;
    private $surname;
    private $image;
    private $isActive;
    private $createdAt;
    private $updatedAt;

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
                $where[] = 'read_method.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'read_method.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['is_active'])) {
            $where[] = 'read_method.is_active=:is_active';
            $executeData[':is_active'] = $criteria['filter']['is_active'];
        }

        $sql = 'SELECT * FROM read_method';

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
                    $sql .= ' ORDER BY read_method.id ';
                    break;
                default:
                    $sql .= ' ORDER BY read_method.id ';
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
     * @return ReadMethod
     */
    public function initialize($data): ReadMethod
    {
        return (new self())
            ->setId($data->id)
            ->setName($data->name)
            ->setSurname($data->surname)
            ->setImage($data->image)
            ->setIsActive($data->is_active)
            ->setCreatedAt($data->created_at)
            ->setUpdatedAt($data->updated_at);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('read_method', [
                'name' => $this->name,
                'surname' => $this->surname,
                'image' => $this->image,
                'is_active' => $this->isActive,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('read_method', [
                'id' => $this->id,
                'name' => $this->name,
                'surname' => $this->surname,
                'image' => $this->image,
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
     * @return ReadMethod
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
     * @return ReadMethod
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     * @return ReadMethod
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage($fullPath = false)
    {
        $image = $this->image;

        if ($fullPath && !empty($image)) {
            $image = '/images/method/' . $image;
        }

        return $image;
    }

    /**
     * @param mixed $image
     * @return ReadMethod
     */
    public function setImage($image)
    {
        $this->image = $image;
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
     * @return ReadMethod
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
     * @return ReadMethod
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
     * @return ReadMethod
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

}
