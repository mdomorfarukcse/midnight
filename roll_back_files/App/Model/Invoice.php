<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class Invoice extends BaseModel
{
    private $id;
    private $number;
    private $customerId;

    /* @var Order */
    public $order;

    private $status;
    private $file;
    private $createdAt;
    private $updatedAt;

    /* @var Customer */
    public $customer;

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
                $where[] = 'invoice.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'invoice.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['customer_id'])) {
            $where[] = 'invoice.customer_id=:customer_id';
            $executeData[':customer_id'] = $criteria['filter']['customer_id'];
        }

        if (isset($criteria['filter']['order_id'])) {
            $where[] = 'invoice.order_id=:order_id';
            $executeData[':order_id'] = $criteria['filter']['order_id'];
        }

        $sql = 'SELECT invoice.* FROM invoice';

        if (!empty($criteria['filter']['datatable_query'])) {
            $q = $criteria['filter']['datatable_query'];
            $sql .= ' LEFT JOIN customer ON customer.id=invoice.customer_id ';
            $where[] = '(CONCAT(customer.first_name, " ",  customer.last_name) LIKE "%' . $q . '%" OR invoice.number LIKE "%' . $q . '%")';
        }

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        if (isset($criteria['pagination'])) {

            try {

                $prepare = $this->database->prepare($sql);
                $prepare->execute($executeData);
                $this->queryTotalCount = $prepare->rowCount();
                $criteria['pagination']['total_count'] = $prepare->rowCount();

            } catch (\Exception $e) {}

        }

        if (isset($criteria['order'])) {
            switch ($criteria['order']['field']) {
                case 'id':
                    $sql .= ' ORDER BY invoice.id ';
                    break;
                case 'updated_at':
                    $sql .= ' ORDER BY invoice.updated_at ';
                    break;

                    case 'number':
                        $sql .= ' ORDER BY invoice.number ';
                        break;

                  case 'order_id':
                      $sql .= ' ORDER BY invoice.order_id ';
                      break;

                  case 'status':
                      $sql .= ' ORDER BY invoice.status ';
                      break;

                case 'created_at':
                    $sql .= ' ORDER BY invoice.created_at ';
                    break;
                default:
                    $sql .= ' ORDER BY invoice.id ';
                    $criteria['order']['sort'] = 'ASC';
                    break;
            }
            $sql .= $criteria['order']['sort'];
        }

        if (isset($criteria['pagination'])) {

            if (isset($criteria['pagination']['limit'])) {
                $sql .= ' LIMIT ' . $criteria['pagination']['limit'];
                $criteria['pagination']['total_page'] = ceil($criteria['pagination']['total_count'] / $criteria['pagination']['limit']);
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
                        if(!isset($data)) continue;
                        $result[$key] = $this->initialize($data);
                    }
                }
            }

        } catch (\Exception $e) {print_r($e);die;}

        return $result;
    }

    /**
     * @param $data
     * @return Invoice
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setNumber($data->number)
            ->setCustomerId($data->customer_id)
            ->setOrder((new Order())->find($data->order_id))
            ->setStatus($data->status)
            ->setFile($data->file)
            ->setUpdatedAt($data->updated_at)
            ->setCreatedAt($data->created_at);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('invoice', [
                'number' => $this->number,
                'customer_id' => $this->customerId,
                'order_id' => $this->order->getId(),
                'status' => $this->status,
                'file' => $this->file,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('invoice', [
                'id' => $this->id,
                'number' => $this->number,
                'customer_id' => $this->customerId,
                'order_id' => $this->order->getId(),
                'status' => $this->status,
                'file' => $this->file,
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
     * @return Invoice
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     * @return Invoice
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param mixed $customerId
     * @return Invoice
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     * @return Invoice
     */
    public function setOrder(Order $order): Invoice
    {
        $this->order = $order;
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
     * @return Invoice
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return Invoice
     */
    public function setFile($file)
    {
        $this->file = $file;
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
     * @return Invoice
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
     * @return Invoice
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return array|Customer|void
     */
    public function getCustomer()
    {
        return $this->customer = (new Customer())->find($this->customerId);
    }
}
