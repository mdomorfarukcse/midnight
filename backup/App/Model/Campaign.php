<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class Campaign extends BaseModel
{
    private $id;
    private $campaignType;
    private $relation;
    private $relationId;
    private $isActive;
    private $startDate;
    private $endDate;
    private $createddAt;
    private $variables;
    private $rules;

    private $filterParams;
    private $list;

    public function filter()
    {

        $where = $executeData = [];

        if (!empty($this->filterParams['filter']['id'])) {
            if (is_array($this->filterParams['filter']['id'])) {
                $where[] = 'campaigns.id IN (' . implode(',', $this->filterParams['filter']['id']) . ')';
            } else {
                $where[] = 'campaigns.id=:id';
                $executeData[':id'] = $this->filterParams['filter']['id'];
            }
        }

        if (!empty($this->filterParams['filter']['campaign_type_id'])) {
            if (is_array($this->filterParams['filter']['campaign_type_id'])) {
                $where[] = 'campaigns.campaign_type_id IN (' . implode(',', $this->filterParams['filter']['campaign_type_id']) . ')';
            } else {
                $where[] = 'campaigns.campaign_type_id=:campaign_type_id';
                $executeData[':campaign_type_id'] = $this->filterParams['filter']['campaign_type_id'];
            }
        }

        if (!empty($this->filterParams['filter']['relation'])) {
            if (is_array($this->filterParams['filter']['relation'])) {
                $where[] = 'campaigns.relation IN (' . implode(',', $this->filterParams['filter']['relation']) . ')';
            } else {
                $where[] = 'campaigns.relation=:relation';
                $executeData[':relation'] = $this->filterParams['filter']['relation'];
            }
        }

        if (!empty($this->filterParams['filter']['relation_id'])) {
            if (is_array($this->filterParams['filter']['relation_id'])) {
                $where[] = 'campaigns.relation_id IN (' . implode(',', $this->filterParams['filter']['relation_id']) . ')';
            } else {
                $where[] = 'campaigns.relation_id=:relation_id';
                $executeData[':relation_id'] = $this->filterParams['filter']['relation_id'];
            }
        }

        if (!empty($this->filterParams['filter']['is_active'])) {
            $where[] = 'campaigns.is_active=:is_active';
            $executeData[':is_active'] = $this->filterParams['filter']['is_active'];
        }

        $sql = 'SELECT campaigns.*, campaign_types.name as campaign_type_name, campaign_types.slogan as campaign_type_slogan, 
        campaign_types.description as campaign_type_description,  campaign_types.is_active as campaign_type_is_active, 
        campaign_types.mandatory_variables as campaign_type_mandatory_variables FROM campaigns
                INNER JOIN campaign_types ON campaign_types.id=campaigns.campaign_type_id';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        if (!empty($this->filterParams['pagination'])) {

            try {

                $prepare = $this->database->prepare($sql);
                $prepare->execute($executeData);

                $this->filterParams['pagination']['total_count'] = $prepare->rowCount();

            } catch (\Exception $e) {}

        }

        if (!empty($this->filterParams['order'])) {
            switch ($this->filterParams['order']['field']) {
                case 'id':
                    $sql .= ' ORDER BY campaigns.id ';
                    break;
                case 'relation':
                    $sql .= ' ORDER BY campaigns.relation ';
                    break;
                case 'relation_id':
                    $sql .= ' ORDER BY campaigns.relation_id ';
                    break;
                case 'is_active':
                    $sql .= ' ORDER BY campaigns.is_active ';
                    break;
                default:
                    $sql .= ' ORDER BY campaigns.id ';
                    $this->filterParams['order']['sort'] = 'DESC';
                    break;
            }
            $sql .= $this->filterParams['order']['sort'];
        }

        if (!empty($this->filterParams['pagination'])) {

            if (!empty($this->filterParams['pagination']['limit'])) {
                $sql .= ' LIMIT ' . $this->filterParams['pagination']['limit'];
                $this->filterParams['pagination']['total_page'] = ceil($this->filterParams['pagination']['total_count'] / $this->filterParams['pagination']['limit']);

                if (!empty($this->filterParams['pagination']['page'])) {
                    $sql .= ' OFFSET ' . (($this->filterParams['pagination']['page'] - 1) * $this->filterParams['pagination']['limit']);
                }
            }

        }

        try {

            $prepare = $this->database->prepare($sql);
            $prepare->execute($executeData);
            $campaignList = $prepare->fetchAll(PDO::FETCH_OBJ);

            if (!empty($campaignList)) {
                require_once 'CampaignType.php';
                foreach ($campaignList as $key => $campaign) {
                    $this->list[$key] = (new self())->setId($campaign->id)
                                                    ->setCampaignType(
                                                        (new CampaignType())
                                                            ->setId($campaign->campaign_type_id)
                                                            ->setIsActive($campaign->campaign_type_is_active)
                                                            ->setName($campaign->campaign_type_name)
                                                            ->setDescription($campaign->campaign_type_description)
                                                            ->setMandatoryVariables($campaign->campaign_type_mandatory_variables)
                                                            ->setSlogan($campaign->campaign_type_slogan)
                                                    )
                                                    ->setRelation($campaign->relation)
                                                    ->setRelationId($campaign->relation_id)
                                                    ->setIsActive($campaign->is_active)
                                                    ->setStartDate($campaign->start_date)
                                                    ->setEndDate($campaign->end_date)
                                                    ->setCreateddAt($campaign->created_at)
                                                    ->setVariables($campaign->variables)
                                                    ->setRules($campaign->rules);
                }
            }
        } catch (\Exception $e) {print_r($e);die;}

        return $this->list;
    }

    /**
     * @params $params array
     * @return integer
     */
    public function add($params)
    {
        $data = $fields = [];
        $id = 0;

        foreach ($params as $key => $value) {

            $fields[] = $key;

            if ($key == 'rules' || $key == 'variables') {
                $value = serialize($value);
            }

            $data[':' . $key] = $value;
        }

        $database = Database::getInstance();

        $insert = $database->prepare('INSERT INTO campaigns (' . implode(', ', $fields) . ') VALUES (:' . implode(', :', $fields) . ')');
        $saved = $insert->execute($data);

        if ($saved) {
            $id = $database->lastInsertId();
        }

        return $id;
    }

    /**
     * @param $id integer
     * @param $updateParameters array
     * @return boolean
     */
    public function update($id, $updateParameters) {

        $database = Database::getInstance();

        if (!empty($updateParameters)) {
            $data = $field = [];
            foreach ($updateParameters as $key => $params) {
                $field[] = $key . '=:' . $key;
                $data[':'. $key] = $params;
            }

            $data[':id'] = $id;

            $update = $database->prepare('UPDATE campaigns SET ' . implode(', ', $field) . ' WHERE id=:id');
            $updated = $update->execute($data);

            if (!$updated) {
                throw new \Exception('Güncelleme başarısız');
            }
        }
        return true;
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
     * @return Campaign
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCampaignType()
    {
        return $this->campaignType;
    }

    /**
     * @param mixed $campaignType
     * @return Campaign
     */
    public function setCampaignType($campaignType)
    {
        $this->campaignType = $campaignType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * @param mixed $relation
     * @return Campaign
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelationId()
    {
        return $this->relationId;
    }

    /**
     * @param mixed $relationId
     * @return Campaign
     */
    public function setRelationId($relationId)
    {
        $this->relationId = $relationId;
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
     * @return Campaign
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     * @return Campaign
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     * @return Campaign
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreateddAt()
    {
        return $this->createddAt;
    }

    /**
     * @param mixed $createddAt
     * @return Campaign
     */
    public function setCreateddAt($createddAt)
    {
        $this->createddAt = $createddAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @param mixed $variables
     * @return Campaign
     */
    public function setVariables($variables)
    {
        if (!empty($variables))
            $variables = unserialize($variables);

        $this->variables = $variables;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param mixed $rules
     * @return Campaign
     */
    public function setRules($rules)
    {
        if (!empty($rules))
            $rules = unserialize($rules);

        $this->rules = $rules;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilterParams()
    {
        return $this->filterParams;
    }

    /**
     * @param mixed $filterParams
     * @return Campaign
     */
    public function setFilterParams($filterParams)
    {
        $this->filterParams = $filterParams;
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
     * @return Campaign
     */
    public function setDatabase(?Database $database): Campaign
    {
        $this->database = $database;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param mixed $list
     * @return Campaign
     */
    public function setList($list)
    {
        $this->list = $list;
        return $this;
    }

    public function isAuthorized()
    {
        global $container;

        $authorized = false;
        $customer = $container->get('customer');

        switch ($this->rules['authorized']['type']) {
            case 'all':
                $authorized = true;
                break;
            case 'only-user':
                if (!empty($customer->getId()))
                    $authorized = true;
                break;
            case 'selected-user':
                if (!empty($customer->getId()) && $customer->getId() == $this->rules['authorized']['user_id'])
                $authorized = true;
                break;
        }
        return $authorized;
    }

    public function checkInDateRange()
    {
        $now = new \DateTime('Europe/Istanbul');
        $startDate = new \DateTime($this->startDate ?? '1970-01-01 00:00:00');
        $endDate = new \DateTime($this->endDate ?? '2050-01-01 00:00:00');

        return $now > $startDate && $now < $endDate;
    }
}
