<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use Pemm\Model\Setting;
use PDO;
use Symfony\Component\HttpFoundation\Session\Session;

class CartEvc extends BaseModel
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

    private $listTotalPrice = 0.00;
    private $cartTotalPrice = 0.00;

    private $listTotalPriceExTax = 0.00;
      private $cartTotalPriceExTax = 0.00;

    private $listTotalCredit = 0;
    private $cartTotalCredit = 0;

    private $campaigns = [];


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
                $where[] = 'cart_evc.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'cart_evc.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['product_id'])) {
            $where[] = 'cart_evc.product_id=:product_id';
            $executeData[':product_id'] = $criteria['filter']['product_id'];
        }

        if (isset($criteria['filter']['customer_id'])) {
            $where[] = 'cart_evc.customer_id=:customer_id';
            $executeData[':customer_id'] = $criteria['filter']['customer_id'];
        }

        $sql = 'SELECT * FROM cart_evc';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        if (isset($criteria['order'])) {
            switch ($criteria['order']['field']) {
                case 'id':
                    $sql .= ' ORDER BY cart_evc.id ';
                    break;
                default:
                    $sql .= ' ORDER BY cart_evc.id ';
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
            ->setProduct((new ProductEvc())->find($data->product_id))
            ->setQuantity($data->quantity)
            ->setCreatedAt($data->created_at)
            ->setUpdatedAt($data->updated_at);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('cart_evc', [
                'customer_id' => $this->customer->getId(),
                'product_id' => $this->product->getId(),
                'quantity' => $this->quantity,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('cart_evc', [
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
    public function getProduct(): ProductEvc
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return Cart
     */
    public function setProduct(ProductEvc $product): CartEvc
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
        /* @var Currency $currency */
        $currency = $this->container->get('currency');

        /* @var Session $session */
        $session = $this->container->get('session');

        $totalPrice = $this->listTotalPrice;

        if ($numberFormat) {
            $cartTotalPrice = number_format($totalPrice, 2, ',', '.');
        }

        if ($withSymbol) {
            $totalPrice = $currency->getList()[$session->get('currency')]->getSymbol() . $totalPrice;
        }

        return $totalPrice;
    }

    public function getListTotalPriceExTax($numberFormat = false, $withSymbol = false)
      {
          /* @var Currency $currency */
          $currency = $this->container->get('currency');

          /* @var Session $session */
          $session = $this->container->get('session');

          $totalPrice = $this->listTotalPriceExTax;

          if ($numberFormat) {
              $cartTotalPrice = number_format($totalPrice, 2, ',', '.');
          }

          if ($withSymbol) {
              $totalPrice = $currency->getList()[$session->get('currency')]->getSymbol() . $totalPrice;
          }

          return $totalPrice;
      }



    /**
     * @param $listTotalPrice
     * @return Cart
     */
    public function setListTotalPrice($listTotalPrice): CartEvc
    {
        $this->listTotalPrice = $listTotalPrice;
        return $this;
    }

    public function setListTotalPriceExTax($listTotalPriceExTax): CartEvc
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
    public function setCampaigns(array $campaigns): CartEvc
    {
        $this->campaigns = $campaigns;
        return $this;
    }

    /**
     * @return float
     */
    public function getCartTotalPrice($numberFormat = false, $withSymbol = false)
    {
        /* @var Currency $currency */
        $currency = $this->container->get('currency');

        /* @var Session $session */
        $session = $this->container->get('session');

        $cartTotalPrice = $this->cartTotalPrice;

        if ($numberFormat) {
            $cartTotalPrice = number_format($cartTotalPrice, 2, ',', '.');
        }

        if ($withSymbol) {
            $cartTotalPrice = $currency->getList()[$session->get('currency')]->getSymbol() . $cartTotalPrice;
        }

        return $cartTotalPrice;
    }

    public function getCartTotalPriceExTax($numberFormat = false, $withSymbol = false)
      {
          /* @var Currency $currency */
          $currency = $this->container->get('currency');

          /* @var Session $session */
          $session = $this->container->get('session');

          $cartTotalPriceExTax = $this->cartTotalPriceExTax;

          if ($numberFormat) {
              $cartTotalPriceExTax = number_format($cartTotalPriceExTax, 2, ',', '.');
          }

          if ($withSymbol) {
              $cartTotalPriceExTax = $currency->getList()[$session->get('currency')]->getSymbol() . $cartTotalPriceExTax;
          }

          return $cartTotalPriceExTax;
      }


    /**
     * @param float $cartTotalPrice
     * @return Cart
     */
    public function setCartTotalPrice($cartTotalPrice): CartEvc
    {
        $this->cartTotalPrice = $cartTotalPrice;
        return $this;
    }

    public function setCartTotalPriceExTax($cartTotalPriceExTax): CartEvc
      {
          $this->cartTotalPriceExTax = $cartTotalPriceExTax;
          return $this;
      }

    public function calculateListTotalPrice()
    {
        if (!empty($this->list)) {
            /* @var Cart $cart */
            foreach ($this->list as $cart) {
                $this->listTotalPrice += $cart->getProduct()->getPrice(true, true) * $cart->getQuantity();
            }
        }
    }

    public function calculateCartTotalPrice()
    {
        $this->cartTotalPrice = $this->product->getPrice(true, true) * $this->quantity;
    }

    public function calculateListTotalPriceExTax()
      {
          if (!empty($this->list)) {
              /* @var Cart $cart */
              foreach ($this->list as $cart) {
                  $this->listTotalPriceExTax += $cart->getProduct()->getPrice(true, false) * $cart->getQuantity();
              }
          }
      }

      public function calculateCartTotalPriceExTax()
      {
          $this->cartTotalPriceExTax = $this->product->getPrice(true, false) * $this->quantity;
      }

    public function calculateListTotalCredit()
    {
        if (!empty($this->list)) {
            /* @var Cart $cart */
            foreach ($this->list as $cart) {
                $this->listTotalCredit += $cart->getProduct()->getCredit() * $cart->getQuantity();
            }
        }
    }

    public function calculateCartTotalCredit()
    {
        $this->cartTotalCredit = $this->product->getCredit() * $this->quantity;
    }

    public function isEmpty()
    {
        return empty($this->list);
    }

    public function remove()
    {
        $this->database->bulkDeleteByIds('cart_evc', [$this->id]);
    }

    public function toOrder($params, $type = 'PROD')
    {
        /* @var Session $session */
        $session = $this->container->get('session');

        $this->calculateListTotalPrice();
        $this->calculateListTotalCredit();
        $this->calculateListTotalPriceExTax();

        $order = new Order();
        $order->setNumber($order->generateNumber($this->customer->getId()));
        $order->setCustomerId($this->customer->getId());
        $order->setCustomerIp($this->customer->getIp());
        $order->setItemsTotal($this->listTotalPriceExTax);
        $order->setAdjustments(0);
        $order->setTotal($this->listTotalPrice);
        $order->setTaxAmount($this->listTotalPrice - $this->listTotalPriceExTax);
        $order->setCurrency($session->get('currency'));
        $order->setNotes("EVC");
        $order->setToken(Helper::generateRandomString(24));
        $order->setState('new');
        $order->setTotalCredit($this->listTotalCredit);
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
                $cart->calculateCartTotalPrice();
                $cart->calculateCartTotalPriceExTax();
                $cart->calculateCartTotalCredit();
                $orderItem = new OrderItem();
                $orderItem->setOrderId($order->getId());
                $orderItem->setProductId($cart->getProduct()->getId());
                $orderItem->setQuantity($cart->getQuantity());
                $orderItem->setUnitsTotal($cart->cartTotalPriceExTax);
                $orderItem->setAdjustments(0);
                $orderItem->setTaxRate($cart->getProduct()->getTaxRate());
                $orderItem->setTaxAmount($cart->cartTotalPrice - $cart->cartTotalPriceExTax);
                $orderItem->setProductName($cart->getProduct()->getName());
                $orderItem->setTotalCredit($cart->cartTotalCredit);
                $orderItem->store();
                $order->addItem($orderItem);
            }
        }

        return $order;
    }
}
