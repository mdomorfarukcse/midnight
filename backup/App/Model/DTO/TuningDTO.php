<?php

namespace Pemm\Model\DTO;

class TuningDTO
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $isActive;

    /**
     * @var int
     */
    public $credit;

    /**
     * @var VehicleTuningDTO
     */
    public $vehicleTuningDTO;

    /**
     * @var AdditionalOptionDTO[]
     */
    public $additionalOptions = [];

    public function __construct()
    {
        $this->vehicleTuningDTO = new VehicleTuningDTO();
    }
}
