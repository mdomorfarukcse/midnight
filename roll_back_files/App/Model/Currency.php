<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class Currency extends BaseModel
{
    private $id;
    private $code;
    private $name;
    private $symbol;
    private $lastUpdate;
    private $autoUpdate;
    private $status;

    private $list;
    private $exchangeRates;

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
                $where[] = 'currency.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'currency.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['is_active'])) {
            $where[] = 'currency.is_active=:is_active';
            $executeData[':is_active'] = $criteria['filter']['is_active'];
        }

        $sql = 'SELECT * FROM currency';

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
            $sql .= ' ORDER BY currency.' . $criteria['order']['field'] . ' ' . $criteria['order']['sort'];
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
     * @return Currency
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setCode($data->code)
            ->setName($data->name)
            ->setSymbol($data->symbol)
            ->setAutoUpdate($data->auto_update)
            ->setLastUpdate($data->last_update)
            ->setStatus($data->status);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('currency', [
                'code' => $this->code,
                'name' => $this->name,
                'symbol' => $this->symbol,
                'auto_update' => $this->autoUpdate ?? 0,
                'status' => $this->status,
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('currency', [
                'id' => $this->id,
                'code' => $this->code,
                'name' => $this->name,
                'symbol' => $this->symbol,
                'last_update' => $this->lastUpdate,
                'auto_update' => $this->autoUpdate,
                'status' => $this->status,
            ]);
        }

        return $this;
    }

    public function getAll()
    {
        $currencies = $this->database->query('SELECT * FROM currency')->fetchAll(PDO::FETCH_OBJ);

        $exchangeRate = new ExchangeRate();
        $exchangeRates = $exchangeRate->getList();

        if (!empty($currencies)) {
            foreach ($currencies as $key => $currency) {
                $this->list[$currency->code] = (new Currency())->setId($currency->id)
                                                            ->setCode($currency->code)
                                                            ->setName($currency->name)
                                                            ->setSymbol($currency->symbol)
                                                            ->setStatus($currency->status)
                                                            ->setLastUpdate($currency->last_update)
                                                            ->setAutoUpdate($currency->auto_update)
                                                            ->setExchangeRates($exchangeRates[$currency->code] ?? []);
            }
        }

        return $this->list;

    }

    public function getActives()
    {
        $currencies = $this->database->query('SELECT * FROM currency WHERE status = 1')->fetchAll(PDO::FETCH_OBJ);

        $exchangeRate = new ExchangeRate();
        $exchangeRates = $exchangeRate->getList();

        if (!empty($currencies)) {
            foreach ($currencies as $key => $currency) {
                $this->list[$currency->code] = (new Currency())->setId($currency->id)
                    ->setCode($currency->code)
                    ->setName($currency->name)
                    ->setSymbol($currency->symbol)
                    ->setLastUpdate($currency->last_update)
                    ->setAutoUpdate($currency->auto_update)
                    ->setExchangeRates($exchangeRates[$currency->code] ?? []);
            }
        }

        return $this->list;

    }

    public function get($code)
    {
        return $this->getList()[$code];
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
     * @return Currency
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
     * @return Currency
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
     * @return Currency
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @param mixed $symbol
     * @return Currency
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * @param mixed $lastUpdate
     * @return Currency
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAutoUpdate()
    {
        return $this->autoUpdate;
    }

    /**
     * @param mixed $autoUpdate
     * @return Currency
     */
    public function setAutoUpdate($autoUpdate)
    {
        $this->autoUpdate = $autoUpdate;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        if (empty($this->list))
            $this->list = $this->getAll();

        return $this->list;
    }

    /**
     * @param mixed $list
     * @return Currency
     */
    public function setList($list)
    {
        $this->list = $list;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExchangeRates()
    {
        return $this->exchangeRates;
    }

    /**
     * @param mixed $exchangeRates
     * @return Currency
     */
    public function setExchangeRates($exchangeRates)
    {
        $this->exchangeRates = $exchangeRates;
        return $this;
    }

    public function exchange($price, $baseCurrency, $toBeExchanged)
    {
        return $price * $this->list[$baseCurrency->getCode()]->getExchangeRates()[$toBeExchanged->getCode()]->getRate();
    }
}
