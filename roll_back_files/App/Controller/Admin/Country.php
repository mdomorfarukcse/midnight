<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\City;
use Pemm\Model\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Country as CountryModel;

class Country extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('country-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Countries - ' . $this->setting->getSiteName())
                ->setMetaDescription('Countries - ' . $this->setting->getDescription())
        );

        View::render('admin','country-list', []);
    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $countryList = [];

        $countryModel = new CountryModel();
        $countries = $countryModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($countries)) {
            /* @var CountryModel $country */
            foreach ($countries as $key => $country) {
                $countryList[$key] = [
                    'id' => $country->getId(),
                    'sortname' => $country->getSortName(),
                    'name' => $country->getName(),
                    'phonecode' => $country->getPhoneCode(),
                    'is_active' => $country->getIsActive()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $countryModel->queryTotalCount,
            'recordsFiltered' => $countryModel->queryTotalCount,
            'data' => $countryList
        ]))->send();
    }


    public function ajaxCityListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $cityList = [];

        $cityModel = new City();
        $cities = $cityModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($cities)) {
            /* @var City $city */
            foreach ($cities as $key => $city) {
                $city->getCountry();
                $cityList[$key] = [
                    'id' => $city->getId(),
                    'name' => $city->getName(),
                    'country_id' => $city->getCountryId(),
                    'country_name' => $city->country->getName(),
                    'is_active' => $city->getIsActive()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $cityModel->queryTotalCount,
            'recordsFiltered' => $cityModel->queryTotalCount,
            'data' => $cityList
        ]))->send();
    }

    public function ajaxListForSelect()
    {
        $query = $this->request->query->get('q');

        $countryModel = new CountryModel();
        $countries = $countryModel->forSelect2($query);

        return (new JsonResponse($countries))->send();

    }
}
