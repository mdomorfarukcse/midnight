<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class Category extends BaseModel
{
    private $id;
    private $type;
    private $parentId;
    private $slug;
    private $name;
    private $icon;
    private $image;
    private $banner;
    private $metaTitle;
    private $metaKeywords;
    private $metaDescription;
    private $description;
    private $status;
    private $homePageShow = 0;
    private $sortOrder = 999;
    private $deletedAt;
    private $updatedAt;
    private $createdAt;

    private $subCategories;
    private $children;

    /* @var Category */
    public $parent;

    const TABLE_NAME = 'categories';

    const TABLE_FIELDS = ['id', 'parent_id', 'sort_order', 'status', 'slug', 'name', 'icon', 'banner', 'meta_title',
        'meta_description', 'meta_keywords', 'description', 'created_at', 'updated_at'];

    private $filterParams;
    private $allCategories;
    private $childrenOfTheFamily;

    private $base;

    const ICON_PATH = '/icon/category/';

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
                $where[] = 'categories.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'categories.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        if (isset($criteria['filter']['type'])) {
            if (is_array($criteria['filter']['type'])) {
                $where[] = 'categories.type IN (' . implode(',', $criteria['filter']['type']) . ')';
            } else {
                $where[] = 'categories.type=:type';
                $executeData[':type'] = $criteria['filter']['type'];
            }
        }

        if (isset($criteria['filter']['parent_id'])) {
            if (is_array($criteria['filter']['parent_id'])) {
                $where[] = 'categories.parent_id IN (' . implode(',', $criteria['filter']['parent_id']) . ')';
            } else {
                $where[] = 'categories.parent_id=:parent_id';
                $executeData[':parent_id'] = $criteria['filter']['parent_id'];
            }
        }

        // samet fix
        if (isset($criteria['filter']['brands'])) {
            $where[] = 'categories.parent_id != 0';
        }

        if (isset($criteria['filter']['type'])) {
            $where[] = 'categories.type=:type';
            $executeData[':type'] = $criteria['filter']['type'];
        }
        // samet fix

        if (isset($criteria['filter']['slug'])) {
            $where[] = 'categories.slug=:slug';
            $executeData[':slug'] = $criteria['filter']['slug'];
        }

        if (isset($criteria['filter']['slug'])) {
            $where[] = 'categories.slug=:slug';
            $executeData[':slug'] = $criteria['filter']['slug'];
        }

        if (isset($criteria['filter']['base'])) {
            $where[] = 'categories.base=:base';
            $executeData[':base'] = $criteria['filter']['base'];
        }

        if (isset($criteria['filter']['name'])) {
            $where[] = 'categories.name LIKE "%' . $criteria['filter']['name'] . '%"';
        }

        if (!empty($criteria['filter']['datatable_query'])) {
            $explode = explode(' ', $criteria['filter']['datatable_query']);
            $explode = array_filter($explode);
            foreach ($explode as $_ex) {
                $where[] = 'LOWER(categories.name) LIKE LOWER("%' . $_ex . '%")';
            }
        }

        if (!empty($criteria['filter']['is_active'])) {
            $where[] = 'categories.status=1';
            //$where[] = 'categories.deleted_at NOT NULL';
        }

        $sql = 'SELECT * FROM categories';

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
                    $sql .= ' ORDER BY categories.id ';
                    break;
                default:
                    $sql .= ' ORDER BY categories.id ';
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
            ->setType($data->type)
            ->setParentId($data->parent_id)
            ->setSlug($data->slug)
            ->setName($data->name)
            ->setIcon($data->icon)
            ->setImage($data->image)
            ->setBanner($data->banner)
            ->setMetaTitle($data->meta_title)
            ->setMetaKeywords($data->meta_keywords)
            ->setMetaDescription($data->meta_description)
            ->setDescription($data->description)
            ->setStatus($data->status)
            ->setHomePageShow($data->home_page_show)
            ->setSortOrder($data->sort_order)
            ->setUpdatedAt($data->updated_at)
            ->setDeletedAt($data->deleted_at)
            ->setCreatedAt($data->created_at)
            ->setSubCategories($data->subCategories ?? '')
            ->setBase($data->base)
            ->setChildren($data->children ?? []);
    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('categories', [
                'type' => $this->type,
                'parent_id' => $this->parentId,
                'slug' => $this->slug,
                'name' => $this->name,
                'icon' => $this->icon,
                'image' => $this->image,
                'banner' => $this->banner,
                'meta_title' => $this->metaTitle,
                'meta_keywords' => $this->metaKeywords,
                'meta_description' => $this->metaDescription,
                'description' => $this->description,
                'home_page_show' => $this->homePageShow,
                'sort_order' => $this->sortOrder,
                'base' => $this->base,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('categories', [
                'id' => $this->id,
                'type' => $this->type,
                'parent_id' => $this->parentId,
                'status' => $this->status,
                'slug' => $this->slug,
                'name' => $this->name,
                'icon' => $this->icon,
                'image' => $this->image,
                'banner' => $this->banner,
                'meta_title' => $this->metaTitle,
                'meta_keywords' => $this->metaKeywords,
                'meta_description' => $this->metaDescription,
                'description' => $this->description,
                'home_page_show' => $this->homePageShow,
                'sort_order' => $this->sortOrder,
                'base' => $this->base,
                'created_at' => $this->createdAt,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $this;
    }

    public function getCategories(): array
    {
        if (empty($this->allCategories)) {
            $this->allCategories = $this->database->query('SELECT
                                                c1.*,
                                                c1.id as id,
                                                GROUP_CONCAT(c2.id) AS subCategories
                                          FROM categories c1
                                          LEFT JOIN categories c2 ON c1.id = c2.parent_id
                                          GROUP BY c1.id')->fetchAll(\PDO::FETCH_UNIQUE | \PDO::FETCH_OBJ);
        }

        return $this->allCategories;
    }

    public function getAllCategories(): array
    {
        return $this->decorateCategories($this->getCategories(), 'all');
    }

    public function categorySearchBySlug($slug): array
    {
        return $this->decorateCategories($this->getCategories(), 'slug', $slug);
    }

    public function getCategoryById($id): array
    {
        return $this->decorateCategories($this->getCategories(), 'id', $id);
    }

    public function getCategoryByType($type): array
    {
        return $this->decorateCategories($this->getCategories(), 'type', $type);
    }

    public function categorySearchByRegex($q): array
    {
        return $this->decorateCategories($this->getCategories(), 'regex', $q);
    }

    public function getCategoryByParentId($parentId): array
    {
        return $this->decorateCategories($this->getCategories(), 'parent_id', $parentId);
    }

    public function getCategoryByHomePageShow(): array
    {
        return $this->decorateCategories($this->getCategories(), 'home_page_show', 1);
    }

    public function decorateCategories($categories, $type = null, $findParam = null): array
    {

        $output = [];

        if (!empty($categories)) {
            foreach ($categories as $categoryId => $category) {
                $subExport = false;
                $breadcrumb = $this->breadcrumbs($categories, $categoryId);
                $subCategories = explode(',', $category->subCategories);
                switch ($type) {
                    case 'regex':
                        $findParam = mb_strtolower($findParam, 'UTF-8');
                        if (preg_match('/' . $findParam . '/u',
                            mb_strtolower(implode(', ', array_column($breadcrumb, 'name')), 'UTF-8'))
                        ) {
                            $output[$categoryId] = $category;
                            $output[$categoryId]->breadcrumb = $breadcrumb;
                            $subExport = true;
                        }
                        break;
                    case 'id':
                        if (is_array($findParam)) {
                            if (in_array($categoryId, $findParam)) {
                                $output[$categoryId] = $category;
                                $output[$categoryId]->breadcrumb = $breadcrumb;
                                $subExport = true;
                            }
                        } else {
                            if ($categoryId == $findParam) {
                                $output[$categoryId] = $category;
                                $output[$categoryId]->breadcrumb = $breadcrumb;
                                $subExport = true;
                            }
                        }
                        break;
                    case 'type':
                        if (is_array($findParam)) {
                            if (in_array($category->type, $findParam)) {
                                $output[$categoryId] = $category;
                                $output[$categoryId]->breadcrumb = $breadcrumb;
                                $subExport = true;
                            }
                        } else {
                            if ($category->type == $findParam) {
                                $output[$categoryId] = $category;
                                $output[$categoryId]->breadcrumb = $breadcrumb;
                                $subExport = true;
                            }
                        }
                        break;
                    case 'slug':
                        if ($category->slug == $findParam) {
                            $output[$categoryId] = $category;
                            $output[$categoryId]->breadcrumb = $breadcrumb;
                            $subExport = true;
                        }
                        break;
                    case 'parent_id':
                        if (is_array($findParam)) {
                            if (in_array($category->parent_id, $findParam)) {
                                $output[$categoryId] = $category;
                                $output[$categoryId]->breadcrumb = $breadcrumb;
                                $subExport = true;
                            }
                        } else {
                            if ($category->parent_id == $findParam) {
                                $output[$categoryId] = $category;
                                $output[$categoryId]->breadcrumb = $breadcrumb;
                                $subExport = true;
                            }
                        }
                        break;
                    case 'home_page_show':
                        if ($category->home_page_show == $findParam) {
                            $output[$categoryId] = $category;
                            $output[$categoryId]->breadcrumb = $breadcrumb;
                            $subExport = true;
                        }
                        break;
                    default:
                        $output[$categoryId] = $category;
                        $output[$categoryId]->breadcrumb = $breadcrumb;
                        $subExport = true;
                        break;
                }

                if ($subExport && !empty($subCategories)) {
                    foreach ($subCategories as $subCategory) {
                        if (!empty($subCategory)) {
                            $output[$categoryId]->children[$subCategory] = @$this->initialize($categories[$subCategory]);
                        }
                    }
                }

            }
        }

        $result = [];
        if (!empty($output)) {
            foreach ($output as $categoryId => $item) {
                $result[$categoryId] = $this->initialize($item);
            }
        }

        return $result;
    }

    public function breadcrumbs($category_index, $pid): array
    {
        if (empty($category_index[$pid])) {
            return [];
        }

        $category = $category_index[$pid];
        $result = ($category->parent_id) ? $this->breadcrumbs($category_index, $category->parent_id) : [];

        $result[] = [
            'name' => $category->name,
            'slug' => $category->slug,
            'type' => $category->type
        ];

        return $result;
    }

    public function upload($type, $files, $name)
    {
        require_once 'Resim.php';

        $max = ($type == 'icon' ? 1000 : 3000);

        $resim = new Resim();

        $_resim = $resim->kaydet(
            $files,
            ['squareCanvas' => false],
            [$name, $type, time()],
            '/images/category/',
            5000000, $max,
            ''
        );

        return $_resim['resimAdi'] . '.' .$_resim['uzanti'];
    }

    public function getBrandByEngineId($engineId)
    {
        $object = null;

        $prepare = $this->database->prepare('SELECT  brand.*
                        FROM categories brand
                        INNER JOIN categories model ON brand.id = model.parent_id
                        INNER JOIN categories generation ON model.id = generation.parent_id
                        INNER JOIN categories engine ON generation.id = engine.parent_id
                        WHERE engine.id=:engine_id');

        $prepare->execute([':engine_id' => $engineId]);

        if (!empty($category = $prepare->fetchObject())) {
            $object = $this->initialize($category);
        }

        return $object;
    }


    public function getEngineByEngineId($engineId)
    {
        $object = null;

        $prepare = $this->database->prepare('SELECT  engine.*
                        FROM categories engine
                        WHERE engine.id=:engine_id');

        $prepare->execute([':engine_id' => $engineId]);

        if (!empty($category = $prepare->fetchObject())) {
            $object = $this->initialize($category);
        }

        return $object;
    }

    /**
     * @return array
     */
    public function getSitemapUrls()
    {
        return $this->database->query('SELECT slug as url FROM categories WHERE status=1')->fetchAll();
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
     * @return Category
     */
    public function setFilterParams($filterParams): Category
    {
        $this->filterParams = $filterParams;
        return $this;
    }

    public function childrenOfTheFamily($id)
    {
        if (empty($this->allCategories[$id])) {
            return [];
        }

        $category = $this->allCategories[$id];
        $this->childrenOfTheFamily[$id] = 1;
        $result = null;
        if (!empty($category->subCategories)) {
            $subCategories = explode(',', $category->subCategories);
            if (!empty($subCategories)) {
                foreach ($subCategories as $_id) {
                    $result = $this->childrenOfTheFamily($_id);
                }
            }
        }

        return $this->childrenOfTheFamily;
    }


    /**
     * @return mixed
     */
    public function getChildrenOfTheFamily($id)
    {
        $this->childrenOfTheFamily($id);

        return $this->childrenOfTheFamily;
    }

    /**
     * @param mixed $childrenOfTheFamily
     * @return Category
     */
    public function setChildrenOfTheFamily($childrenOfTheFamily): Category
    {
        $this->childrenOfTheFamily = $childrenOfTheFamily;
        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return Category
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return Category
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param mixed $parentId
     * @return Category
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
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
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIcon($fullPath = false)
    {
        $icon = $this->icon;

        if ($fullPath)
            $icon = self::ICON_PATH . $this->icon;

        return $icon;
    }

    /**
     * @param mixed $icon
     * @return Category
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage($fullPath = false)
    {
        $image = $this->image;

        if ($fullPath) {
            $image = !empty($image) ? '/images/category/' . $image : '/assets/img/no-image.png';
        }

        return $image;
    }

    /**
     * @param mixed $image
     * @return Category
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * @param mixed $banner
     * @return Category
     */
    public function setBanner($banner)
    {
        $this->banner = $banner;
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
     * @return Category
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
     * @return Category
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
     * @return Category
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
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
     * @return Category
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
     * @return Category
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHomePageShow()
    {
        return $this->homePageShow;
    }

    /**
     * @param mixed $homePageShow
     * @return Category
     */
    public function setHomePageShow($homePageShow)
    {
        $this->homePageShow = $homePageShow;
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
     * @return Category
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
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
     * @return Category
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
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
     * @return Category
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
     * @return Category
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubCategories()
    {
        return $this->subCategories;
    }

    /**
     * @param mixed $subCategories
     * @return Category
     */
    public function setSubCategories($subCategories)
    {
        $this->subCategories = $subCategories;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     * @return Category
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    public function isActive()
    {
        return !empty($this->status) && empty($this->deletedAt);
    }

    /**
     * @return array|Category|void
     */
    public function getParent()
    {
        return $this->parent = (new Category())->find($this->parentId);
    }

    /**
     * @param Vehicle|null $vehicle
     */
    public function buildCategoryChainByVehicle(?Vehicle $vehicle)
    {
        if (!empty($vehicle)) {
            $prepare = $this->database->prepare('SELECT
                            vehicle_type.id as vehicle_type_id,
                            vehicle_type.type as vehicle_type_type,
                            vehicle_type.parent_id as vehicle_type_parent_id,
                            vehicle_type.slug as vehicle_type_slug,
                            vehicle_type.name as vehicle_type_name,
                            vehicle_type.icon as vehicle_type_icon,
                            vehicle_type.image as vehicle_type_image,
                            vehicle_type.banner as vehicle_type_banner,
                            vehicle_type.meta_title as vehicle_type_meta_title,
                            vehicle_type.meta_keywords as vehicle_type_meta_keywords,
                            vehicle_type.meta_description as vehicle_type_meta_description,
                            vehicle_type.description as vehicle_type_description,
                            vehicle_type.status as vehicle_type_status,
                            vehicle_type.home_page_show as vehicle_type_home_page_show,
                            vehicle_type.deleted_at as vehicle_type_deleted_at,
                            vehicle_type.updated_at as vehicle_type_updated_at,
                            vehicle_type.created_at as vehicle_type_created_at,
                            vehicle_type.banner as vehicle_type_banner,
                            vehicle_type.sort_order as vehicle_type_sort_order,
                            brand.id as brand_id,
                            brand.type as brand_type,
                            brand.parent_id as brand_parent_id,
                            brand.slug as brand_slug,
                            brand.name as brand_name,
                            brand.icon as brand_icon,
                            brand.image as brand_image,
                            brand.banner as brand_banner,
                            brand.meta_title as brand_meta_title,
                            brand.meta_keywords as brand_meta_keywords,
                            brand.meta_description as brand_meta_description,
                            brand.description as brand_description,
                            brand.status as brand_status,
                            brand.home_page_show as brand_home_page_show,
                            brand.deleted_at as brand_deleted_at,
                            brand.updated_at as brand_updated_at,
                            brand.created_at as brand_created_at,
                            brand.banner as brand_banner,
                            brand.sort_order as brand_sort_order,model.id as model_id,
                            model.type as model_type,
                            model.parent_id as model_parent_id,
                            model.slug as model_slug,
                            model.name as model_name,
                            model.icon as model_icon,
                            model.image as model_image,
                            model.banner as model_banner,
                            model.meta_title as model_meta_title,
                            model.meta_keywords as model_meta_keywords,
                            model.meta_description as model_meta_description,
                            model.description as model_description,
                            model.status as model_status,
                            model.home_page_show as model_home_page_show,
                            model.deleted_at as model_deleted_at,
                            model.updated_at as model_updated_at,
                            model.created_at as model_created_at,
                            model.banner as model_banner,
                            model.sort_order as model_sort_order,
                            generation.id as generation_id,
                            generation.type as generation_type,
                            generation.parent_id as generation_parent_id,
                            generation.slug as generation_slug,
                            generation.name as generation_name,
                            generation.icon as generation_icon,
                            generation.image as generation_image,
                            generation.banner as generation_banner,
                            generation.meta_title as generation_meta_title,
                            generation.meta_keywords as generation_meta_keywords,
                            generation.meta_description as generation_meta_description,
                            generation.description as generation_description,
                            generation.status as generation_status,
                            generation.home_page_show as generation_home_page_show,
                            generation.deleted_at as generation_deleted_at,
                            generation.updated_at as generation_updated_at,
                            generation.created_at as generation_created_at,
                            generation.banner as generation_banner,
                            generation.sort_order as generation_sort_order,
                            engine.id as engine_id,
                            engine.type as engine_type,
                            engine.parent_id as engine_parent_id,
                            engine.slug as engine_slug,
                            engine.name as engine_name,
                            engine.icon as engine_icon,
                            engine.image as engine_image,
                            engine.banner as engine_banner,
                            engine.meta_title as engine_meta_title,
                            engine.meta_keywords as engine_meta_keywords,
                            engine.meta_description as engine_meta_description,
                            engine.description as engine_description,
                            engine.status as engine_status,
                            engine.home_page_show as engine_home_page_show,
                            engine.deleted_at as engine_deleted_at,
                            engine.updated_at as engine_updated_at,
                            engine.created_at as engine_created_at,
                            engine.banner as engine_banner,
                            engine.sort_order as engine_sort_order
                        FROM categories vehicle_type
                        INNER JOIN categories brand ON vehicle_type.id = brand.parent_id
                        INNER JOIN categories model ON brand.id = model.parent_id
                        INNER JOIN categories generation ON model.id = generation.parent_id
                        INNER JOIN categories engine ON generation.id = engine.parent_id
                        WHERE engine.id=:engine_id');

            $prepare->execute([':engine_id' => $vehicle->getEngineId()]);

            if (!empty($categoryChainData = $prepare->fetchObject())) {
                $vehicle->type = (new self())
                    ->setId($categoryChainData->vehicle_type_id)
                    ->setType($categoryChainData->vehicle_type_type)
                    ->setParentId($categoryChainData->vehicle_type_parent_id)
                    ->setSlug($categoryChainData->vehicle_type_slug)
                    ->setName($categoryChainData->vehicle_type_name)
                    ->setIcon($categoryChainData->vehicle_type_icon)
                    ->setImage($categoryChainData->vehicle_type_image)
                    ->setBanner($categoryChainData->vehicle_type_banner)
                    ->setMetaTitle($categoryChainData->vehicle_type_meta_title)
                    ->setMetaKeywords($categoryChainData->vehicle_type_meta_keywords)
                    ->setDescription($categoryChainData->vehicle_type_meta_description)
                    ->setStatus($categoryChainData->vehicle_type_status)
                    ->setHomePageShow($categoryChainData->vehicle_type_home_page_show)
                    ->setSortOrder($categoryChainData->vehicle_type_sort_order)
                    ->setUpdatedAt($categoryChainData->vehicle_type_updated_at)
                    ->setDeletedAt($categoryChainData->vehicle_type_deleted_at)
                    ->setCreatedAt($categoryChainData->vehicle_type_created_at);

                $vehicle->brand = (new self())
                    ->setId($categoryChainData->brand_id)
                    ->setType($categoryChainData->brand_type)
                    ->setParentId($categoryChainData->brand_parent_id)
                    ->setSlug($categoryChainData->brand_slug)
                    ->setName($categoryChainData->brand_name)
                    ->setIcon($categoryChainData->brand_icon)
                    ->setImage($categoryChainData->brand_image)
                    ->setBanner($categoryChainData->brand_banner)
                    ->setMetaTitle($categoryChainData->brand_meta_title)
                    ->setMetaKeywords($categoryChainData->brand_meta_keywords)
                    ->setDescription($categoryChainData->brand_meta_description)
                    ->setStatus($categoryChainData->brand_status)
                    ->setHomePageShow($categoryChainData->brand_home_page_show)
                    ->setSortOrder($categoryChainData->brand_sort_order)
                    ->setUpdatedAt($categoryChainData->brand_updated_at)
                    ->setDeletedAt($categoryChainData->brand_deleted_at)
                    ->setCreatedAt($categoryChainData->brand_created_at);

                $vehicle->model = (new self())
                    ->setId($categoryChainData->model_id)
                    ->setType($categoryChainData->model_type)
                    ->setParentId($categoryChainData->model_parent_id)
                    ->setSlug($categoryChainData->model_slug)
                    ->setName($categoryChainData->model_name)
                    ->setIcon($categoryChainData->model_icon)
                    ->setImage($categoryChainData->model_image)
                    ->setBanner($categoryChainData->model_banner)
                    ->setMetaTitle($categoryChainData->model_meta_title)
                    ->setMetaKeywords($categoryChainData->model_meta_keywords)
                    ->setDescription($categoryChainData->model_meta_description)
                    ->setStatus($categoryChainData->model_status)
                    ->setHomePageShow($categoryChainData->model_home_page_show)
                    ->setSortOrder($categoryChainData->model_sort_order)
                    ->setUpdatedAt($categoryChainData->model_updated_at)
                    ->setDeletedAt($categoryChainData->model_deleted_at)
                    ->setCreatedAt($categoryChainData->model_created_at);

                $vehicle->generation = (new self())
                    ->setId($categoryChainData->generation_id)
                    ->setType($categoryChainData->generation_type)
                    ->setParentId($categoryChainData->generation_parent_id)
                    ->setSlug($categoryChainData->generation_slug)
                    ->setName($categoryChainData->generation_name)
                    ->setIcon($categoryChainData->generation_icon)
                    ->setImage($categoryChainData->generation_image)
                    ->setBanner($categoryChainData->generation_banner)
                    ->setMetaTitle($categoryChainData->generation_meta_title)
                    ->setMetaKeywords($categoryChainData->generation_meta_keywords)
                    ->setDescription($categoryChainData->generation_meta_description)
                    ->setStatus($categoryChainData->generation_status)
                    ->setHomePageShow($categoryChainData->generation_home_page_show)
                    ->setSortOrder($categoryChainData->generation_sort_order)
                    ->setUpdatedAt($categoryChainData->generation_updated_at)
                    ->setDeletedAt($categoryChainData->generation_deleted_at)
                    ->setCreatedAt($categoryChainData->generation_created_at);

                $vehicle->engine = (new self())
                    ->setId($categoryChainData->engine_id)
                    ->setType($categoryChainData->engine_type)
                    ->setParentId($categoryChainData->engine_parent_id)
                    ->setSlug($categoryChainData->engine_slug)
                    ->setName($categoryChainData->engine_name)
                    ->setIcon($categoryChainData->engine_icon)
                    ->setImage($categoryChainData->engine_image)
                    ->setBanner($categoryChainData->engine_banner)
                    ->setMetaTitle($categoryChainData->engine_meta_title)
                    ->setMetaKeywords($categoryChainData->engine_meta_keywords)
                    ->setDescription($categoryChainData->engine_meta_description)
                    ->setStatus($categoryChainData->engine_status)
                    ->setHomePageShow($categoryChainData->engine_home_page_show)
                    ->setSortOrder($categoryChainData->engine_sort_order)
                    ->setUpdatedAt($categoryChainData->engine_updated_at)
                    ->setDeletedAt($categoryChainData->engine_deleted_at)
                    ->setCreatedAt($categoryChainData->engine_created_at);

            }
        }
    }

    public function forSelect($query)
    {
        $result = [];

        if (!empty($query)) {
            $explode = explode(' ', $query);
            $explode = array_filter($explode);
            $where = [];
            foreach ($explode as $_ex) {
                $where[] = 'LOWER(CONCAT(type.name, " ", brand.name, " ",model.name, " ",generation.name, " ",engine.name)) LIKE LOWER("%' . $_ex . '%")';
            }

            if (!empty($where)) {
                $sql = "SELECT engine.id, CONCAT(type.name, ' >> ', brand.name, ' >> ', model.name, ' >> ', generation.name, ' >> ', engine.name) as text
                        FROM categories type
                        INNER JOIN categories brand ON type.id = brand.parent_id
                        INNER JOIN categories model ON brand.id = model.parent_id
                        INNER JOIN categories generation ON model.id = generation.parent_id
                        INNER JOIN categories engine ON generation.id = engine.parent_id WHERE " . implode(' AND ', $where);
                $prepare = $this->database->prepare($sql);
                $prepare->execute();
                $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
            }

        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param mixed $base
     * @return Category
     */
    public function setBase($base)
    {
        $this->base = $base;
        return $this;
    }


}
