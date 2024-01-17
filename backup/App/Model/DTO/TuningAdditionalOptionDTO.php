<?php

namespace Pemm\Model\DTO;

class TuningAdditionalOptionDTO
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $credit;

    /**
     * @var int
     */
    public $isActive;

    /**
     * @var VehicleTuningAdditionalOptionDTO
     */
    public $vehicleTuningAdditionalOptionDTO;

    public function __construct()
    {
        $this->vehicleTuningAdditionalOptionDTO = new VehicleTuningAdditionalOptionDTO();
    }
}
