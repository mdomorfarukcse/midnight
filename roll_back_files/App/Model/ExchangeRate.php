<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class ExchangeRate extends BaseModel
{
    private $id;
    private $base;
    private $toBeExchanged;
    private $rate;
    private $status;
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

    public function findAll($pagination = [])
    {
        return $this->findBy($pagination);
    }

    public function findBy($criteria, $findOne = false)
    {
        $where = $executeData = [];

        if (isset($criteria['filter']['id'])) {
            if (is_array($criteria['filter']['id'])) {
                $where[] = 'exchange_rate.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'exchange_rate.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        $sql = 'SELECT * FROM exchange_rate';

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
            $sql .= ' ORDER BY exchange_rate.' . $criteria['order']['field'] . ' ' . $criteria['order']['sort'];
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
     * @return ExchangeRate
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setBase($data->base)
            ->setToBeExchanged($data->to_be_exchanged)
            ->setRate($data->rate)
            ->setStatus($data->status)
            ->setUpdatedAt($data->updated_at)
            ->setCreatedAt($data->created_at);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('exchange_rate', [
                'base' => $this->base,
                'to_be_exchanged' => $this->toBeExchanged,
                'rate' => $this->rate,
                'status' => $this->status,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('exchange_rate', [
                'id' => $this->id,
                'base' => $this->base,
                'to_be_exchanged' => $this->toBeExchanged,
                'rate' => $this->rate,
                'status' => $this->status,
                'created_at' => $this->createdAt,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $this;
    }

    public function getAll()
    {
        $exchangeRates = $this->database->query('SELECT * FROM exchange_rate')->fetchAll(PDO::FETCH_OBJ);

        if (!empty($exchangeRates)) {
            foreach ($exchangeRates as $key => $exchangeRate) {
                $this->list[$exchangeRate->base][$exchangeRate->to_be_exchanged] = (new self())->setId($exchangeRate->id)
                                                                                    ->setBase($exchangeRate->base)
                                                                                    ->setToBeExchanged($exchangeRate->to_be_exchanged)
                                                                                    ->setRate($exchangeRate->rate)
                                                                                    ->setStatus($exchangeRate->status)
                                                                                    ->setCreatedAt($exchangeRate->created_at)
                                                                                    ->setUpdatedAt($exchangeRate->updated_at);


                $this->list[$exchangeRate->base][$exchangeRate->base] = (new self())->setId(0)
                                                                        ->setBase($exchangeRate->base)
                                                                        ->setToBeExchanged($exchangeRate->base)
                                                                        ->setRate(1)
                                                                        ->setStatus($exchangeRate->status);
            }
        }

        return $this->list;
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
     * @return ExchangeRate
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param mixed $base
     * @return ExchangeRate
     */
    public function setBase($base)
    {
        $this->base = $base;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToBeExchanged()
    {
        return $this->toBeExchanged;
    }

    /**
     * @param mixed $toBeExchanged
     * @return ExchangeRate
     */
    public function setToBeExchanged($toBeExchanged)
    {
        $this->toBeExchanged = $toBeExchanged;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     * @return ExchangeRate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
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
     * @return ExchangeRate
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
     * @return ExchangeRate
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
     * @return ExchangeRate
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
        if (empty($this->list))
            $this->getAll();

        return $this->list;
    }

    /**
     * @param mixed $list
     * @return ExchangeRate
     */
    public function setList($list)
    {
        $this->list = $list;
        return $this;
    }

}
