<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class Page extends BaseModel
{
    private $type;
    private $url;
    private $metaTitle;
    private $metaKeywords;
    private $metaDescription;
    private $ogUrl;
    private $ogTitle;
    private $ogDescription;
    private $ogImage;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Page
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return Page
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * @param mixed $metaTitle
     * @return Page
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param mixed $metaKeywords
     * @return Page
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param mixed $metaDescription
     * @return Page
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOgUrl()
    {
        return $this->ogUrl;
    }

    /**
     * @param mixed $ogUrl
     * @return Page
     */
    public function setOgUrl($ogUrl)
    {
        $this->ogUrl = $ogUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOgTitle()
    {
        return $this->ogTitle;
    }

    /**
     * @param mixed $ogTitle
     * @return Page
     */
    public function setOgTitle($ogTitle)
    {
        $this->ogTitle = $ogTitle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOgDescription()
    {
        return $this->ogDescription;
    }

    /**
     * @param mixed $ogDescription
     * @return Page
     */
    public function setOgDescription($ogDescription)
    {
        $this->ogDescription = $ogDescription;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOgImage()
    {
        return $this->ogImage;
    }

    /**
     * @param mixed $ogImage
     * @return Page
     */
    public function setOgImage($ogImage)
    {
        $this->ogImage = $ogImage;
        return $this;
    }

    public function menuBuilder($items)
    {
        foreach ($items as $item) {
            $this->{$item};
        }
    }

    public function business()
    {
        $business = new Business();
        $business->setList(
            $business->findBy([
                'filter' => ['is_active' => 1],
                'order' => [ 'field' => 'sortOrder', 'sort' => 'ASC']
            ])
        );

        $this->container->set('business' , $business);

        return $this;

    }

    public function service()
    {
        $service= new Service();
        $service->setList(
            $service->findBy([
                'filter' => ['is_active' => 1],
                'order' => [ 'field' => 'sortOrder', 'sort' => 'ASC']
            ])
        );

        $this->container->set('service' , $service);

        return $this;
    }

    public function keyword()
    {
        $keyword = (new Search())->rand(1);
        $this->container->set('keyword' , $keyword);

        return $this;
    }

    public function keywords()
    {
        $keywords = (new Search())->getAll();
        $this->container->set('keywords' , $keywords);

        return $this;
    }

    public function query()
    {
        $query = (!empty($_GET['aranan']) ? $_GET['aranan'] : '');
        $this->container->set('query' , $query);

        return $this;
    }

    public function additionalPages($limit, $offset)
    {
        return $this->database->query('select * from eksayfalar where durum="1" order by sira asc limit ' . $offset . ',' . $limit)->fetchAll(PDO::FETCH_OBJ);
    }

}
