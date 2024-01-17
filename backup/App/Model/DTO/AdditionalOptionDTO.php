<?php

namespace Pemm\Model\DTO;

class AdditionalOptionDTO
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
    public $code;

    /**
     * @var string
     */
    public $image;

    /**
     * @var int
     */
    public $isActive;

    /**
     * @var TuningAdditionalOptionDTO
     */
    public $tuningAdditionalOptionDTO;

    public function __construct()
    {
        $this->tuningAdditionalOptionDTO = new TuningAdditionalOptionDTO();
    }
}
