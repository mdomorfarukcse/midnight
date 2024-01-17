<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use Pemm\Model\EmailNotification;
use PDO;
use Symfony\Component\HttpFoundation\Session\Session;

class User extends BaseModel
{
    private $id;
    private $userRole;
    private $firstName;
    private $lastName;
    private $email;
    private $password;
    private $avatar;
    private $contactNumber;
    private $token;
    private $allowLogin;
    private $status;
    private $reference;
    private $ip;
    private $deletedAt;
    private $createdAt;
    private $updatedAt;

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
                $where[] = 'user.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'user.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['email'])) {
            $where[] = 'user.email=:email';
            $executeData[':email'] = $criteria['filter']['email'];
        }

        if (isset($criteria['filter']['status'])) {
            $where[] = 'user.status=:status';
            $executeData[':status'] = $criteria['filter']['status'];
        }

        if (isset($criteria['filter']['allow_login'])) {
            $where[] = 'user.allow_login=:allow_login';
            $executeData[':allow_login'] = $criteria['filter']['allow_login'];
        }

        $sql = 'SELECT * FROM user';

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
                    $sql .= ' ORDER BY user.id ';
                    break;
                default:
                    $sql .= ' ORDER BY user.id ';
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
            ->setUserRole($data->user_role)
            ->setFirstName($data->first_name)
            ->setLastName($data->last_name)
            ->setEmail($data->email)
            ->setPassword($data->password)
            ->setAvatar($data->avatar)
            ->setContactNumber($data->contact_number)
            ->setToken($data->token)
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
            'user_role' => $this->userRole,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,
            'avatar' => $this->avatar,
            'contact_number' => $this->contactNumber,
            'token' => $this->token,
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
            if ($result = $this->database->insert('user', $data)) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $data['updated_at'] = (new \DateTime())->format('Y-m-d H:i:s');
            $data['id'] = $this->id;
            $result = $this->database->update('user', $data);
        }

        return $result;
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
     * @return User
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserRole()
    {
        return $this->userRole;
    }

    /**
     * @param mixed $userRole
     * @return User
     */
    public function setUserRole($userRole)
    {
        $this->userRole = $userRole;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     * @return User
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
     * @return User
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
     * @return User
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
     * @return User
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
     * @return User
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
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAllowLogin()
    {
        return $this->allowLogin;
    }

    /**
     * @param mixed $allowLogin
     * @return User
     */
    public function setAllowLogin($allowLogin)
    {
        $this->allowLogin = $allowLogin;
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
     * @return User
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
     * @return User
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     * @return User
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
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
     * @return User
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
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvatar($fullPath = false)
    {
        $avatar = $this->avatar;

        if ($fullPath) {
            $avatar = '/images/admin/avatar/' . (!empty($avatar) ? $avatar : 'no-avatar.png');
        }

        return $avatar;
    }

    /**
     * @param mixed $avatar
     * @return User
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
            $session->set('userLogin', true);
            $session->set('userId', $this->id);
        }
    }

    public function check()
    {
        try {

            /* @var Session $session */
            $session = $this->container->get('session');

            if (!empty($userId = $session->get('userId'))) {

                $user = (new self())->find($userId);
                if (empty($user) || !$user->getStatus() || !$user->getAllowLogin())
                    throw new \Exception('');

                $this->container->set('user', $user);

            } else {
                if (empty($token = $this->request->cookies->get('user_token')))
                    throw new \Exception('');

                $user = (new self())->findOneBy(['filter' => ['token' => $token, 'status' => 1, 'allow_login' => 1]]);

                if (empty($user))
                    throw new \Exception('');

                $user->session();
                $this->container->set('user', $user);
            }
        }catch (\Exception $e) {
            if (!in_array($this->request->getRequestUri(), ['/admin/login'])) {
                $this->logout();
            }
        }

        return true;
    }

    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function logout()
    {
        /* @var Session $session */
        $session = $this->container->get('session');

        setcookie('user_token', '', time(), '/');
        $session->remove('userLogin');
        $session->remove('userId');
        header('location: /admin/login');
    }

}
