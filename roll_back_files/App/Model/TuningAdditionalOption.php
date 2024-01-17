<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class TuningAdditionalOption extends BaseModel
{
    private $id;
    private $tuningId;

    /* @var AdditionalOption */
    public $additionalOption;

    private $credit;
    private $isActive;
    private $updatedAt;
    private $createdAt;

    /* @var Tuning */
    public $tuning;

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
                $where[] = 'tuning_additional_options.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'tuning_additional_options.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['tuning_id'])) {
            $where[] = 'tuning_additional_options.tuning_id=:tuning_id';
            $executeData[':tuning_id'] = $criteria['filter']['tuning_id'];
        }

        if (isset($criteria['filter']['additional_option_id'])) {
            $where[] = 'tuning_additional_options.additional_option_id=:additional_option_id';
            $executeData[':additional_option_id'] = $criteria['filter']['additional_option_id'];
        }

        $sql = 'SELECT * FROM tuning_additional_options';

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

        if (!empty($criteria['order']['field']) && !empty($criteria['order']['sort'])) {
            $sql .= ' ORDER BY tuning_additional_options.' . $criteria['order']['field'] . ' ' . $criteria['order']['sort'];
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
     * @return TuningAdditionalOption
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setTuningId($data->tuning_id)
            ->setAdditionalOption((new AdditionalOption())->find($data->additional_option_id))
            ->setCredit($data->credit)
            ->setIsActive($data->is_active)
            ->setUpdatedAt($data->updated_at)
            ->setCreatedAt($data->created_at);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('tuning_additional_options', [
                'tuning_id' => $this->tuningId,
                'additional_option_id' => $this->additionalOption->getId(),
                'credit' => $this->credit,
                'is_active' => $this->isActive,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('tuning_additional_options', [
                'id' => $this->id,
                'tuning_id' => $this->tuningId,
                'additional_option_id' => $this->additionalOption->getId(),
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
     * @return TuningAdditionalOption
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTuningId()
    {
        return $this->tuningId;
    }

    /**
     * @param mixed $tuningId
     * @return TuningAdditionalOption
     */
    public function setTuningId($tuningId)
    {
        $this->tuningId = $tuningId;
        return $this;
    }

    /**
     * @return AdditionalOption
     */
    public function getAdditionalOption(): AdditionalOption
    {
        return $this->additionalOption;
    }

    /**
     * @param AdditionalOption $additionalOption
     * @return TuningAdditionalOption
     */
    public function setAdditionalOption(AdditionalOption $additionalOption): TuningAdditionalOption
    {
        $this->additionalOption = $additionalOption;
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
     * @return TuningAdditionalOption
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;
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
     * @return TuningAdditionalOption
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
     * @return TuningAdditionalOption
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
     * @return TuningAdditionalOption
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function isActive()
    {
        return !empty($this->isActive);
    }

    /**
     * @return array|Tuning|void
     */
    public function getTuning()
    {
        return $this->tuning = (new Tuning())->find($this->tuningId);
    }

}
