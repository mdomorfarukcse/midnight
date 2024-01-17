<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class ProductReview extends BaseModel
{
    private $id;
    private $title;
    private $description;
    private $status;
    private $rate;
    private $productId;
    private $answer;
    private $date;
    private $customerName;
    private $customerEmail;

    private $filterParams;
    private $list;

    public function filter()
    {

        $where = $executeData = [];

        if (!empty($this->filterParams['filter']['id'])) {
            $where[] = 'urun_yorumlar.id=:id';
            $executeData[':id'] = $this->filterParams['filter']['id'];
        }

        if (!empty($this->filterParams['filter']['product_id'])) {
            $where[] = 'urun_yorumlar.urunid=:product_id';
            $executeData[':product_id'] = $this->filterParams['filter']['product_id'];
        }

        if (!empty($this->filterParams['filter']['status'])) {
            $where[] = 'urun_yorumlar.durum=:status';
            $executeData[':status'] = $this->filterParams['filter']['status'];
        }

        $sql = 'SELECT * FROM urun_yorumlar';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        if (!empty($this->filterParams['pagination'])) {

            try {

                $prepare = $this->database->prepare($sql);
                $prepare->execute($executeData);

                $this->filterParams['pagination']['total_count'] = $prepare->rowCount();

            } catch (\Exception $e) {}

        }

        if (!empty($this->filterParams['order'])) {
            switch ($this->filterParams['order']['field']) {
                case 'id':
                    $sql .= ' ORDER BY urun_yorumlar.id ';
                    break;
                case 'product_id':
                    $sql .= ' ORDER BY urun_yorumlar.product_id ';
                    break;
                case 'status':
                    $sql .= ' ORDER BY urun_yorumlar.durum ';
                    break;
                default:
                    $sql .= ' ORDER BY urun_yorumlar.id ';
                    $this->filterParams['order']['sort'] = 'DESC';
                    break;
            }
            $sql .= $this->filterParams['order']['sort'];
        }

        if (!empty($this->filterParams['pagination'])) {

            if (!empty($this->filterParams['pagination']['limit'])) {
                $sql .= ' LIMIT ' . $this->filterParams['pagination']['limit'];
                $this->filterParams['pagination']['total_page'] = ceil($this->filterParams['pagination']['total_count'] / $this->filterParams['pagination']['limit']);

                if (!empty($this->filterParams['pagination']['page'])) {
                    $sql .= ' OFFSET ' . (($this->filterParams['pagination']['page'] - 1) * $this->filterParams['pagination']['limit']);
                }
            }

        }

        try {

            $prepare = $this->database->prepare($sql);
            $prepare->execute($executeData);
            $reviewList = $prepare->fetchAll(PDO::FETCH_OBJ);

            if (!empty($reviewList)) {
                foreach ($reviewList as $key => $review) {
                    $this->list[$key] = (new self())->setId($review->id)
                                                    ->setTitle($review->baslik)
                                                    ->setDescription($review->yorum)
                                                    ->setStatus($review->durum)
                                                    ->setProductId($review->urunid)
                                                    ->setRate($review->yildiz)
                                                    ->setAnswer($review->cevap)
                                                    ->setDate($review->tarih)
                                                    ->setCustomerName($review->isim)
                                                    ->setCustomerEmail($review->eposta);
                }
            }
        } catch (\Exception $e) {print_r($e);die;}

        return $this->list;
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
     * @return ProductReview
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return ProductReview
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return ProductReview
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * @return ProductReview
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
     * @return ProductReview
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param mixed $answer
     * @return ProductReview
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return ProductReview
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * @param mixed $customerName
     * @return ProductReview
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * @param mixed $customerEmail
     * @return ProductReview
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilterParams()
    {
        return $this->filterParams;
    }

    /**
     * @param mixed $filterParams
     * @return ProductReview
     */
    public function setFilterParams($filterParams)
    {
        $this->filterParams = $filterParams;
        return $this;
    }

    /**
     * @return Database|null
     */
    public function getDatabase(): ?Database
    {
        return $this->database;
    }

    /**
     * @param Database|null $database
     * @return ProductReview
     */
    public function setDatabase(?Database $database): ProductReview
    {
        $this->database = $database;
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
     * @return ProductReview
     */
    public function setList($list)
    {
        $this->list = $list;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     * @return ProductReview
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }
}
