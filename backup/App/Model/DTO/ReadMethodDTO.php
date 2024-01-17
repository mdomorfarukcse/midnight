<?php

namespace Pemm\Model\DTO;

class ReadMethodDTO
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $surname;

    /**
     * @var string
     */
    public $image;

    /**
     * @var int
     */
    public $isActive;

    /**
     * @var VehicleReadMethodDTO
     */
    public $vehicleReadMethod;

    public function __construct()
    {
        $this->vehicleReadMethod = new VehicleReadMethodDTO();
    }
}
