<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class OrderItem extends BaseModel
{
    private $id;
    private $orderId;
    private $productId;
    private $quantity;
    private $unitsTotal;
    private $adjustments;
    private $taxRate;
    private $taxAmount;
    private $productName;
    private $totalCredit;

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
                $where[] = 'order_item.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'order_item.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['order_id'])) {
            $where[] = 'order_item.order_id=:order_id';
            $executeData[':order_id'] = $criteria['filter']['order_id'];
        }

        $sql = 'SELECT * FROM order_item';

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
                    $sql .= ' ORDER BY order_item.id ';
                    break;
                case 'updated_at':
                    $sql .= ' ORDER BY order_item.updated_at ';
                    break;
                case 'created_at':
                    $sql .= ' ORDER BY order_item.created_at ';
                    break;
                default:
                    $sql .= ' ORDER BY order_item.id ';
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
     * @return OrderItem
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setOrderId($data->order_id)
            ->setProductId($data->product_id)
            ->setQuantity($data->quantity)
            ->setUnitsTotal($data->units_total)
            ->setAdjustments($data->adjustments)
            ->setTaxRate($data->tax_rate)
            ->setTaxAmount($data->tax_amount)
            ->setProductName($data->product_name)
            ->setTotalCredit($data->total_credit);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('order_item', [
                'order_id' => $this->orderId,
                'product_id' => $this->productId,
                'quantity' => $this->quantity,
                'units_total' => $this->unitsTotal,
                'adjustments' => $this->adjustments,
                'tax_rate'    => $this->taxRate,
                'tax_amount' => $this->taxAmount,
                'product_name' => $this->productName,
                'total_credit' => $this->totalCredit
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('order_item', [
                'id' => $this->id,
                'order_id' => $this->orderId,
                'product_id' => $this->productId,
                'quantity' => $this->quantity,
                'units_total' => $this->unitsTotal,
                'adjustments' => $this->adjustments,
                'tax_rate'    => $this->taxRate,
                'tax_amount' => $this->taxAmount,
                'product_name' => $this->productName,
                'total_credit' => $this->totalCredit
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
     * @return OrderItem
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     * @return OrderItem
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param mixed $productId
     * @return OrderItem
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return OrderItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getUnitsTotal($currencyCode = null, $numberFormat = false, $withSymbol = false)
    {
        /* @var Currency $currency */
        $currency = $this->container->get('currency');

        $unitsTotal = $this->unitsTotal;

        if ($numberFormat) {
            $unitsTotal = number_format($unitsTotal, 2, ',', '.');
        }

        if ($withSymbol) {
            $unitsTotal = $currency->getList()[$currencyCode]->getSymbol() . $unitsTotal;
        }

        return $unitsTotal;
    }

    /**
     * @param mixed $unitsTotal
     * @return OrderItem
     */
    public function setUnitsTotal($unitsTotal)
    {
        $this->unitsTotal = $unitsTotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdjustments()
    {
        return $this->adjustments;
    }

    /**
     * @param mixed $adjustments
     * @return OrderItem
     */
    public function setAdjustments($adjustments)
    {
        $this->adjustments = $adjustments;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxRate()
    {
        return $this->taxRate;
    }

    /**
     * @param $taxRate
     * @return $this
     */
    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxAmount()
    {
        return $this->taxAmount;
    }

    /**
     * @param mixed $taxAmount
     * @return OrderItem
     */
    public function setTaxAmount($taxAmount)
    {
        $this->taxAmount = $taxAmount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * @param mixed $productName
     * @return OrderItem
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalCredit()
    {
        return $this->totalCredit;
    }

    /**
     * @param mixed $totalCredit
     * @return OrderItem
     */
    public function setTotalCredit($totalCredit)
    {
        $this->totalCredit = $totalCredit;
        return $this;
    }

}
