<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Category as CategoryModel;
use Symfony\Component\HttpFoundation\JsonResponse;

class Category extends CoreController
{
    public function ajaxListForSelect()
    {
        $query = $this->request->query->get('q');

        $categoryModel = new CategoryModel;
        $categories = $categoryModel->forSelect($query);

        return (new JsonResponse($categories))->send();
    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $categoryList = [];

        $categoryModel = new CategoryModel();
        $categories = $categoryModel->findBy([
            'filter' => ['parent_id' => 0],
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($categories)) {
            /* @var CategoryModel $country */
            foreach ($categories as $key => $category) {
                $categoryList[$key] = [
                    'category_icon' => $category->getIcon(),
                    'id' => $category->getId(),
                    'type_name' => $category->getName(),
                    'name' => $category->getName(),
                    'type_id' => $category->getType(),
                    'is_active' => $category->getStatus()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $categoryModel->queryTotalCount,
            'recordsFiltered' => $categoryModel->queryTotalCount,
            'data' => $categoryList
        ]))->send();
    }

    public function ajaxListModelsForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $categoryList = [];

        $categoryModel = new CategoryModel();
        $categories = $categoryModel->findBy([
            'filter' => ['type' => 'model'],
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($categories)) {
            /* @var CategoryModel $country */
            foreach ($categories as $key => $category) {
                $categoryList[$key] = [
                    'category' => $category->getParent()->getParent()->getIcon(),
                    'brand'    => $category->getParent()->getImage(),
                    'brand_name'   => $category->getParent()->getName(),
                    'id'        => $category->getId(),
                    'name'      => $category->getName(),
                    'is_active' => $category->getStatus()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $categoryModel->queryTotalCount,
            'recordsFiltered' => $categoryModel->queryTotalCount,
            'data' => $categoryList
        ]))->send();
    }

    public function ajaxListYearsForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $categoryList = [];

        $categoryModel = new CategoryModel();
        $categories = $categoryModel->findBy([
            'filter' => ['type' => 'generation'],
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($categories)) {
            /* @var CategoryModel $country */
            foreach ($categories as $key => $category) {
                $categoryList[$key] = [
                    'category' => $category->getParent()->getParent()->getParent()->getIcon(),
                    'brand'    => $category->getParent()->getParent()->getImage(),
                    'model_name'   => $category->getParent()->getName(),
                    'id'        => $category->getId(),
                    'name'      => $category->getName(),
                    'is_active' => $category->getStatus()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $categoryModel->queryTotalCount,
            'recordsFiltered' => $categoryModel->queryTotalCount,
            'data' => $categoryList
        ]))->send();
    }

    public function ajaxListEngineForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $categoryList = [];

        $categoryModel = new CategoryModel();
        $categories = $categoryModel->findBy([
            'filter' => ['type' => 'engine'],
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($categories)) {
            /* @var CategoryModel $country */
            foreach ($categories as $key => $category) {
                $categoryList[$key] = [
                    'category' => $category->getParent()->getParent()->getParent()->getParent()->getIcon(),
                    'brand'    => $category->getParent()->getParent()->getParent()->getImage(),
                    'model_name'   => $category->getParent()->getParent()->getName(),
                    'year'   => $category->getParent()->getName(),
                    'id'        => $category->getId(),
                    'name'      => $category->getName(),
                    'is_active' => $category->getStatus()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $categoryModel->queryTotalCount,
            'recordsFiltered' => $categoryModel->queryTotalCount,
            'data' => $categoryList
        ]))->send();
    }

    public function ajaxGetSubCategories()
    {
        try {

            if (!isset($this->route_params['id']))
                throw new \Exception('Eksik parametre');

            $result = [];

            $categoryModel = new CategoryModel();
            $subCategories = $categoryModel->findBy(['filter' => ['parent_id' => intval($this->route_params['id']), 'is_active' => 1]]);
            if (!empty($subCategories)) {
                /* @var CategoryModel $subCategory */
                foreach ($subCategories as $key => $subCategory) {
                    $result[$key] = [
                        'id' => $subCategory->getId(),
                        'name' => $subCategory->getName()
                    ];
                }
            }

        } catch (\Exception $e) {
            return (new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]))->send();
        }

        return (new JsonResponse([
            'success' => true,
            'result' => $result
        ]))->send();

    }
}
