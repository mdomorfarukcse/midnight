<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Model\Country;
use Pemm\Model\State;
use Pemm\Model\City;

class Address extends CoreController
{
    public function ajaxStateGetForSelect()
    {
        return (new State())->getByCountryIdForSelectBox($this->route_params['country_id']);
    }

    public function ajaxCityGetForSelect()
    {
        return (new City())->getByStateIdForSelectBox($this->route_params['state_id']);
    }

}
