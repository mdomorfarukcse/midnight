<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;
use Symfony\Component\HttpFoundation\Session\Session;

class Product extends BaseModel
{
    private $id;
    private $name;
    private $credit;
    private $price;

    /* @var Currency*/
    private $currency;

    private $taxRate;
    private $status;
    private $sortOrder;
    private $discountStatus;
    private $discountedPrice;
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
                $where[] = 'product.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'product.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if(isset($criteria['filter']['status']))
            $where[] = 'status ='. $criteria['filter']['status'];

        $sql = 'SELECT * FROM product';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        if (isset($criteria['order'])) {
            switch ($criteria['order']['field']) {
                case 'id':
                    $sql .= ' ORDER BY product.id ';
                    break;
                case 'credit':
                    $sql .= ' ORDER BY product.credit ';
                    break;
                case 'sort_order':
                    $sql .= ' ORDER BY product.sort_order ';
                    break;
                case 'updated_at':
                    $sql .= ' ORDER BY product.updated_at ';
                    break;
                case 'created_at':
                    $sql .= ' ORDER BY product.created_at ';
                    break;
                default:
                    $sql .= ' ORDER BY product.id ';
                    $criteria['order']['sort'] = 'ASC';
                    break;
            }
            $sql .= $criteria['order']['sort'];
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
     * @return Product
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setName($data->name)
            ->setCredit($data->credit)
            ->setPrice($data->price)
            ->setCurrency($this->container->get('currency')->get($data->currency))
            ->setTaxRate($data->tax_rate)
            ->setStatus($data->status)
            ->setSortOrder($data->sort_order)
            ->setDiscountStatus($data->discount_status)
            ->setDiscountedPrice($data->discounted_price)
            ->setUpdatedAt($data->updated_at)
            ->setCreatedAt($data->created_at);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('product', [
                'name' => $this->name,
                'credit' => $this->credit,
                'price' => $this->price,
                'currency' => $this->currency->getCode(),
                'tax_rate' => $this->taxRate,
                'status' => $this->status,
                'sort_order' => $this->sortOrder,
                'discount_status' => $this->discountStatus,
                'discounted_price' => $this->discountedPrice,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('product', [
                'id' => $this->id,
                'name' => $this->name,
                'credit' => $this->credit,
                'price' => $this->price,
                'currency' => $this->currency->getCode(),
                'tax_rate' => $this->taxRate,
                'status' => $this->status,
                'sort_order' => $this->sortOrder,
                'discount_status' => $this->discountStatus,
                'discounted_price' => $this->discountedPrice,
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
     * @return Product
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */

    public function getPrice($exchange = false, $taxInclude = false, $numberFormat = false, $withSymbol = false)
    {
        /* @var Currency $currency */
        $currency = $this->container->get('currency');

        /* @var Session $session */
        $session = $this->container->get('session');

        $price = $this->price;

        /* @var Customer $customer */
        $customer = $this->container->get('customer');

        if (!empty($customer) && $customer->hasCustomerGroupIncrease()) {
            $price = $customer->calculatePriceIncreaseForCustomerGroup($price);
        }

        // Discount Apply
     /*  if (!empty($customer) && $customer->hasCustomerGroupPromotion()) {
            $price = $this->getDiscountedPriceByCustomerGroupPromotion(
                $customer, false, false
            );
        }*/

        if ($exchange) {
            if (
                !empty($currency->getList()[$this->currency->getCode()]) &&
                !empty($exchangeRate = @$currency->getList()[$this->currency->getCode()]->getExchangeRates()[$session->get('currency')])
            ) {
                $price = $price * $exchangeRate->getRate();
            }
        }

        if ($taxInclude) {
            $taxRate = $this->getTaxRate();
            if (!empty($taxRate))
                $price = $price + (($price / 100) * $taxRate);

			if (strlen(substr($price, 2)) > 2)
				{
					$price = number_format($price, 2);
				}
        }

        if ($numberFormat) {
            $price = number_format($price, 2, ',', '.');
        }

        if ($withSymbol) {
            $price = $currency->getList()[$session->get('currency')]->getSymbol() . $price;
        }

        return $price;
    }

    /**
     * @return mixed
     */

    public function getDiscountedPriceByCustomerGroupPromotion(Customer $customer, $exchange = false, $taxInclude = false, $numberFormat = false, $withSymbol = false)
    {
        $type = $customer->getCustomerGroup()->getProcessType();
        $multiplier = $customer->getCustomerGroup()->getMultiplier();

        $price = $this->price;

        if (!empty($customer) && $customer->hasCustomerGroupIncrease()) {
            $price = $customer->calculatePriceIncreaseForCustomerGroup($price);
        }

        switch ($type) {
            case 'percent':
                $price = $price - (($price / 100) * $multiplier);
                break;
            case 'amount':
                $price = $price - $multiplier;
                break;
        }

        /* @var Currency $currency */
        $currency = $this->container->get('currency');

        /* @var Session $session */
        $session = $this->container->get('session');

        if ($exchange) {
            if (
                !empty($currency->getList()[$this->currency->getCode()]) &&
                !empty(
                $exchangeRate = @$currency->getList()[$this->currency->getCode()]->getExchangeRates()[$session->get(
                    'currency'
                )]
                )
            ) {
                $price = $price * $exchangeRate->getRate();
            }
        }

        if ($taxInclude) {
            $taxRate = $this->getTaxRate();
            if (!empty($taxRate)) {
                $price = $price + (($price / 100) * $taxRate);
            }

            if (strlen(substr($price, 2)) > 2) {
                $price = number_format($price, 2);
            }
        }

        if ($numberFormat) {
            $price = number_format($price, 2, ',', '.');
        }

        if ($withSymbol) {
            $price = $currency->getList()[$session->get('currency')]->getSymbol() . $price;
        }

        return $price;
    }


    public function getPriceClear($exchange = false, $taxInclude = false, $numberFormat = false, $withSymbol = false)
    {
        /* @var Currency $currency */
        $currency = $this->container->get('currency');

        /* @var Session $session */
        $session = $this->container->get('session');

        $price = $this->price;

        /* @var Customer $customer */
        $customer = $this->container->get('customer');

        if ($customer->hasCustomerGroupIncrease()) {
            $price = $customer->calculatePriceIncreaseForCustomerGroup($price);
        }

        // Discount Apply
     /*  if ($customer->hasCustomerGroupPromotion()) {
           $price = $this->getDiscountedPriceByCustomerGroupPromotion(
               $customer, false, false
           );
       }*/

        if ($exchange) {
            if (
                !empty($currency->getList()[$this->currency->getCode()]) &&
                !empty($exchangeRate = @$currency->getList()[$this->currency->getCode()]->getExchangeRates()[$session->get('currency')])
            ) {
                $price = $price * $exchangeRate->getRate();
            }
        }

        if ($taxInclude) {
            $taxRate = $this->getTaxRate();
            if (!empty($taxRate))
                $price = $price + (($price / 100) * $taxRate);

        /*    if (strlen(substr($price, 2)) > 2)
            {
                $price = number_format($price, 2);
            }*/
        }


        if ($numberFormat) {
            $price = number_format($price, 2, ',', '.');
        }

        if ($withSymbol) {
            $price = $currency->getList()[$session->get('currency')]->getSymbol() . $price;
        }

        return $price;
    }

    /**
     * @param mixed $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;
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
     * @return Product
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;
        return $this;
    }


    /**
     * @param mixed $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     * @return Product
     */
    public function setCurrency(Currency $currency): Product
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxRate()
    {

        $tax_rate = $this->taxRate;
        if($tax_rate == 0){

            /* @var Customer $customer */
            $customer = $this->container->get('customer');

            if (!empty($customer) && $customer->hasCustomerGroupTaxRate()) {
                $tax_rate = $customer->hasCustomerGroupTaxRate();
            }
        }

        return $tax_rate;
    }

    /**
     * @param mixed $taxRate
     * @return Product
     */
    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;
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
     * @return Product
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param mixed $sortOrder
     * @return Product
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDiscountStatus()
    {
        return $this->discountStatus;
    }

    /**
     * @param mixed $discountStatus
     * @return Product
     */
    public function setDiscountStatus($discountStatus)
    {
        $this->discountStatus = $discountStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDiscountedPrice($currencyFormat = false, $exchangeCurrencyCode = 'TRY')
    {
        $discountedPrice = $this->discountedPrice;

        if ($currencyFormat) {
            /* @var ExchangeRate $exchangeRates */
            $exchangeRates = $this->currency->getExchangeRates()[$exchangeCurrencyCode];
            $discountedPrice = $this->container->get('currency')->get($exchangeCurrencyCode)->getSymbol() . ($this->discountedPrice * $exchangeRates->getRate());
        }

        return $discountedPrice;
    }

    /**
     * @param mixed $discountedPrice
     * @return Product
     */
    public function setDiscountedPrice($discountedPrice)
    {
        $this->discountedPrice = $discountedPrice;
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
     * @return Product
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
     * @return Product
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function findOneByGreaterThanValue($credit)
    {
        $data = [];
        $products = $this->findBy(['filter' => ['status' => 1], 'order' => ['field' => 'credit', 'sort' => 'ASC']]);

        /** @var self $product */

        if (!empty($products)) {
            foreach ($products as $key => $product) {
                if ($product->getCredit() > $credit) {
                    $data[$key]['product'] = $product;
                    $data[$key]['quantity'] = 1;
                    break;
                }
            }
        }

        if (empty($data)) {
            $products = array_reverse($products);
            $data = $this->addProductToData($credit, $products, $data);
        }

        return $data;
    }

    public function addProductToData($credit, $products, $data)
    {
        $total = $this->calculateDataCredit($data);

        if ($total >= $credit) {
            return $data;
        }

        foreach ($products as $key => $product) {
            $id = $product->getId();
            $multiplier = ($credit - $total)/$product->getCredit();
            $floor = floor($multiplier);
            if ($floor > 0) {
                $data[$id]['product'] = $product;
                $data[$id]['quantity'] = $floor;
                unset($products[$key]);
                $data = $this->addProductToData($credit, $products, $data);
            }
            break;
        }

        return $data;
    }


    public function calculateDataCredit($data)
    {
        $total = 0;

        foreach ($data as $key => $item) {
            $total += $item['product']->getCredit()*$item['quantity'];
        }

        return $total;
    }

}
