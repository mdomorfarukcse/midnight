<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use Pemm\Config;
use PDO;
use Symfony\Component\HttpFoundation\Session\Session;

class Order extends BaseModel
{
    public $id;
    public $number;
    public $customerId;
    public $customerIp;
    public $itemsTotal;
    public $adjustments;
    public $total;
    public $taxAmount;
    public $currency;
    public $notes;
    public $token;
    public $state;
    public $totalCredit;
    public $paymentType;
    public $paymentStatus;
    public $paymentError;
    public $createdAt;
    public $updatedAt;
    public $country;
    public $city;
    public $address;
    public $countryState;
    public $update = 0;

    /* @var ?Invoice */
    public $invoice;

    public $items = [];

    /* @var Customer */
    public $customer;

    public static function situations()
    {
        return [
            'new' => 'New',
            'completed' => 'Completed',
            'cancel' => 'Cancel'
        ];
    }

    public function delete($orderNumber) {

        $sql = "DELETE FROM `order` Where `number`='".$orderNumber."'";
        $prepare = $this->database->prepare($sql);
        $prepare->execute([]);

    }

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
                $where[] = 'order.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'order.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['customer_id'])) {
            $where[] = 'order.customer_id=:customer_id';
            $executeData[':customer_id'] = $criteria['filter']['customer_id'];
        }

        if (isset($criteria['filter']['payment_status'])) {
            $where[] = 'order.payment_status=:payment_status';
            $executeData[':payment_status'] = $criteria['filter']['payment_status'];
        }

        if (isset($criteria['filter']['number'])) {
            $where[] = 'order.number=:number';
            $executeData[':number'] = $criteria['filter']['number'];
        }

        if (isset($criteria['filter']['status'])) {
            $where[] = 'order.status=:status';
            $executeData[':status'] = $criteria['filter']['status'];
        }

        $sql = 'SELECT `order`.* FROM `order`';

        if (!empty($criteria['filter']['datatable_query'])) {
            $q = $criteria['filter']['datatable_query'];
            $sql .= ' LEFT JOIN customer ON customer.id=order.customer_id ';
            $where[] = '(CONCAT(customer.first_name,
            order.items_total,
            order.total,
            order.tax_amount,
            order.currency,
            order.total_credit,
              order.state,
                order.payment_type,
                  order.payment_status,
                    order.country,
                      " ",  customer.last_name) LIKE "%' . $q . '%" )';
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
                    $sql .= ' ORDER BY order.id ';
                    break;
                case 'updated_at':
                    $sql .= ' ORDER BY order.updated_at ';
                    break;

                case 'created_at':
                    $sql .= ' ORDER BY order.created_at ';
                    break;
                case 'items_total':
                    $sql .= ' ORDER BY order.items_total ';
                    break;
                case 'tax_amount':
                    $sql .= ' ORDER BY order.tax_amount ';
                    break;
                case 'currency':
                    $sql .= ' ORDER BY order.currency ';
                    break;
                case 'total_credit':
                    $sql .= ' ORDER BY order.total_credit ';
                    break;
                case 'payment_type':
                    $sql .= ' ORDER BY order.payment_type ';
                    break;
                case 'payment_status':
                    $sql .= ' ORDER BY order.payment_status ';
                    break;
                case 'country':
                    $sql .= ' ORDER BY order.country ';
                    break;


                default:
                    $sql .= ' ORDER BY order.id ';
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
                        $result[$key] = $this->initialize($data);
                    }
                }
            }

        } catch (\Exception $e) {print_r($e);die;}

        return $result;
    }

    /**
     * @param $data
     * @return Order
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setNumber($data->number)
            ->setCustomerId($data->customer_id)
            ->setCustomerIp($data->customer_ip)
            ->setItemsTotal($data->items_total)
            ->setAdjustments($data->adjustments)
            ->setTotal($data->total)
            ->setTaxAmount($data->tax_amount)
            ->setCurrency($data->currency)
            ->setNotes($data->notes)
            ->setToken($data->token)
            ->setState($data->state)
            ->setTotalCredit($data->total_credit)
            ->setPaymentType($data->payment_type)
            ->setPaymentStatus($data->payment_status)
            ->setPaymentError($data->payment_error)
            ->setUpdatedAt($data->updated_at)
            ->setCreatedAt($data->created_at)
            ->setCountry($data->country)
            ->setCity($data->city)
            ->setAddress($data->address)
            ->setCountryState($data->country_state);
    }

    public function store()
    {
        $this->updatedAt = date('Y-m-d H:i:s');

        if (empty($this->id)) {

            $this->createdAt = date('Y-m-d H:i:s');

            if ($this->database->insert('`order`', [
                'number' => $this->number,
                'customer_id' => $this->customerId,
                'customer_ip' => $this->customerIp,
                'items_total' => $this->itemsTotal,
                'adjustments' => $this->adjustments,
                'total' => $this->total,
                'tax_amount' => $this->taxAmount,
                'currency' => $this->currency,
                'notes' => $this->notes,
                'token' => $this->token,
                'state' => $this->state,
                'total_credit' => $this->totalCredit,
                'payment_type' => $this->paymentType,
                'payment_status' => $this->paymentStatus,
                'payment_error' => $this->paymentError,
                'country' => $this->country,
                'city' => $this->city,
                'country_state' => $this->countryState,
                'address' => $this->address,
                'created_at' => $this->createdAt,
                'updated_at' => $this->updatedAt
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {


            $this->database->update('`order`', [
                'id' => $this->id,
                'number' => $this->number,
                'customer_id' => $this->customerId,
                'customer_ip' => $this->customerIp,
                'items_total' => $this->itemsTotal,
                'adjustments' => $this->adjustments,
                'total' => $this->total,
                'tax_amount' => $this->taxAmount,
                'currency' => $this->currency,
                'notes' => $this->notes,
                'token' => $this->token,
                'state' => $this->state,
                'total_credit' => $this->totalCredit,
                'payment_type' => $this->paymentType,
                'payment_status' => $this->paymentStatus,
                'payment_error' => $this->paymentError,
                'country' => $this->country,
                'city' => $this->city,
                'country_state' => $this->countryState,
                'address' => $this->address,
                'created_at' => $this->createdAt,
                'updated_at' => $this->updatedAt
            ]);

        }

        return $this;
    }

    public function getItems()
    {
        return $this->items = (new OrderItem())->findBy(['filter' => ['order_id' => $this->id]]);
    }

    public function getCustomer()
    {
        return $this->customer = (new Customer())->find($this->customerId);
    }

    public function generateNumber($uniqId)
    {
        return strtoupper('P' . $uniqId . Helper::generateRandomString(8));
    }

    public function addItem(OrderItem $orderItem)
    {
        array_push($this->items, $orderItem);
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
     * @return Order
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
     * @return Order
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
     * @return Order
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerIp()
    {
        return $this->customerIp;
    }

    /**
     * @param mixed $customerIp
     * @return Order
     */
    public function setCustomerIp($customerIp)
    {
        $this->customerIp = $customerIp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItemsTotal($numberFormat = false, $withSymbol = false)
    {
        /* @var Currency $currency */
        $currency = $this->container->get('currency');

        $itemsTotal = $this->itemsTotal;

        if ($numberFormat) {
            $itemsTotal = number_format($itemsTotal, 2, ',', '.');
        }

        if ($withSymbol) {
            $itemsTotal = $currency->getList()[$this->currency]->getSymbol() . $itemsTotal;
        }

        return $itemsTotal;
    }

    /**
     * @param mixed $itemsTotal
     * @return Order
     */
    public function setItemsTotal($itemsTotal)
    {
        $this->itemsTotal = $itemsTotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdjustments($numberFormat = false, $withSymbol = false)
    {
        /* @var Currency $currency */
        $currency = $this->container->get('currency');

        $adjustments = $this->adjustments;

        if ($numberFormat) {
            $adjustments = number_format($adjustments, 2, ',', '.');
        }

        if ($withSymbol) {
            $adjustments = $currency->getList()[$this->currency]->getSymbol() . $adjustments;
        }

        return $adjustments;
    }

    /**
     * @param mixed $adjustments
     * @return Order
     */
    public function setAdjustments($adjustments)
    {
        $this->adjustments = $adjustments;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotal($numberFormat = false, $withSymbol = false)
    {
        /* @var Currency $currency */
        $currency = $this->container->get('currency');

        $total = $this->total;

        if ($this->adjustments > 0) {
            $total = $total-$this->adjustments;
        }

        if ($numberFormat) {
            $total = number_format($total, 2, ',', '.');
        }

        if ($withSymbol) {
            $total = $currency->getList()[$this->currency]->getSymbol() . $total;
        }

        return $total;
    }

    /**
     * @param mixed $total
     * @return Order
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxAmount($numberFormat = false, $withSymbol = false)
    {
        /* @var Currency $currency */
        $currency = $this->container->get('currency');

        $taxAmount = $this->taxAmount;

        if ($numberFormat) {
            $taxAmount = number_format($taxAmount, 2, ',', '.');
        }

        if ($withSymbol) {
            $taxAmount = $currency->getList()[$this->currency]->getSymbol() . $taxAmount;
        }

        return $taxAmount;
    }

    /**
     * @param mixed $taxAmount
     * @return Order
     */
    public function setTaxAmount($taxAmount)
    {
        $this->taxAmount = $taxAmount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     * @return Order
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param mixed $notes
     * @return Order
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return Order
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     * @return Order
     */
    public function setState($state)
    {
        $this->state = $state;
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
     * @return Order
     */
    public function setTotalCredit($totalCredit)
    {
        $this->totalCredit = $totalCredit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param mixed $paymentType
     * @return Order
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * @param mixed $paymentStatus
     * @return Order
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentError()
    {
        return $this->paymentError;
    }

    /**
     * @param mixed $paymentError
     * @return Order
     */
    public function setPaymentError($paymentError)
    {
        $this->paymentError = $paymentError;
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
     * @return Order
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
     * @return Order
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return Order
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return Order
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return Order
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountryState()
    {
        return $this->countryState;
    }

    /**
     * @param mixed $countryState
     * @return Order
     */
    public function setCountryState($countryState)
    {
        $this->countryState = $countryState;
        return $this;
    }

    public function getInvoice()
    {
        return $this->invoice = (new Invoice())->findOneBy(['filter' => [ 'order_id' => $this->id]]);
    }

    public function summaryTotal($currencyFormat = false)
    {
        global $container;

        /* @var Currency $currency */
        $currency = $container->get('currency');

        /* @var Session $session */
        $session = $container->get('session');

        $selectedCurrency = $session->get('currency');

        $result = new \stdClass();
        $result->orderTotal = 0;
        $result->orderCreditTotal = 0;

        $orders = $this->database->query('SELECT total, currency, total_credit FROM `order` WHERE state NOT IN ("cancel","pending") and NOT payment_status = "pending" and NOT payment_status = "cancel" and NOT payment_status = "cancelled" and NOT payment_status = "canceled" ')->fetchAll(PDO::FETCH_OBJ);

        if (!empty($orders)) {
            foreach ($orders as $order) {
                if (
                    !empty($currency->get($order->currency)) &&
                    !empty($exchange = $currency->get($order->currency)->getExchangeRates()[$selectedCurrency])
                ) {
                    /* @var ExchangeRate $exchange */

                    $result->orderTotal += $order->total * $exchange->getRate();
                }

                $result->orderCreditTotal += $order->total_credit;
            }
        }

        if (!empty($currencyFormat)) {
            $result->orderTotal = $currency->get($selectedCurrency)->getSymbol() . $result->orderTotal;
        }

        return $result;
    }

    public function createInvoice()
    {
        $invoice = new Invoice();
        $invoice
            ->setNumber($this->customerId . strtoupper(Helper::generateRandomString(8)))
            ->setCustomerId($this->customerId)
            ->setOrder($this)
            ->setStatus('paid')
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->store();

		$invoice->setNumber($this->setting->getInvoiceprefix().$invoice->getId())->store();


        $customerM = new Customer();
        $customer =  $customerM->find($this->customerId);

        if(trim($this->notes)=="EVC") {

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$this->setting->getSiteUrl()."/evc/api.php?islem=addcustomeraccount&customer=".$customer->getEvcnumber()."&credits=".$this->totalCredit);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

            $output=curl_exec($ch);
            curl_close($ch);



        }else{
            $customer->setSumNewCredit($this->totalCredit)->save();
        }



    }
}
