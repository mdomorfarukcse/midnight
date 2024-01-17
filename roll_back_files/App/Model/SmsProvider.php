<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class SmsProvider extends BaseModel
{
    private $id;
    private $name;
    private $header;
    private $token;
    private $token2;
    private $number;
    private $status = 1;
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

    public function findAll()
    {
        return $this->findBy([]);
    }

    public function findBy($criteria, $findOne = false)
    {
        $where = $executeData = [];

        if (isset($criteria['filter']['id'])) {
            if (is_array($criteria['filter']['id'])) {
                $where[] = 'sms_providers.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'sms_providers.id=:id';
                $executeData[':id'] = intval($criteria['filter']['id']);
            }
        }

        if (isset($criteria['filter']['code'])) {
            $where[] = 'sms_providers.code=:code';
            $executeData[':code'] = $criteria['filter']['code'];
        }

        if (isset($criteria['filter']['status'])) {
            $where[] = 'sms_providers.status=:status';
            $executeData[':status'] = $criteria['filter']['status'];
        }

        $sql = 'SELECT * FROM sms_providers';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
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
     * @return SmsProvider
     */
    public function initialize($data): SmsProvider
    {
        return (new self())
            ->setId($data->id)
            ->setName($data->name)
            ->setHeader($data->header)
            ->setToken($data->token)
            ->setToken2($data->token2)
            ->setNumber($data->number)
            ->setStatus($data->status)
            ->setCreatedAt($data->created_at)
            ->setUpdatedAt($data->updated_at);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('sms_providers', [
                'name' => $this->name,
                'header' => $this->header,
                'token' => $this->token,
                'token2' => $this->token2,
                'number' => $this->number,
                'status' => $this->status,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('sms_providers', [
                'id' => $this->id,
                'name' => $this->name,
                'header' => $this->header,
                'token' => $this->token,
                'number' => $this->number,
                'token2' => $this->token2,
                'status' => $this->status,
                'created_at' => date('Y-m-d H:i:s'),
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
     * @return SmsProvider
     */
    public function setId($id)
    {
        $this->id = $id;
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
         * @return SmsProvider
         */
        public function setName($name)
        {
            $this->name = $name;
            return $this;
        }


            /**
             * @return mixed
             */
            public function getHeader()
            {
                return $this->header;
            }

            /**
             * @param mixed $name
             * @return SmsProvider
             */
            public function setHeader($header)
            {
                $this->header = $header;
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
             * @param mixed $name
             * @return SmsProvider
             */
            public function setToken($token)
            {
                $this->token = $token;
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
     * @return SmsProvider
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
     * @return SmsProvider
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
     * @return SmsProvider
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }


        /**
         * @return mixed
         */
        public function getToken2()
        {
            return $this->token2;
        }

        /**
         * @param mixed $updatedAt
         * @return SmsProvider
         */
        public function setToken2($token2)
        {
            $this->token2 = $token2;
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
                 * @param mixed $updatedAt
                 * @return SmsProvider
                 */
                public function setNumber($number)
                {
                    $this->number = $number;
                    return $this;
                }




}
