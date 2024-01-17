<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use Pemm\Model\Setting;
use PDO;
use Symfony\Component\HttpFoundation\Session\Session;

class Cart extends BaseModel
{
    private $id;

    /* @var Customer */
    private $customer;

    /* @var Product */
    private $product;

    private $quantity;
    private $createdAt;
    private $updatedAt;

    private $list;

    private $listTotalAdjustments = 0.00;
    private $cartTotalAdjustments = 0.00;

    private $listTotalPrice = 0.00;
    private $cartTotalPrice = 0.00;

	  private $listTotalPriceExTax = 0.00;
    private $cartTotalPriceExTax = 0.00;

    private $listTotalCredit = 0;
    private $cartTotalCredit = 0;

    private $cartTotalTax = 0.00;
    private $listTotalTax = 0.00;

    private $campaigns = [];

    private $bonusCredit = 0;

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
                $where[] = 'cart.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'cart.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['product_id'])) {
            $where[] = 'cart.product_id=:product_id';
            $executeData[':product_id'] = $criteria['filter']['product_id'];
        }

        if (isset($criteria['filter']['customer_id'])) {
            $where[] = 'cart.customer_id=:customer_id';
            $executeData[':customer_id'] = $criteria['filter']['customer_id'];
        }

        $sql = 'SELECT * FROM cart';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        if (isset($criteria['order'])) {
            switch ($criteria['order']['field']) {
                case 'id':
                    $sql .= ' ORDER BY cart.id ';
                    break;
                default:
                    $sql .= ' ORDER BY cart.id ';
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
     * @return Cart
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setCustomer((new Customer())->find($data->customer_id))
            ->setProduct((new Product())->find($data->product_id))
            ->setQuantity($data->quantity)
            ->setCreatedAt($data->created_at)
            ->setUpdatedAt($data->updated_at);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('cart', [
                'customer_id' => $this->customer->getId(),
                'product_id' => $this->product->getId(),
                'quantity' => $this->quantity,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('cart', [
                'id' => $this->id,
                'customer_id' => $this->customer->getId(),
                'product_id' => $this->product->getId(),
                'quantity' => $this->quantity,
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
     * @return Cart
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param mixed $customer
     * @return Cart
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return Cart
     */
    public function setProduct(Product $product): Cart
    {
        $this->product = $product;
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
     * @return Cart
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
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
     * @return Cart
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
     * @return Cart
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
     * @return Cart
     */
    public function setList($list)
    {
        $this->list = $list;
        return $this;
    }

    /**
     * @return float
     */
    public function getListTotalPrice($numberFormat = false, $withSymbol = false)
    {
        return $this->priceRenderer($this->listTotalPrice, $numberFormat, $withSymbol);
    }

	public function getListTotalPriceExTax($numberFormat = false, $withSymbol = false)
    {
        return $this->priceRenderer($this->listTotalPriceExTax, $numberFormat, $withSymbol);
    }

    /**
     * @param $listTotalPrice
     * @return Cart
     */
    public function setListTotalPrice($listTotalPrice): Cart
    {
        $this->listTotalPrice = $listTotalPrice;
        return $this;
    }

	public function setListTotalPriceExTax($listTotalPriceExTax): Cart
    {
        $this->listTotalPriceExTax = $listTotalPriceExTax;
        return $this;
    }

    /**
     * @return array
     */
    public function getCampaigns(): array
    {
        return $this->campaigns;
    }

    /**
     * @param array $campaigns
     * @return Cart
     */
    public function setCampaigns(array $campaigns): Cart
    {
        $this->campaigns = $campaigns;
        return $this;
    }

    public function getCartTotalPrice($numberFormat = false, $withSymbol = false)
    {
        return $this->priceRenderer($this->cartTotalPrice, $numberFormat, $withSymbol);
    }

	public function getCartTotalPriceExTax($numberFormat = false, $withSymbol = false)
    {
        return $this->priceRenderer($this->cartTotalPriceExTax, $numberFormat, $withSymbol);
    }

    /**
     * @param float $cartTotalPrice
     * @return Cart
     */
    public function setCartTotalPrice($cartTotalPrice): Cart
    {
        $this->cartTotalPrice = $cartTotalPrice;
        return $this;
    }

	public function setCartTotalPriceExTax($cartTotalPriceExTax): Cart
    {
        $this->cartTotalPriceExTax = $cartTotalPriceExTax;
        return $this;
    }

    public function calculator()
    {
        if (!empty($this->list)) {
            /* @var Cart $cart */
            foreach ($this->list as $cart) {
                $cart->setCartTotalPrice($cart->getProduct()->getPrice(true, true) * $cart->getQuantity());
                $this->listTotalPrice += $cart->getCartTotalPrice();
                $cart->setCartTotalPriceExTax($cart->getProduct()->getPrice(true) * $cart->getQuantity());
                $this->listTotalPriceExTax += $cart->getCartTotalPriceExTax();
                $cart->setCartTotalCredit($cart->getProduct()->getCredit() * $cart->getQuantity());
                $this->listTotalCredit += $cart->getCartTotalCredit();
                $cart->setCartTotalTax($cart->getCartTotalPrice()- $cart->getCartTotalPriceExTax());
                $this->listTotalTax += $cart->getCartTotalTax();
                if ($this->customer->hasCustomerGroupPromotion()) {
                    $appliedPromotionPrice = $cart->getProduct()->getDiscountedPriceByCustomerGroupPromotion(
                        $this->customer,
                        true,
                        true
                    ) * $cart->getQuantity();

                    $cart->setCartTotalAdjustments($cart->getCartTotalPrice() - $appliedPromotionPrice);
                    $this->listTotalAdjustments += $cart->getCartTotalAdjustments();
                }

                if ($this->customer->hasCustomerGroupBonusCredit()) {
                    $cart->setBonusCredit($this->customer->calculateBonusCreditForCustomerGroup($cart->getCartTotalCredit()));
                    $this->bonusCredit += $cart->getBonusCredit();
                }
            }
        }
    }

    public function isEmpty()
    {
        return empty($this->list);
    }

    public function remove()
    {
        $this->database->bulkDeleteByIds('cart', [$this->id]);
    }

    public function toOrder($params, $type = 'PROD')
    {
        /* @var Session $session */
        $session = $this->container->get('session');

        $this->calculator();

        $order = new Order();
        $order->setNumber($order->generateNumber($this->customer->getId()));
        $order->setCustomerId($this->customer->getId());
        $order->setCustomerIp($this->customer->getIp());
        $order->setItemsTotal($this->listTotalPriceExTax);
        $order->setAdjustments($this->listTotalAdjustments);
        $order->setTotal($this->listTotalPrice);
        $order->setTaxAmount($this->listTotalTax);
        $order->setCurrency($session->get('currency'));
        $order->setNotes('');
        $order->setToken(Helper::generateRandomString(24));
        $order->setState('new');
        $order->setTotalCredit($this->listTotalCredit + $this->bonusCredit);
        $order->setPaymentType($params['payment_method']);
        $order->setPaymentStatus('pending');
        $order->setPaymentError('');
        $order->setCountry($params['country']);
        $order->setCountryState($params['state']);
        $order->setCity($params['city']);
        $order->setAddress($params['address']);

        if ($type == 'TEST') {
            $order->setPaymentStatus('completed');
        }

        $order->store();

        if ($order->getId()) {
            /* @var Cart $cart */
            foreach ($this->list as $cart) {

                $unitsTotal = $cart->cartTotalPriceExTax;

                if ($this->customer->hasCustomerGroupPromotion()) {
                    $unitsTotal = $cart->getProduct()->getDiscountedPriceByCustomerGroupPromotion(
                            $this->customer,
                            true,
                            true
                        ) * $cart->getQuantity();
                }


                $orderItem = new OrderItem();
                $orderItem->setOrderId($order->getId());
                $orderItem->setProductId($cart->getProduct()->getId());
                $orderItem->setQuantity($cart->getQuantity());
                $orderItem->setUnitsTotal($unitsTotal);
                $orderItem->setAdjustments($cart->cartTotalAdjustments);
                $orderItem->setTaxRate($cart->getProduct()->getTaxRate());
                $orderItem->setTaxAmount($cart->cartTotalTax);
                $orderItem->setProductName($cart->getProduct()->getName());
                $orderItem->setTotalCredit($cart->cartTotalCredit + $cart->bonusCredit);
                $orderItem->store();
                $order->addItem($orderItem);
            }
        }

        return $order;
    }

    public function getListTotalAdjustments($numberFormat = false, $withSymbol = false)
    {
        return $this->priceRenderer($this->listTotalAdjustments, $numberFormat, $withSymbol);
    }

    /**
     * @param float $listTotalAdjustments
     * @return Cart
     */
    public function setListTotalAdjustments(float $listTotalAdjustments): Cart
    {
        $this->listTotalAdjustments = $listTotalAdjustments;
        return $this;
    }

    public function getCartTotalAdjustments($numberFormat = false, $withSymbol = false)
    {
        return $this->priceRenderer($this->cartTotalAdjustments, $numberFormat, $withSymbol);
    }

    /**
     * @param float $cartTotalAdjustments
     * @return Cart
     */
    public function setCartTotalAdjustments(float $cartTotalAdjustments): Cart
    {
        $this->cartTotalAdjustments = $cartTotalAdjustments;
        return $this;
    }

    /**
     * @return int
     */
    public function getListTotalCredit(): int
    {
        return $this->listTotalCredit;
    }

    /**
     * @param int $listTotalCredit
     * @return Cart
     */
    public function setListTotalCredit(int $listTotalCredit): Cart
    {
        $this->listTotalCredit = $listTotalCredit;
        return $this;
    }

    /**
     * @return int
     */
    public function getCartTotalCredit(): int
    {
        return $this->cartTotalCredit;
    }

    /**
     * @param int $cartTotalCredit
     * @return Cart
     */
    public function setCartTotalCredit(int $cartTotalCredit): Cart
    {
        $this->cartTotalCredit = $cartTotalCredit;
        return $this;
    }

    public function getCartTotalTax($numberFormat = false, $withSymbol = false)
    {
        return $this->priceRenderer($this->cartTotalTax, $numberFormat, $withSymbol);
    }

    /**
     * @param float $cartTotalTax
     * @return Cart
     */
    public function setCartTotalTax(float $cartTotalTax): Cart
    {
        $this->cartTotalTax = $cartTotalTax;
        return $this;
    }

    public function getListTotalTax($numberFormat = false, $withSymbol = false)
    {
        return $this->priceRenderer($this->listTotalTax, $numberFormat, $withSymbol);
    }

    /**
     * @param float $listTotalTax
     * @return Cart
     */
    public function setListTotalTax(float $listTotalTax): Cart
    {
        $this->listTotalTax = $listTotalTax;
        return $this;
    }

    public function priceRenderer($price, $numberFormat = false, $withSymbol = false)
    {
        /* @var Currency $currency */
        $currency = $this->container->get('currency');

        /* @var Session $session */
        $session = $this->container->get('session');

        if ($numberFormat) {
            $price = number_format($price, 2, ',', '.');
        }

        if ($withSymbol) {
            $price = $currency->getList()[$session->get('currency')]->getSymbol() . $price;
        }

        return $price;
    }

    /**
     * @return int
     */
    public function getBonusCredit(): int
    {
        return $this->bonusCredit;
    }

    /**
     * @param int $bonusCredit
     * @return Cart
     */
    public function setBonusCredit(int $bonusCredit): Cart
    {
        $this->bonusCredit = $bonusCredit;
        return $this;
    }

}
