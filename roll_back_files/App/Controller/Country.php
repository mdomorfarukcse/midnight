<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Pemm\Model\Customer;
use Pemm\Model\Country as CountryModel;
use Symfony\Component\HttpFoundation\JsonResponse;

class Country extends CoreController
{
    public function ajaxCountriesGetForSelect()
    {
        return (new CountryModel())->getForSelectBox();
    }

    public function ajaxCityList()
    {

        try {

            $countryId = $this->request->query->getInt('country');
            $country = (new CountryModel())->find($countryId);
            $cities = $country->getCitiesForSelect();

        } catch (\Exception $e) {
            return (new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]))->send();
        }

        return (new JsonResponse([
            'success' => true,
            'cities' => $cities,
        ]))->send();

    }

}
