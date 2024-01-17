<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class CustomerGroup extends BaseModel
{
    private $id;
    private $code;
    private $name;
    private $type = 'decrease';
    private $status = 1;
    private $multiplier;
    private $extra;
    private $tax_rate;
    private $processType;
    private $bonusCredit;
    private $bonusCreditType = 'percent';
    private $createdAt;
    private $updatedAt;

    private $list;

    public function find($id)
    {
        return $this->findOneBy(['filter' => ['id' => $id]]);
    }

    public function findOneBy($criteria)
    {
        return $this->findBy($criteria,true);
    }

    public function findAll()
    {
        return $this->findBy([]);
    }

    public function findBy($criteria, $findOne = false)
    {
        $where = $executeData = [];

        if (isset($criteria['filter']['id'])) {
            if (is_array($criteria['filter']['id'])) {
                $where[] = 'customer_group.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'customer_group.id=:id';
                $executeData[':id'] = intval($criteria['filter']['id']);
            }
        }

        if (isset($criteria['filter']['code'])) {
            $where[] = 'customer_group.code=:code';
            $executeData[':code'] = $criteria['filter']['code'];
        }

        if (isset($criteria['filter']['status'])) {
            $where[] = 'customer_group.status=:status';
            $executeData[':status'] = $criteria['filter']['status'];
        }

        $sql = 'SELECT * FROM customer_group';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
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
     * @return CustomerGroup
     */
    public function initialize($data): CustomerGroup
    {
        return (new self())
            ->setId($data->id)
            ->setCode($data->code)
            ->setName($data->name)
            ->setStatus($data->status)
            ->setType($data->type)
            ->setProcessType($data->process_type)
            ->setMultiplier($data->multiplier)
            ->setExtra($data->extra)
            ->setTaxRate($data->tax_rate)
            ->setBonusCredit($data->bonus_credit)
            ->setBonusCreditType($data->bonus_credit_type)
            ->setCreatedAt($data->created_at)
            ->setUpdatedAt($data->updated_at);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('customer_group', [
                'code' => $this->code,
                'name' => $this->name,
                'status' => $this->status,
                'type' => $this->type,
                'process_type' => $this->processType,
                'multiplier' => $this->multiplier,
                'extra' => $this->extra,
                'tax_rate' => $this->tax_rate,
                'bonus_credit' => $this->bonusCredit,
                'bonus_credit_type' => $this->bonusCreditType,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('customer_group', [
                'id' => $this->id,
                'code' => $this->code,
                'name' => $this->name,
                'status' => $this->status,
                'type' => $this->type,
                'process_type' => $this->processType,
                'multiplier' => $this->multiplier,
                'extra' => $this->extra,
                'tax_rate' => $this->tax_rate,
                'bonus_credit' => $this->bonusCredit,
                'bonus_credit_type' => $this->bonusCreditType,
                'created_at' => date('Y-m-d H:i:s'),
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
     * @return CustomerGroup
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
     * @return CustomerGroup
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
     * @return CustomerGroup
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return CustomerGroup
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
     * @return CustomerGroup
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
     * @return CustomerGroup
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param mixed $list
     * @return CustomerGroup
     */
    public function setList($list)
    {
        $this->list = $list;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMultiplier()
    {
        return $this->multiplier;
    }

    /**
     * @param mixed $multiplier
     * @return CustomerGroup
     */
    public function setMultiplier($multiplier)
    {
        $this->multiplier = $multiplier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param mixed $extra
     * @return CustomerGroup
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxRate()
    {
        return $this->tax_rate;
    }

    /**
     * @param mixed $tax_rate
     * @return CustomerGroup
     */
    public function setTaxRate($tax_rate)
    {
        $this->tax_rate = $tax_rate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessType()
    {
        return $this->processType;
    }

    /**
     * @param mixed $processType
     * @return CustomerGroup
     */
    public function setProcessType($processType)
    {
        $this->processType = $processType;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasDiscount()
    {
        return $this->multiplier != 0;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return CustomerGroup
     */
    public function setType(string $type): CustomerGroup
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBonusCredit()
    {
        return $this->bonusCredit;
    }

    /**
     * @param mixed $bonusCredit
     * @return CustomerGroup
     */
    public function setBonusCredit($bonusCredit)
    {
        $this->bonusCredit = $bonusCredit;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBonusCreditType(): ?string
    {
        return $this->bonusCreditType;
    }

    /**
     * @param string|null $bonusCreditType
     * @return CustomerGroup
     */
    public function setBonusCreditType(?string $bonusCreditType): CustomerGroup
    {
        $this->bonusCreditType = $bonusCreditType;
        return $this;
    }

}
