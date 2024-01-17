<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class CampaignType extends BaseModel
{
    private $id;
    private $name;
    private $slogan;
    private $description;
    private $createdAt;
    private $isActive;
    private $mandatoryVariables;

    private $list;

    public function getList()
    {
        if (is_null($this->list)) {
            $campaignTypes = $this->database->query('SELECT * FROM campaign_types')->fetchAll(PDO::FETCH_OBJ);
            if (!empty($campaignTypes)) {
                foreach ($campaignTypes as $key => $campaignType) {
                    $this->list[$campaignType->id] =  (new CampaignType())
                                            ->setId($campaignType->id)
                                            ->setIsActive($campaignType->is_active)
                                            ->setName($campaignType->name)
                                            ->setDescription($campaignType->description)
                                            ->setMandatoryVariables($campaignType->mandatory_variables)
                                            ->setSlogan($campaignType->slogan)
                                            ->setCreatedAt($campaignType->created_at);
                }
            }
        }

        return $this->list;

    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return CampaignType
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return CampaignType
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlogan()
    {
        return $this->slogan;
    }

    /**
     * @param mixed $slogan
     * @return CampaignType
     */
    public function setSlogan($slogan)
    {
        $this->slogan = $slogan;
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
     * @return CampaignType
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * @return CampaignType
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     * @return CampaignType
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMandatoryVariables()
    {
        return $this->mandatoryVariables;
    }

    /**
     * @param mixed $mandatoryVariables
     * @return CampaignType
     */
    public function setMandatoryVariables($mandatoryVariables)
    {
        if (!empty($mandatoryVariables))
            $mandatoryVariables = unserialize($mandatoryVariables);

        $this->mandatoryVariables = $mandatoryVariables;
        return $this;
    }

    /**
     * @return Database|null
     */
    public function getDatabase(): ?Database
    {
        return $this->database;
    }

    /**
     * @param Database|null $database
     * @return CampaignType
     */
    public function setDatabase(?Database $database): CampaignType
    {
        $this->database = $database;
        return $this;
    }


}
