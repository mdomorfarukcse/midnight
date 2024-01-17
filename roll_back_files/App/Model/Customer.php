<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use Pemm\Model\EmailNotification;
use PDO;
use Symfony\Component\HttpFoundation\Session\Session;

class Customer extends BaseModel
{
    private $id;

    /** @var CustomerGroup  */
    private $customerGroup;

    private $firstName;
    private $lastName;
    private $email;
    private $password;
    private $country;
    private $companyName;
    private $vatNumber;
    private $city;
    private $evcnumber;
    private $evccredit;
    private $address;
    private $avatar;
    private $contactNumber;
    private $token;
    private $credit;
    private $allowLogin;
    private $status;
    private $reference;
    private $ip;
    private $deletedAt;
    private $createdAt;
    private $updatedAt;

    /* @var Cart $cart */
    public $cart;
    public $cartevc;

    public $creditLogs;
    public $files;
    public $supoorts;
    public $notifications;

    /* @var Currency */
    public $currency;

    private $list;

    public function __construct()
    {
        parent::__construct();
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
                $where[] = 'customer.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'customer.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['customer_group'])) {
            $where[] = 'customer.customer_group=:customer_group';
            $executeData[':customer_group'] = $criteria['filter']['customer_group'];
        }

        if (isset($criteria['filter']['email'])) {
            $where[] = 'customer.email=:email';
            $executeData[':email'] = $criteria['filter']['email'];
        }

        if (isset($criteria['filter']['status'])) {
            $where[] = 'customer.status=:status';
            $executeData[':status'] = $criteria['filter']['status'];
        }

        if (isset($criteria['filter']['reference'])) {
            $where[] = 'customer.reference=:reference';
            $executeData[':reference'] = $criteria['filter']['reference'];
        }

        if (isset($criteria['filter']['allow_login'])) {
            $where[] = 'customer.allow_login=:allow_login';
            $executeData[':allow_login'] = $criteria['filter']['allow_login'];
        }

        if (isset($criteria['filter']['token'])) {
            $where[] = 'customer.token=:token';
            $executeData[':token'] = $criteria['filter']['token'];
        }

        if (isset($criteria['filter']['datatable_query'])) {
            $explode = explode(' ', $criteria['filter']['datatable_query']);
            foreach ($explode as $_ex) {
                $where[] = '(LOWER(CONCAT(
                   customer.first_name,
                  customer.last_name,
                  customer.email,
                  customer.credit,
                  customer.status,
                   customer.contact_number)) LIKE LOWER("%' . $_ex . '%"))';
            }
        }

        $sql = 'SELECT * FROM customer';

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

        if (isset($criteria['order'])) {
            switch ($criteria['order']['field']) {
                case 'id':
                    $sql .= ' ORDER BY customer.id ';
                    break;

                case 'first_name':
                    $sql .= ' ORDER BY customer.first_name ';
                    break;

                case 'last_name':
                    $sql .= ' ORDER BY customer.last_name ';
                    break;

                case 'email':
                    $sql .= ' ORDER BY customer.email ';
                    break;

                case 'contact_number':
                    $sql .= ' ORDER BY customer.contact_number ';
                    break;

                case 'credit':
                    $sql .= ' ORDER BY customer.credit ';
                    break;

                case 'status':
                    $sql .= ' ORDER BY customer.status ';
                    break;

                default:
                    $sql .= ' ORDER BY customer.id ';
                    $criteria['order']['sort'] = 'ASC';
                    break;
            }
            $sql .= $criteria['order']['sort'];
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

    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setCustomerGroup((new CustomerGroup())->find($data->customer_group))
            ->setFirstName($data->first_name)
            ->setLastName($data->last_name)
            ->setEmail($data->email)
            ->setPassword($data->password)
            ->setAvatar($data->avatar)
            ->setContactNumber($data->contact_number)
            ->setCountry($data->country)
            ->setCompanyName($data->companyName)
            ->setVatNumber($data->vatNumber)
            ->setCity($data->city)
            ->setEvcnumber($data->evcnumber)
            ->setEvcCredit($data->evccredit)
            ->setAddress($data->address)
            ->setToken($data->token)
            ->setCredit($data->credit)
            ->setAllowLogin($data->allow_login)
            ->setStatus($data->status)
            ->setIp($data->ip)
            ->setReference($data->reference)
            ->setCreatedAt($data->created_at)
            ->setUpdatedAt($data->updated_at)
            ->setDeletedAt($data->deleted_at);
    }

    public function save()
    {
        $data = [
            'customer_group' => !empty($this->customerGroup) ? $this->customerGroup->getId() : 0,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,
            'country' => $this->country,
            'city' => $this->city,
            'evcnumber' => $this->evcnumber,
            'evccredit' => $this->evccredit,
            'address' => $this->address,
            'companyName' => $this->companyName,
            'vatNumber' => $this->vatNumber,
            'avatar' => $this->avatar,
            'contact_number' => $this->contactNumber,
            'token' => $this->token,
            'credit' => $this->credit ?? 0,
            'allow_login' => $this->allowLogin ?? 0,
            'status' => $this->status ?? 0,
            'reference' => $this->reference,
            'ip' => $this->ip,
            'created_at' => $this->createdAt
        ];

        if (!empty($this->deletedAt)) $data['deletedAt'] = $this->deletedAt;

        if (!empty($this->updatedAt)) $data['updated_at'] = $this->updatedAt;

        if (empty($this->id)) {
            $data['created_at'] = (new \DateTime())->format('Y-m-d H:i:s');
            if ($result = $this->database->insert('customer', $data)) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $data['updated_at'] = (new \DateTime())->format('Y-m-d H:i:s');
            $data['id'] = $this->id;
            $result = $this->database->update('customer', $data);
        }

        return $result;
    }

    /**
     * @return CustomerGroup
     */
    public function getCustomerGroup(): CustomerGroup
    {
        return $this->customerGroup;
    }

    /**
     * @param CustomerGroup $customerGroup
     * @return Customer
     */
    public function setCustomerGroup(CustomerGroup $customerGroup): Customer
    {
        $this->customerGroup = $customerGroup;
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
     * @return Customer
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
     public function getFirstName()
     {
         return $this->firstName;
     }

     public function getCountry()
     {
         return $this->country;
     }

    public function getCompanyName()
    {
        return $this->companyName;
    }

    public function getVatNumber()
    {
        return $this->vatNumber;
    }

     public function getCity()
     {
         return $this->city;
     }

     public function getEvcnumber()
     {
         return $this->evcnumber;
     }

     public function getEvcCredit()
     {
         return $this->evccredit;
     }

     public function getAddress()
     {
         return $this->address;
     }

    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
        return $this;
    }

    public function setVatNumber($vatNumber)
    {
        $this->vatNumber = $vatNumber;
        return $this;
    }

     public function setCountry($country)
     {
         $this->country = $country;
         return $this;
     }

     public function setCity($city)
     {
         $this->city = $city;
         return $this;
     }
     public function setEvcnumber($evcnumber)
     {
         $this->evcnumber = $evcnumber;
         return $this;
     }
     public function setEvcCredit($evccredit)
     {
         $this->evccredit = $evccredit;
         return $this;
     }

     public function setAddress($address)
     {
         $this->address = $address;
         return $this;
     }

    /**
     * @param mixed $firstName
     * @return Customer
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return Customer
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return Customer
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return Customer
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    /**
     * @param mixed $contactNumber
     * @return Customer
     */
    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;
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
     * @return Customer
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * @param mixed $credit
     * @return Customer
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;
        return $this;
    }


    public function setSumNewCredit($credit)
    {
        $this->credit = round($this->credit + $credit,0);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAllowLogin($handleForClient = false)
    {
        $allowLogin = $this->allowLogin;
        if ($handleForClient) {
            switch ($allowLogin) {
                case 1:
                    $allowLogin = '';
                    break;
                case 0:
                    $allowLogin = 'Suspended';
                    break;
            }
        }
        return $allowLogin;
    }

    /**
     * @param mixed $allowLogin
     * @return Customer
     */
    public function setAllowLogin($allowLogin)
    {
        $this->allowLogin = $allowLogin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus($handleForClient = false)
    {
        $status = $this->status;
        if ($handleForClient) {
            switch ($status) {
                case 1:
                    $status = 'Active';
                    break;
                case 0:
                    $status = 'Passive';
                    break;
            }
        }

        return $status;
    }

    /**
     * @param mixed $status
     * @return Customer
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     * @return Customer
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     * @return Customer
     */
    public function setIp($ip = null)
    {
        if (is_null($ip)) {
            if( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
                if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
                    $addr = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
                    $ip = trim($addr[0]);
                } else {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }
            }
            else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }

        $this->ip = $ip;

        return $this;
    }

    /**
     * @return null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param null $deletedAt
     * @return Customer
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    /**
     * @return null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param null $createdAt
     * @return Customer
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param null $updatedAt
     * @return Customer
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
     * @return Customer
     */
    public function setList($list)
    {
        $this->list = $list;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvatar($fullPath = false)
    {
        $avatar = $this->avatar;

        if ($fullPath) {
            $avatar = '/images/customer/avatar/' . (!empty($avatar) ? $avatar : 'no-avatar.png');
        }

        return $avatar;
    }

    /**
     * @param mixed $avatar
     * @return Customer
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }


    public function session()
    {
        if ($this->id) {
            /* @var Session $session */
            $session = $this->container->get('session');
            $session->set('customerLogin', true);
            $session->set('customerId', $this->id);
        }
    }

    public function check()
    {
        /* @var Session $session */
        $session = $this->container->get('session');

        try {

            if (!empty($customerId = $session->get('customerId'))) {

                $customer = (new self())->find($customerId);

                if (empty($customer) || !$customer->getStatus() || !$customer->getAllowLogin())
                    throw new \Exception('');

                $customer->currency();
                $customer->cart();
                $customer->cartevc();
                $this->container->set('customer', $customer);

            } else {

                if (empty($token = $this->request->cookies->get('token')))
                    throw new \Exception('');

                $customer = (new self())->findOneBy(['filter' => ['token' => $token, 'status' => 1, 'allow_login' => 1]]);

                if (empty($customer))
                    throw new \Exception('');

                $customer->session();
                $customer->currency();
                $customer->cart();
                $customer->cartevc();
                $this->container->set('customer', $customer);
            }
        } catch (\Exception $e) {
            if (!in_array(@$this->request->query->keys()[0], ['paypal/callback','btcpayserver/callback','iyzipay/callback','panel/login', 'panel/register', 'panel/forgot-password', 'panel/reset-password', 'panel/account-activation'])) {
                header('location: /panel/login');
                exit();
            }
        }

        return true;
    }

    public function currency()
    {
        /* @var Session $session */
        $session = $this->container->get('session');

        /* @var Currency $currency */
        $currency = $this->container->get('currency');

        $currencyCode = $session->get('currencyCode') ?? 'TRY';

        $this->currency = $currency->get($currencyCode);
    }

    public function cart()
    {
        $cart = new Cart();
        $cart->setCustomer($this);
        $cart->setList((new Cart())->findBy(['filter' => ['customer_id' => $this->id]]));
        $this->cart = $cart;
    }

    public function cartevc()
    {
        $cart = new CartEvc();
        $cart->setCustomer($this);
        $cart->setList((new CartEvc())->findBy(['filter' => ['customer_id' => $this->id]]));
        $this->cartevc = $cart;
    }


    public function totalOrderCredit()
    {
        return $this->database->query('SELECT SUM(total_credit) FROM `order` WHERE state IN ("new", "completed") AND customer_id=' . $this->id)->fetchColumn() ?? 0;
    }


    public function homeTotalOrderCredit()
    {
        return $this->database->query('SELECT SUM(total_credit) FROM `order` WHERE state IN ("new", "completed") AND payment_status="completed" AND customer_id=' . $this->id)->fetchColumn() ?? 0;
    }


    public function totalSpendingCredit()
    {
        return $this->database->query('SELECT SUM(total_credit) FROM `customer_vehicle` WHERE status IN ("pending", "process", "completed") AND customer_id=' . $this->id)->fetchColumn() ?? 0;
    }

    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function sendConfirmationMail()
    {
        (new EmailNotification())->send('customer', 'confirmation', $this);
    }

    public function sendForgotEmail()
    {
        (new EmailNotification())->send('customer', 'forgotPassword', $this);
    }

    public function summaryTotal()
    {
        return $this->database->query('SELECT COUNT(id) FROM customer')->fetchColumn() ?? 0;
    }

    public function notifications()
    {
        $notifications = [];
        $customerVehicles = (new CustomerVehicle())->findBy(['filter' => ['customer_id' => $this->id, 'notification' => 1]]);

        if (!empty($customerVehicles)) {
            /* @var CustomerVehicle $customerVehicle */
            foreach ($customerVehicles as $customerVehicle) {
                $notifications[] = [
                    'title' => self::notificationTitle($customerVehicle->getStatus()),
                    'datetime' => self::processDate($customerVehicle->getChangedAt())
                ];
            }
        }

        return $notifications;
    }

    public static function notificationTitle($state)
    {
        $states = [
            'awaiting_payment' => 'Awaiting Payment',
            'pending' => 'Your file has been added to the system',
            'process' => 'Your file is in process',
            'completed' => 'Your file is complete',
            'cancel' => 'Your file has been canceled',
        ];

        return $states[$state];
    }

    public function processDate($date)
    {
        $dateTime = new \DateTime();
        $changedAt = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
        $interval = $dateTime->diff($changedAt);
        return ($interval->format('%a') > 0) ? $changedAt->format('m/d/y') : $changedAt->format('H:s');
    }

    /**
     * @return bool
     */
    public function hasCustomerGroupPromotion(): bool
    {
        if (empty($this->customerGroup)) {
            return false;
        }

        return $this->customerGroup->getMultiplier() > 0 && $this->customerGroup->getType() == 'decrease';
    }

    /**
     * @return bool
     */
    public function hasCustomerGroupIncrease(): bool
    {
        if (empty($this->customerGroup)) {
            return false;
        }

        if($this->customerGroup->getExtra() > 0)
            return true;

        return $this->customerGroup->getMultiplier() > 0 && $this->customerGroup->getType() == 'increase';
    }

    /**
     * @return Int
     */
    public function hasCustomerGroupTaxRate()
    {
        if (empty($this->customerGroup)) {
            return 0;
        }

        if($this->customerGroup->getTaxRate() > 0)
            return $this->customerGroup->getTaxRate();
        else
            return 0;
    }

    public function calculatePriceIncreaseForCustomerGroup($price)
    {
        switch ($this->customerGroup->getProcessType()) {
            case 'percent':
                if($this->customerGroup->getExtra() > 0)
                    $price = $price + (($price / 100) * $this->customerGroup->getExtra());
                else
                    $price = $price + (($price / 100) * $this->customerGroup->getMultiplier());
                break;
            case 'amount':
                if($this->customerGroup->getExtra() > 0)
                    $price = $price + $this->customerGroup->getMultiplier();
                else
                    $price = $price + $this->customerGroup->getMultiplier();
                break;
        }

        return $price;
    }

    /**
     * @return bool
     */
    public function hasCustomerGroupBonusCredit(): bool
    {
        if (empty($this->customerGroup)) {
            return false;
        }

        return $this->customerGroup->getBonusCredit() > 0;
    }

    public function calculateBonusCreditForCustomerGroup($credit)
    {
        $bonusCredit = 0;

        switch ($this->customerGroup->getBonusCreditType()) {
            case 'percent':
                $bonusCredit = ($credit / 100) * $this->customerGroup->getBonusCredit();
                break;
            case 'amount':
                $bonusCredit = $this->customerGroup->getBonusCredit();
                break;
        }

        return $bonusCredit;
    }
}
