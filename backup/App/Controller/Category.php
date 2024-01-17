<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Model\Category as CategoryModel;
use Symfony\Component\HttpFoundation\JsonResponse;


class Category extends CoreController
{
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
