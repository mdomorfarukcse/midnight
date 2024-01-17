<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class Tuning extends BaseModel
{
    private $id;
    private $code;
    private $name;
    private $isActive;
    private $credit;
    private $sortOrder;
    private $updatedAt;
    private $createdAt;

    public $options = [];

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
                $where[] = 'tuning.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'tuning.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['is_active'])) {
            $where[] = 'tuning.is_active=:is_active';
            $executeData[':is_active'] = $criteria['filter']['is_active'];
        }

        $sql = 'SELECT * FROM tuning';

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
                    $sql .= ' ORDER BY tuning.id ';
                    break;
                    case 'sort_order':
                        $sql .= ' ORDER BY tuning.sort_order ';
                        break;
                case 'updated_at':
                    $sql .= ' ORDER BY tuning.updated_at ';
                    break;
                case 'created_at':
                    $sql .= ' ORDER BY tuning.created_at ';
                    break;
                default:
                    $sql .= ' ORDER BY tuning.id ';
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
     * @return Tuning
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setCode($data->code)
            ->setName($data->name)
            ->setIsActive($data->is_active)
            ->setCredit($data->credit)
            ->setSortOrder($data->sort_order)
            ->setUpdatedAt($data->updated_at)
            ->setCreatedAt($data->created_at);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('tuning', [
                'code' => $this->code,
                'name' => $this->name,
                'credit' => $this->credit,
                'sort_order' => $this->sortOrder,
                'is_active' => $this->isActive,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('tuning', [
                'id' => $this->id,
                'code' => $this->code,
                'name' => $this->name,
                'sort_order' => $this->sortOrder,
                'credit' => $this->credit,
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
     * @return Tuning
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
     * @return Tuning
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @return Tuning
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return Tuning
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCredit()
    {

        $credit = $this->credit;

        /* @var Customer $customer */
        $customer = $this->container->get('customer');

        if (!empty($customer) && $customer->hasCustomerGroupIncrease()) {
            $credit = $customer->calculatePriceIncreaseForCustomerGroup($credit);
        }

        return $credit;
    }

    /**
     * @param mixed $credit
     * @return Tuning
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;
        return $this;
    }
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param mixed $sortOrder
     * @return Tuning
     */
    public function setSortOrder($sort_order)
    {
        $this->sortOrder = $sort_order;
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
     * @return Tuning
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
     * @return Tuning
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getOptions()
    {
        return $this->options = (new TuningAdditionalOption())->findBy([
          'filter' => ['tuning_id' => $this->id]
        ]);
    }


    public function saveOption($options)
    {
        $this->resetOptions();

        if (!empty($options)) {

            foreach ($options as $oid => $od) {

                $tuningOption = new TuningAdditionalOption();

                /* @var TuningAdditionalOption $option */
                foreach ($this->options as $option) {
                    if ($option->getAdditionalOption()->getId() == $oid) {
                        $tuningOption = $option;
                    }
                }

                $tuningOption
                    ->setTuningId($this->id)
                    ->setAdditionalOption((new AdditionalOption())->find($oid))
                    ->setCredit(@intval($od['credit']))
                    ->setIsActive(!empty($od['is_active']) ? 1 : 0)
                    ->store();
            }
        }
    }

    public function resetOptions()
    {
        $prepare = $this->database->prepare('UPDATE tuning_additional_options SET is_active=:is_active WHERE tuning_id=:tuning_id');
        $prepare->execute([
            ':tuning_id' => $this->id,
            ':is_active' => 0
        ]);
    }
}
