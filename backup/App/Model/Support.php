<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use Pemm\Model\Customer;
use PDO;

class Support extends BaseModel
{
    private $id;
    private $reference;
    private $type;

    /* @var Customer */
    public $customer;

    /* @var User|null */
    public $admin = null;

    private $customerRead;
    private $administratorRead;
    private $firstQuestion;
    private $text;
    private $status;
    private $updatedAt;
    private $createdAt;
    private $subject;
    private $vehicle;
    private $cvid;
    private $file;

    private $inboxSupportCount = 0;
    private $inboxReadSupportMessageCount = 0;
    private $inboxUnReadSupportMessageCount = 0;
    private $openSupportCount = 0;
    private $closedSupportCount = 0;

    private $readSupportMessageCount = 0;
    private $unreadSupportMessageCount = 0;

    /**
     * @var Support|null
     */
    private $lastMessage = null;

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
                $where[] = 'support.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'support.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['customer_id'])) {
            $where[] = 'support.customer_id=:customer_id';
            $executeData[':customer_id'] = $criteria['filter']['customer_id'];
        }

        if (isset($criteria['filter']['reference'])) {
            $where[] = 'support.reference=:reference';
            $executeData[':reference'] = $criteria['filter']['reference'];
        }

        if (isset($criteria['filter']['type'])) {
            $where[] = 'support.type=:type';
            $executeData[':type'] = $criteria['filter']['type'];
        }

        if (isset($criteria['filter']['status'])) {
            if (is_array($criteria['filter']['status'])) {
                $where[] = 'support.status IN ("' . implode('","', $criteria['filter']['status']) . '")';
            } else {
                $where[] = 'support.status=:status';
                $executeData[':status'] = $criteria['filter']['status'];
            }
        }

        if (isset($criteria['filter']['customer_read'])) {
            $where[] = 'support.customer_read=:customer_read';
            $executeData[':customer_read'] = $criteria['filter']['customer_read'];
        }

        if (isset($criteria['filter']['administrator_read'])) {
            $where[] = 'support.administrator_read=:administrator_read';
            $executeData[':administrator_read'] = $criteria['filter']['administrator_read'];
        }

        if (isset($criteria['filter']['first_question'])) {
            $where[] = 'support.first_question=:first_question';
            $executeData[':first_question'] = $criteria['filter']['first_question'];
        }

        $sql = 'SELECT * FROM support';

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
                    $sql .= ' ORDER BY support.id ';
                    break;
                case 'updated_at':
                    $sql .= ' ORDER BY support.updated_at ';
                    break;
                case 'created_at':
                    $sql .= ' ORDER BY support.created_at ';
                    break;
                default:
                    $sql .= ' ORDER BY support.id ';
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

    /**
     * @param $data
     * @return Support
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setType($data->type)
            ->setReference($data->reference)
            ->setCustomer((new Customer())->find($data->customer_id))
            ->setAdmin((new User)->find($data->admin_id))
            ->setCustomerRead($data->customer_read)
            ->setAdministratorRead($data->administrator_read)
            ->setFirstQuestion($data->first_question)
            ->setText($data->text)
            ->setFile($data->file)
            ->setStatus($data->status)
            ->setCreatedAt($data->created_at)
            ->setUpdatedAt($data->updated_at)
            ->setSubject($data->subject)
            ->setVehicle($data->vehicle)
            ->setCVId($data->vehicle);
    }

    public function store()
    {

        if (empty($this->id)) {
            if ($this->database->insert('support', [
                'reference' => $this->reference,
                'c_v_id' => $this->cvid,
                'type' => $this->type,
                'customer_id' => $this->customer->getId(),
                'customer_read' => $this->customerRead,
                'admin_id' => !empty($this->admin) ? $this->admin->getId() : 0,
                'administrator_read' => $this->administratorRead,
                'first_question' => $this->firstQuestion,
                'text' => $this->text,
                'file' => $this->file,
                'status' => $this->status,
                'subject' => $this->subject,
                'vehicle' =>  $this->getVehicleName(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('support', [
                'id' => $this->id,
                'reference' => $this->reference,
                'type' => $this->type,
                'customer_id' => $this->customer->getId(),
                'customer_read' => $this->customerRead,
                'admin_id' => !empty($this->admin) ? $this->admin->getId() : 0,
                'administrator_read' => $this->administratorRead,
                'first_question' => $this->firstQuestion,
                'text' => $this->text,
                'file' => $this->file,
                'status' => $this->status,
                'subject' => $this->subject,
                'vehicle' =>  $this->getVehicleName(),
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
     * @return Support
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return Support
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Support
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return Support
     */
    public function setCustomer(Customer $customer): Support
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getAdmin(): ?User
    {
        return $this->admin;
    }

    /**
     * @param User|null $admin
     * @return Support
     */
    public function setAdmin(?User $admin): Support
    {
        $this->admin = $admin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerRead()
    {
        return $this->customerRead;
    }

    /**
     * @param mixed $customerRead
     * @return Support
     */
    public function setCustomerRead($customerRead)
    {
        $this->customerRead = $customerRead;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCustomerRead()
    {
        return $this->customerRead == 1;
    }

    /**
     * @return mixed
     */
    public function getAdministratorRead()
    {
        return $this->administratorRead;
    }

    /**
     * @param mixed $administratorRead
     * @return Support
     */
    public function setAdministratorRead($administratorRead)
    {
        $this->administratorRead = $administratorRead;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdministratorRead()
    {
        return $this->administratorRead == 1;
    }

    /**
     * @return mixed
     */
    public function getFirstQuestion()
    {
        return $this->firstQuestion;
    }

    /**
     * @param mixed $firstQuestion
     * @return Support
     */
    public function setFirstQuestion($firstQuestion)
    {
        $this->firstQuestion = $firstQuestion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return Support
     */
    public function setText($text)
    {
        $this->text = $text;
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
     * @return Support
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
     * @return Support
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
     * @return Support
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        if (!$this->firstQuestion) {
            $firstQuestion = (new self)->findOneBy(['filter' => ['reference' => $this->reference, 'first_question' => 1]]);
            $this->subject = $firstQuestion->getSubject();
        }

        return $this->subject;
    }

    /**
     * @param mixed $subject
     * @return Support
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
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
     * @return Support
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * @return string|null
     */
    public function getVehicleName()
    {

        if(is_int($this->vehicle)) {
            $vehicle = (new CustomerVehicle())->find($this->vehicle);

            if (empty($vehicle)) {
                return null;
            }
            return $vehicle->getWMVdata('wmv_vehicle_name') != NULL ? $vehicle->getWMVdata('wmv_vehicle_name') : $vehicle->getVehicle()->getFullName();
        }

        return $this->vehicle;
    }

    /**
     * @param mixed $subject
     * @return Support
     */
    public function setCVId($id)
    {
        $this->cvid = $id;
        return $this;
    }

    /**
     * @param mixed $subject
     * @return Support
     */
    public function setVehicle($vehicle)
    {
        $this->vehicle = $vehicle;
        return $this;
    }

    public function getSupportDateOrTime()
    {
        $dateTime = new \DateTime();
        $createdAt = \DateTime::createFromFormat('Y-m-d H:i:s', $this->getCreatedAt());
        $interval = $dateTime->diff($createdAt);
        return ($interval->format('%a') > 0) ? $createdAt->format('m/d/y') : $createdAt->format('H:i');
    }

    public function getSubMessages()
    {
        return (new self())->findBy(['filter' => ['reference' => $this->reference], 'order' => ['field' => 'id', 'sort' => 'asc']]);
    }

    public function who($type)
    {
        return $this->type == $type ? 'me' : 'you';
    }
    public function read($type)
    {
        $field = null;

        switch ($type) {
            case 'admin':
                $field = 'administrator_read';
                break;
            case 'customer':
                $field = 'customer_read';
                break;
        }

        if (!empty($field)) {
            $prepare = $this->database->prepare('UPDATE support SET ' . $field . '=1 WHERE reference=:reference');
            $prepare->execute([':reference' => $this->reference]);
        }
    }

    public function isOpen()
    {
        return in_array($this->status, ['pending', 'answered']);
    }

    /**
     * @return int
     */
    public function getInboxSupportCount(): int
    {
        return $this->inboxSupportCount;
    }

    /**
     * @param int $inboxSupportCount
     * @return Support
     */
    public function setInboxSupportCount(int $inboxSupportCount): Support
    {
        $this->inboxSupportCount = $inboxSupportCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getOpenSupportCount(): int
    {
        return $this->openSupportCount;
    }

    /**
     * @param int $openSupportCount
     * @return Support
     */
    public function setOpenSupportCount(int $openSupportCount): Support
    {
        $this->openSupportCount = $openSupportCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getClosedSupportCount(): int
    {
        return $this->closedSupportCount;
    }

    /**
     * @param int $closedSupportCount
     * @return Support
     */
    public function setClosedSupportCount(int $closedSupportCount): Support
    {
        $this->closedSupportCount = $closedSupportCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getInboxReadSupportMessageCount(): int
    {
        return $this->inboxReadSupportMessageCount;
    }

    /**
     * @param int $inboxReadSupportMessageCount
     * @return Support
     */
    public function setInboxReadSupportMessageCount(int $inboxReadSupportMessageCount): Support
    {
        $this->inboxReadSupportMessageCount = $inboxReadSupportMessageCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getInboxUnReadSupportMessageCount(): int
    {
        return $this->inboxUnReadSupportMessageCount;
    }

    /**
     * @param int $inboxUnReadSupportMessageCount
     * @return Support
     */
    public function setInboxUnReadSupportMessageCount(int $inboxUnReadSupportMessageCount): Support
    {
        $this->inboxUnReadSupportMessageCount = $inboxUnReadSupportMessageCount;
        return $this;
    }

    public static function counter($customerId = null, $type = null)
    {
        $support = new Support();

        $filter['first_question'] = 1;

        if ($customerId) {
            $filter['customer_id'] = $customerId;
        }

        $support->setOpenSupportCount(count($support->findBy(['filter' => array_merge($filter, ['status' => ['pending', 'answered']])])));
        $support->setClosedSupportCount(count($support->findBy(['filter' => array_merge($filter, ['status' => 'closed'])])));

        unset ($filter['first_question']);

        $filter['type'] = !empty($customerId) ? 'admin' : 'customer';

        $support->setInboxSupportCount(count($support->findBy(['filter' => $filter])));

        if ($customerId) {
            $support->setInboxReadSupportMessageCount(
                count($support->findBy(['filter' => array_merge($filter, ['customer_read' => 1])]))
            );
            $support->setInboxUnReadSupportMessageCount(
                count($support->findBy(['filter' => array_merge($filter, ['customer_read' => 0])]))
            );
        } else {
            $support->setInboxReadSupportMessageCount(
                count($support->findBy(['filter' => array_merge($filter, ['administrator_read' => 1])]))
            );
            $support->setInboxUnReadSupportMessageCount(
                count($support->findBy(['filter' => array_merge($filter, ['administrator_read' => 0])]))
            );
        }


        return $support;
    }

    public function getLastMessage(): Support
    {
        if (is_null($this->lastMessage)) {
            $this->lastMessage = $this->findOneBy([
                'filter' => ['reference' => $this->reference],
                'order' => ['field' => 'id', 'sort' => 'DESC']
            ]);
        }
        return $this->lastMessage;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        // $path = $this->type == 'customer' ? $this->customer->getAvatar() : $this->admin->getAvatar();

        return '/images/' . $this->type . '/avatar/' . (!empty($path) ? $path : 'no-avatar.png');
    }
}
