<?php

namespace Pemm\Controller;

use PDO;
use Pemm\Core\Controller as CoreController;
use Pemm\Model\AdditionalOption;
use Pemm\Model\ReadMethod;
use Pemm\Model\Tuning;
use Pemm\Model\TuningAdditionalOption;
use Pemm\Model\VehicleAdditionalOption;
use Pemm\Model\VehicleReadMethod;
use Pemm\Model\VehicleTuning;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Pemm\Model\Category;

class Mapping extends CoreController
{
    public $laydotDB = null;

    public function __construct($route_params)
    {
        parent::__construct($route_params);
        $this->laytonDB = $this->laytonDB();
    }

    public function index()
    {
        $options = [];
        ini_set("memory_limit", "3000M");
        ini_set('max_execution_time', 300);

        $error = [];

        foreach ($this->getVehicles() as $key => $_vehicle) {

                $slugger = new AsciiSlugger();

                $data = json_decode($_vehicle->data);

            try {

                $powerOemChart = !empty($data->data->chart->power->oem) ? $data->data->chart->power->oem : null;
                $torqueOemChart = !empty($data->data->chart->torque->oem) ? $data->data->chart->torque->oem : null;;

                if (!empty($powerOemChart) || !empty($torqueOemChart)) {
                    $vehicle = (new \Pemm\Model\Vehicle())->findOneBy(['filter' => ['base' => $data->data->id]]);
                    $vehicle->setOemPowerChart($powerOemChart);
                    $vehicle->setOemTorqueChart($torqueOemChart);
                    $vehicle->store();
                }
            } catch (\Exception $e) {
                print_r($e->getMessage());
                print_r($data);
                die;
            }


        }

        print_r($error);die;

    }

    public function laytonDB()
    {
        return new PDO(
            "mysql:host=localhost;dbname=layton",
            "ubuntu",
            "ubuntu",
            [
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            ]);
    }

    public function getVehicles()
    {
        return $this->laytonDB->query('SELECT * FROM vehicle')->fetchAll();
    }

    public function getEngines()
    {
        return $this->laytonDB->query('SELECT * FROM engine')->fetchAll();
    }

    public function getGenerations()
    {
        return $this->laytonDB->query('SELECT * FROM generation')->fetchAll();
    }

    public function getModels()
    {
        return $this->laytonDB->query('SELECT * FROM model')->fetchAll();
    }

    public function getMakeTypes()
    {
        return $this->laytonDB->query('SELECT * FROM make_type')->fetchAll();
    }

    public function getType($id)
    {
        return $this->laytonDB->query('SELECT * FROM type WHERE id = ' . $id)->fetch();
    }

    public function getMakeType($typeId, $makeId)
    {
        return $this->laytonDB->query('SELECT * FROM make_type WHERE type_id = ' . $typeId . ' AND make_id=' . $makeId)->fetch();
    }

    public function getMake($id)
    {
        return $this->laytonDB->query('SELECT * FROM make WHERE id = ' . $id)->fetch();
    }

    public function _make()
    {
        $slugger = new AsciiSlugger();

        foreach ($this->getMakeTypes() as $key => $_make) {
            try {
                $type = $this->getType($_make->type_id);
                $make = $this->getMake($_make->make_id);

                $category = new \Pemm\Model\Category();
                $category->setType('brand');
                $category->setParentId($type->base_id);
                $category->setName($make->name);
                $category->setSlug((string)$slugger->slug($make->name)->lower());
                $category->setSortOrder($key);
                $category->setStatus(1);
                $category->store();
                $this->laytonDB->query(
                    'UPDATE make_type SET base_id=' . $category->getId() . ' WHERE id = ' . $_make->id
                );
            } catch (\Exception $e) {
                print_r($e->getMessage());
                print_r($_make);
                die;
            }
        }
    }

    public function _model()
    {
        ini_set("memory_limit", "3000M");

        $slugger = new AsciiSlugger();

        $error = [];
        foreach ($this->getModels() as $key => $_model) {
            if ($_model->id > 0) {
                try {
                    $makeType = $this->getMakeType($_model->type_id, $_model->make_id);

                    $category = new \Pemm\Model\Category();
                    $category->setType('model');
                    $category->setParentId($makeType->base_id);
                    $category->setName($_model->name);
                    $category->setSlug((string)$slugger->slug(str_replace('+', '-plus-', $_model->name))->lower());
                    $category->setSortOrder($key);
                    $category->setStatus(1);
                    $category->store();
                    $this->laytonDB->query(
                        'UPDATE model SET category_id=' . $category->getId() . ' WHERE id = ' . $_model->id
                    );
                } catch (\Exception $e) {
                    $error[] = [
                        'message' => $e->getMessage(),
                        'data' => $_model
                    ];
                }
            }
        }

        print_r($error);
        die;
    }

    public function __engine()
    {
        ini_set("memory_limit", "3000M");

        $slugger = new AsciiSlugger();

        $error = [];
        foreach ($this->getEngines() as $key => $_engine) {
            try {
                $generation = $this->laytonDB->query(
                    'SELECT * FROM generation WHERE id=' . $_engine->generation_id
                )->fetch();

                $category = new \Pemm\Model\Category();
                $category->setType('engine');
                $category->setParentId($generation->category_id);
                $category->setName($_engine->name);
                $category->setSlug((string)$slugger->slug($_engine->name)->lower());
                $category->setSortOrder($key);
                $category->setStatus(1);
                $category->store();
                $this->laytonDB->query(
                    'UPDATE engine SET category_id=' . $category->getId() . ' WHERE id = ' . $_engine->id
                );
            } catch (\Exception $e) {
                $error[] = [
                    'message' => $e->getMessage(),
                    'data' => $_engine
                ];
            }
        }

        print_r($error);
        die;
    }

    public function _index()
    {
        $options = [];
        ini_set("memory_limit", "3000M");
        ini_set('max_execution_time', 300);

        $laytonTuning = [
            'stage1' => 2,
            'stage2' => 3,
        ];

        /*$tunings = (new Tuning())->findAll();
        $adds = (new AdditionalOption())->findAll();

        $errors = [];
        foreach ($tunings as $tuning) {
            foreach ($adds as $add) {
                try {
                    $tAdd = new TuningAdditionalOption();
                    $tAdd->setTuningId($tuning->getId());
                    $tAdd->setAdditionalOption($add);
                    $tAdd->setCredit(1);
                    $tAdd->setIsActive(1);
                    $tAdd->store();
                } catch (\Exception $e) {
                    $errors[] = [$e->getMessage(), $tuning->getId, $add->getId()];
                }


            }
        }
        print_r($errors);die;
        die;*/
        $slugger = new AsciiSlugger();

        $error = [];
        $tunning = [];
        $power = [];
        $torque = [];

        $stage1 = (new Tuning())->find(2);
        $stage2 = (new Tuning())->find(3);

        foreach ($this->getVehicles() as $key => $_vehicle) {
            try {
                $data = json_decode($_vehicle->data);

                $vehicle = (new \Pemm\Model\Vehicle())->findOneBy(['filter' => ['base' => $data->data->id]]);

                if (!empty($data->data->power->oem)) {
                    $powerOem = $data->data->power->oem;
                }
                if (!empty($data->data->power->stage1)) {
                    $powerStage1 = $data->data->power->stage1;
                }

                if (!empty($data->data->power->stage2)) {
                    $powerStage2 = $data->data->power->stage2;
                }

                if (!empty($data->data->chart->power->stage1)) {
                    $powerStage1Chart = $data->data->chart->power->stage1;
                }

                if (!empty($data->data->chart->torque->stage1)) {
                    $torqueStage1Chart = $data->data->chart->torque->stage1;
                }

                if (!empty($data->data->torque->oem)) {
                    $torqueOem = $data->data->torque->oem;
                }
                if (!empty($data->data->torque->stage1)) {
                    $torqueStage1 = $data->data->torque->stage1;
                }
                if (!empty($data->data->torque->stage2)) {
                    $torqueStage2 = $data->data->torque->stage2;
                }

                if (!empty($data->data->chart->power->stage2)) {
                    $powerStage2Chart = $data->data->chart->power->stage2;
                }

                if (!empty($data->data->chart->torque->stage2)) {
                    $torqueStage2Chart = $data->data->chart->torque->stage2;
                }

                $vehicleTuning1 = new VehicleTuning();
                $vehicleTuning1->setVehicleId($vehicle->getId());
                $insert1 = false;

                if (!empty($powerStage1)) {
                    $insert1 = true;
                    $vehicleTuning1->setTuning($stage1);
                    $vehicleTuning1->setDifferencePower($powerStage1 - intval($powerOem));
                    $vehicleTuning1->setMaxPower($powerStage1);
                    $vehicleTuning1->setIsActive(1);
                    $vehicleTuning1->setCredit(0);
                    if (!empty($powerStage1Chart)) {
                        $vehicleTuning1->setPowerChart($powerStage1Chart);
                    }
                }

                if (!empty($torqueStage1)) {
                    $insert1 = true;
                    $vehicleTuning1->setTuning($stage1);
                    $vehicleTuning1->setDifferenceTorque($torqueStage1 - intval($torqueOem));
                    $vehicleTuning1->setMaxTorque($torqueStage1);
                    $vehicleTuning1->setIsActive(1);
                    $vehicleTuning1->setCredit(0);
                    if (!empty($torqueStage1Chart)) {
                        $vehicleTuning1->setTorqueChart($torqueStage1Chart);
                    }
                }

                if ($insert1) {
                    $vehicleTuning1->store();
                }

                $vehicleTuning2 = new VehicleTuning();
                $vehicleTuning2->setVehicleId($vehicle->getId());
                $insert2 = false;

                if (!empty($powerStage2)) {
                    $insert2 = true;
                    $vehicleTuning2->setTuning($stage2);
                    $vehicleTuning2->setDifferencePower($powerStage2 - intval($powerOem));
                    $vehicleTuning2->setMaxPower($powerStage2);
                    $vehicleTuning2->setIsActive(1);
                    $vehicleTuning2->setCredit(0);
                    if (!empty($powerStage2Chart)) {
                        $vehicleTuning2->setPowerChart($powerStage2Chart);
                    }
                }

                if (!empty($torqueStage2)) {
                    $insert2 = true;
                    $vehicleTuning2->setTuning($stage2);
                    $vehicleTuning2->setDifferenceTorque($torqueStage2 - intval($torqueOem));
                    $vehicleTuning2->setMaxTorque($torqueStage2);
                    $vehicleTuning2->setIsActive(1);
                    $vehicleTuning2->setCredit(1);
                    if (!empty($torqueStage2Chart)) {
                        $vehicleTuning2->setTorqueChart($torqueStage2Chart);
                    }
                }


                if ($insert2) {
                    $vehicleTuning2->store();
                }
                /*if (!empty($data->data->chart->rpm)) {
                    $vehicle = (new \Pemm\Model\Vehicle())->findOneBy(['filter' => ['base' => $data->data->id]]);
                    $vehicle->setRpm($data->data->chart->rpm);
                    $vehicle->store();
                } else {
                    throw new \Exception('');
                }*/
            } catch (\Exception $e) {
                $error[$data->data->id] = $e->getMessage();
            }
        }

        print_r($error);
        die;
    }

    public function __index()
    {
        $options = [];
        ini_set("memory_limit", "3000M");
        ini_set('max_execution_time', 300);

        $error = [];

        foreach ($this->getVehicles() as $key => $_vehicle) {
            try {
                $slugger = new AsciiSlugger();

                $data = json_decode($_vehicle->data);
                $vehicle = (new \Pemm\Model\Vehicle())->findOneBy(['filter' => ['base' => $data->data->id]]);

                $typeId = @$data->data->selected->type->id;
                $typeName = @$data->data->selected->type->name;
                $typeSlug = $slugger->slug($typeName)->lower();

                $makeId = @$data->data->selected->make->id;
                $makeName = @$data->data->selected->make->name;
                $makeSlug = $slugger->slug($makeName)->lower();

                $modelId = @$data->data->selected->model->id;
                $modelName = @$data->data->selected->model->name;
                $modelSlug = $slugger->slug($modelName)->lower();

                $generationId = @$data->data->selected->generation->id;
                $generationName = @$data->data->selected->generation->name;
                $generationSlug = $slugger->slug($generationName)->lower();

                $engineId = @$data->data->selected->engine->id;
                $engineName = @$data->data->selected->engine->name;
                $engineSlug = $slugger->slug($engineName)->lower();

                $type = (new Category())->findOneBy(['filter' => ['type' => 'main', 'base' => $typeId]]);

                if (empty($type)) {
                    $category = new Category();
                    $category->setType('main');
                    $category->setBase($typeId);
                    $category->setName($typeName);
                    $category->setSlug($typeSlug);
                    $category->setStatus(1);
                    $category->setSortOrder(1);
                    $category->setHomePageShow(0);
                    $category->setParentId(0);
                    $category->store();
                }

                $make = (new Category())->findOneBy(
                    ['filter' => ['type' => 'brand', 'parent_id' => $type->getId(), 'base' => $makeId]]
                );

                if (empty($make)) {
                    $category = new Category();
                    $category->setType('brand');
                    $category->setBase($makeId);
                    $category->setName($makeName);
                    $category->setSlug($makeSlug);
                    $category->setStatus(1);
                    $category->setSortOrder(1);
                    $category->setHomePageShow(0);
                    $category->setParentId($type->getId());
                    $category->store();
                }

                $model = (new Category())->findOneBy(
                    ['filter' => ['type' => 'model', 'parent_id' => $make->getId(), 'base' => $modelId]]
                );

                if (empty($model)) {
                    $category = new Category();
                    $category->setType('model');
                    $category->setBase($modelId);
                    $category->setName($modelName);
                    $category->setSlug($modelSlug);
                    $category->setStatus(1);
                    $category->setSortOrder(1);
                    $category->setHomePageShow(0);
                    $category->setParentId($make->getId());
                    $category->store();
                }

                $generation = (new Category())->findOneBy(
                    ['filter' => ['type' => 'generation', 'parent_id' => $model->getId(), 'slug' => $generationSlug]]
                );

                if (empty($generation)) {
                    $category = new Category();
                    $category->setType('generation');
                    $category->setBase($generationId);
                    $category->setName($generationName);
                    $category->setSlug($generationSlug);
                    $category->setStatus(1);
                    $category->setSortOrder(1);
                    $category->setHomePageShow(0);
                    $category->setParentId($model->getId());
                    $category->store();
                }

                $engine = (new Category())->findOneBy(
                    ['filter' => ['type' => 'engine', 'parent_id' => $generation->getId(), 'base' => $engineId]]
                );

                if (empty($engine)) {
                    $category = new Category();
                    $category->setType('engine');
                    $category->setBase($engineId);
                    $category->setName($engineName);
                    $category->setSlug($engineSlug);
                    $category->setStatus(1);
                    $category->setSortOrder(1);
                    $category->setHomePageShow(0);
                    $category->setParentId($generation->getId());
                    $category->store();
                }

                $vehicle->setEngineId($engine->getId());
                $vehicle->store();
            } catch (\Exception $e) {
                $error[$data->data->id] = $e->getMessage();
            }
        }

        print_r($error);
        die;
    }
}
