<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Pemm\Model\DTO\AdditionalOptionDTO;
use Pemm\Model\DTO\ReadMethodDTO;
use Pemm\Model\DTO\TuningDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Vehicle;
use Pemm\Model\Setting;

global $container;

$setting = (new Setting())->find(1);

/* @var Request $request */
$request = $container->get('request');

/* @var Session $session */
$session = $container->get('session');

$language = $container->get('language');

$vehicle = new Vehicle();

if (!empty($vehicleId = intval($container->get('detailId')))) {
    $vehicle = (new Vehicle())->find($vehicleId);
}

$new = empty($vehicle->getId());

if ($request->isMethod('post')) {

    try {

        $vehicle
            ->setEngineId($request->request->get('engine_id'))
            ->setFullName($request->request->get('full_name'))
            ->setStandardPower($request->request->get('standard_power'))
            ->setStandardTorque($request->request->get('standard_torque'))
            ->setFuel($request->request->get('fuel'))
            ->setCylinder($request->request->getInt('cylinder'))
            ->setCompression($request->request->get('compression'))
            ->setBore($request->request->get('bore'))
            ->setEngineNumber($request->request->get('engine_number'))
            ->setEcu($request->request->get('ecu'))
            ->setIsActive(!empty($request->request->get('is_active')) ? 1 : 0)
            ->setRpm($request->request->get('rpm'))
            ->setOemPowerChart($request->request->get('oem_power_chart'))
            ->setOemTorqueChart($request->request->get('oem_torque_chart'));

        $vehicle->store();


        if (!empty($request->request->get('tunings'))) {
            $vehicle->saveTunings($request->request->get('tunings'));
        }

        if (!empty($request->request->get('read_methods'))) {
            $vehicle->saveReadMethods($request->request->get('read_methods'));
        }

        $session->getFlashBag()->add('success', 'Success');

        header('location: /admin/vehicle/detail/' . $vehicle->getId());

    } catch (\Exception $exception) {
        $session->getFlashBag()->add('danger', $exception->getMessage());
    }
} else {

    $vehicle->tmpDecorator();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $language::translate('Vehicle') ?> - <?= SITE_NAME ?> </title>
    <?php include("css.php")?>
    <link href="<?= SITE_URL ?>\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\bootstrap-toggle\bootstrap-toggle.min.css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\table\datatable\datatables.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\assets\css\forms\theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\table\datatable\dt-global_style.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\select2\select2.min.css">
    <link rel="stylesheet" href="\assets/css/themify-icons.css">
    <link rel="stylesheet" href="\assets/css/ie7/ie7.css">
</head>
<body>
<?php include("header.php")?>
<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">
    <?php include("sidebar.php")?>
    <div id="content" class="main-content">
        <div class="layout-px-spacing">
            <div class="row layout-top-spacing">
                <form action="" method="post" enctype="multipart/form-data" style="width: 100%">
                <div class="col-xl-12 col-lg-12 col-sm-12 mb-3">
                    <div class="card">
                        <div class="card-body">

                          <?php
                          foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                              <div class="alert alert-<?= $type ?>">
                                  <?php foreach ($messages as $message) { echo $message;} ?>
                              </div>
                          <?php }
                          ?>
                          <div class="form-row">
                              <div class="form-group col-xl-12 col-lg-12 col-sm-12">
                                  <label for="category-select"> <?= $language::translate('Search') ?></label>
                                  <div class="input-group">
                                      <select id="category-select" name="engine_id" class="form-control select2-allow-clear category-select" data-type="type" required>
                                          <?php if (!$new) {?>
                                              <option value="<?= $vehicle->getEngineId() ?>"><?= $vehicle->getCategoryChainText() ?></option>
                                          <?php } ?>
                                      </select>
                                  </div>
                              </div>
                          </div>
                          <hr>



                            <h5 class="card-title"><?= $language::translate('Vehicle') ?></h5>

                                <div class="form-row">


                                    <div class="form-group col-xl-4 col-lg-3 col-sm-12">
                                        <label for="standard_power"> <?= $language::translate('Standart Power') ?></label>
                                        <input type="text" name="standard_power" class="form-control" id="standard_power" value="<?= $vehicle->getStandardPower() ?>">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-3 col-sm-12">
                                        <label for="standard_torque"> <?= $language::translate('Standart Torque') ?></label>
                                        <input type="text" name="standard_torque" class="form-control" id="standard_torque" value="<?= $vehicle->getStandardTorque() ?>">
                                    </div>

                                    <div class="form-group col-xl-4 col-lg-6 col-sm-12">
                                        <label for="fuel"> <?= $language::translate('Fuel') ?></label>
                                        <input type="text" name="fuel" class="form-control" id="fuel" value="<?= $vehicle->getFuel() ?>">
                                    </div>
                                </div>

                                <div class="form-row"> 
                                    <div class="form-group col-xl-6 col-lg-6 col-sm-12">
                                        <label for="ecu"> <?= $language::translate('Ecu') ?></label>
                                        <input type="text" name="ecu" class="form-control" id="ecu" value="<?= $vehicle->getEcu() ?>">
                                    </div>
                                <div class="form-group col-xl-3 col-lg-3 col-sm-12">
                                    <label for="rpm"> <?= $language::translate('RPM') ?></label>
                                    <input type="text" name="rpm" class="form-control" id="rpm"
                                           value="<?= $vehicle->getRpm() ?>">
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-sm-12">
                                    <label for="oem_power_chart"> <?= $language::translate('Oem Power Chart') ?></label>
                                    <input type="text" name="oem_power_chart" class="form-control" id="oem_power_chart"
                                           value="<?= $vehicle->getOemPowerChart() ?>">
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-sm-12">
                                    <label for="oem_torque_chart"> <?= $language::translate('Oem Torque Chart') ?></label>
                                    <input type="text" name="oem_torque_chart" class="form-control" id="oem_torque_chart"
                                           value="<?= $vehicle->getOemTorqueChart() ?>">
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-sm-12">
                                  <label for="ecu"> <?= $language::translate('Status') ?></label>
                                  <div class="form-check">
                                      <label class="checkbox-inline">
                                          <input type="checkbox" value="1" name="is_active" <?= !$vehicle->getIsActive() ?: 'checked'; ?> data-toggle="toggle">
                                      </label>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               <div class="col-xl-12 col-lg-12 col-sm-12 mb-3">
                    <?php
                        /** @var TuningDTO $tuningDTO */
                        /** @var AdditionalOptionDTO $additionalOptionDTO */
                        foreach ($vehicle->tmpDTO['tunings'] as $key => $tuningDTO) {
                            foreach ($tuningDTO->additionalOptions as $aKey => $additionalOptionDTO) {?>
                                <input type="hidden" name="tunings[<?= $key ?>][additional_options][<?= $aKey ?>][id]" value="<?= $additionalOptionDTO->id ?>">
                                <input type="hidden" name="tunings[<?= $key ?>][additional_options][<?= $aKey ?>][is_active]" value="<?= $additionalOptionDTO->isActive ?>">
                                <input type="hidden" name="tunings[<?= $key ?>][additional_options][<?= $aKey ?>][vehicle_tuning_additional_option][id]" value="<?= $additionalOptionDTO->tuningAdditionalOptionDTO->vehicleTuningAdditionalOptionDTO->id ?>">
                                <input type="hidden" name="tunings[<?= $key ?>][additional_options][<?= $aKey ?>][vehicle_tuning_additional_option][is_active]" value="<?= $additionalOptionDTO->tuningAdditionalOptionDTO->vehicleTuningAdditionalOptionDTO->isActive ?>">
                                <input type="hidden" name="tunings[<?= $key ?>][additional_options][<?= $aKey ?>][tuning_additional_option][id]" value="<?= $additionalOptionDTO->tuningAdditionalOptionDTO->id ?>">
                                <input type="hidden" name="tunings[<?= $key ?>][additional_options][<?= $aKey ?>][tuning_additional_option][is_active]" value="<?= $additionalOptionDTO->tuningAdditionalOptionDTO->isActive ?>">
                                <input type="hidden" name="tunings[<?= $key ?>][additional_options][<?= $aKey ?>][tuning_additional_option][credit]" value="<?= $additionalOptionDTO->tuningAdditionalOptionDTO->credit ?>">
                            <?php }
                            ?>
                            <input type="hidden" name="tunings[<?= $key ?>][id]" value="<?= $tuningDTO->id ?>">
                            <input type="hidden" name="tunings[<?= $key ?>][vehicle_tuning][id]" value="<?= $tuningDTO->vehicleTuningDTO->id ?>">
                            <?php if (!$tuningDTO->isActive) {?>
                                <input type="hidden" name="tunings[<?= $key ?>][vehicle_tuning][is_active]" value="0">
                            <?php } else { ?>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                                <div class="form-group form-check">
                                                    <input type="checkbox" class="form-check-input" value="1"
                                                           name="tunings[<?= $key ?>][vehicle_tuning][is_active]" <?= $tuningDTO->vehicleTuningDTO->isActive ? 'checked' : ''; ?> >
                                                    <label class="form-check-label" for="exampleCheck1">
                                                        <h5 class="card-title"><?= $tuningDTO->name ?></h5>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                                <div class="form-row">

                                                    <div class="form-group col-xl-3 col-lg-3 col-sm-12">
                                                        <label for="difference_power"> <?= $language::translate('Difference Power') ?></label>
                                                        <input type="number" name="tunings[<?= $key ?>][vehicle_tuning][difference_power]"
                                                               class="form-control" id="difference_power"
                                                               value="<?= $tuningDTO->vehicleTuningDTO->differencePower ?>">
                                                    </div>
                                                    <div class="form-group col-xl-3 col-lg-3 col-sm-12">
                                                        <label for="max_power"> <?= $language::translate(
                                                                'Max Power'
                                                            ) ?></label>
                                                        <input type="number" name="tunings[<?= $key ?>][vehicle_tuning][max_power]"
                                                               class="form-control" id="max_power"
                                                               value="<?= $tuningDTO->vehicleTuningDTO->maxPower ?>">
                                                    </div>
                                                    <div class="form-group col-xl-3 col-lg-3 col-sm-12">
                                                        <label for="difference_torque"> <?= $language::translate('Difference Torque') ?></label>
                                                        <input type="number" name="tunings[<?= $key ?>][vehicle_tuning][difference_torque]"
                                                               class="form-control" id="difference_torque"
                                                               value="<?= $tuningDTO->vehicleTuningDTO->differenceTorque ?>">
                                                    </div>
                                                    <div class="form-group col-xl-3 col-lg-3 col-sm-12">
                                                        <label for="max_torque"> <?= $language::translate('Max Torque') ?></label>
                                                        <input type="number" name="tunings[<?= $key ?>][vehicle_tuning][max_torque]"
                                                               class="form-control" id="max_torque"
                                                               value="<?= $tuningDTO->vehicleTuningDTO->maxTorque ?>">
                                                    </div>

                                                    <div class="form-group col-xl-3 col-lg-3 col-sm-12">
                                                        <label for="method"> <?= $language::translate('Power Chart') ?></label>
                                                        <input type="text"
                                                               name="tunings[<?= $key ?>][vehicle_tuning][power_chart]"
                                                               class="form-control" id="power_chart"
                                                               value="<?= $tuningDTO->vehicleTuningDTO->powerChart ?>">
                                                    </div>
                                                    <div class="form-group col-xl-3 col-lg-3 col-sm-12">
                                                        <label for="method"> <?= $language::translate('Torque Chart') ?></label>
                                                        <input type="text"
                                                               name="tunings[<?= $key ?>][vehicle_tuning][torque_chart]"
                                                               class="form-control" id="torque_chart"
                                                               value="<?= $tuningDTO->vehicleTuningDTO->torqueChart ?>">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                    </div>


                <div class="col-xl-12 col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary" style="width: 100%">Save</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <?php include("alt.php")?>
    </div>
    <?php include("js.php")?>
    <script src="<?= SITE_URL ?>\plugins\select2\select2.min.js"></script>
    <script src="<?= SITE_URL ?>\plugins\bootstrap-toggle\bootstrap-toggle.min.js"></script>
    <!-- BEGIN PAGE LEVEL SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?= SITE_URL ?>\plugins\table\datatable\datatables.js"></script>
</body>
<script>

    $(function () {

        $(".category-select").select2({
            ajax: {
                url: '/ajax/admin/category/list-for-select',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            },
            minimumInputLength: 3
        }).val(<?= $new ?: $vehicle->getEngineId() ?>).trigger('change');
    });

    $(document).ready( function() {
        $(document).on('change', '.btn-file :file', function() {
            var input = $(this),
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [label]);
        });

        $('.btn-file :file').on('fileselect', function(event, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = label;

            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }

        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#img-upload').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp").change(function(){
            readURL(this);
        });
    });
</script>
<style>
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }

    #img-upload{
        width: 100%;
    }

    .select2-container {
        width: 80% !important;
    }

    .select2-new-button{
        line-height: 2.1 !important;
    }

    .select2-container.mb-4 {
        margin-bottom: 0 !important;
    }
</style>
</html>
