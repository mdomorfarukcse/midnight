<?php

use Pemm\Core\Container;
use Pemm\Model\Category;
use Pemm\Model\Vehicle;
use Pemm\Model\Tuning;
use Pemm\Model\TuningAdditionalOption;
use Pemm\Model\AdditionalOption;
use Pemm\Model\Customer;
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

$categoryInstance = new Category();
$categoryInstance->getCategories();

$tunings = (new Tuning())->findBy(['filter' => ['is_active' => 1]]);

$language = $container->get('language');

/* @var Customer $customer */
$customer = $container->get('customer');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $language::translate('File Upload') ?> - <?= SITE_NAME ?> </title>
    <?php include("css.php") ?>
    <link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
     <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="\plugins\jquery-step\jquery.steps.css">
    <style>
        #formValidate .wizard > .content {
            min-height: 25em;
        }

        #example-vertical.wizard > .content {
            min-height: 24.5em;
        }
		.layout-spacing {
			margin-top: 40px;
		}
    </style>
    <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
    <link href="\assets\css\tables\table-basic.css" rel="stylesheet" type="text/css">
    <link href="\assets\css\components\custom-list-group.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
    <link href="\plugins\file-upload\file-upload-with-preview.min.css" rel="stylesheet" type="text/css">


</head>
<body>
<?php include("ust2.php") ?>

<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">
    <?php include("ust.php") ?>
    <div id="content" class="main-content">

      <div  class="" style="z-index:999; position: fixed;    right: 50px; top: 70px;">
          <span   class='badge-chip badge-warning mt-2 mb-2 ml-2'>
              <span ><?= $language::translate('Total credits in your account:') ?> <?= $customer->getCredit() ?>  <?= $language::translate('Credit') ?>  </span>
      </div>

        <div id="credit" class="" style="z-index:999; position: fixed;right: 50px;top: 120px;">
            <span   class='badge-chip badge-success mt-2 mb-2 ml-2' id='lblCartCount'>
                <span class="total-credit"><?= $language::translate('Credit to be spent:') ?> 0</span> <?= $language::translate('Credit') ?> </span>
        </div>
		  <div class="layout-px-spacing">
		  
        <div class="col-lg-12 layout-spacing">
            <div   class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4><?= $language::translate('File Upload') ?></h4>
                        </div>
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <div id="info-block"></div>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area" style="overflow-x: hidden">
                    <div id="file-upload-step" class="">
                        <h3><?= $language::translate('Select Vehicle') ?></h3>
                        <section>
                            <div id="vehicle-select-block needs-validation" class="row" novalidate>
                                <input type="hidden" id="vehicle-id" name="vehicle_id" value="">
                                <div class="col-sm-12 mb-1">
                                    <label> <?= $language::translate('Select Type') ?>  <a onclick="return wmvSelect(this, 'cancel');" id="wmvcancel1" data-wmvid="1" style="display:none;cursor: pointer;">[Cancel]</a></label>
                                    <select name="main" data-title="<?= $language::translate('Type') ?>" data-symbol="" data-type="main" class="mustiselam main" data-wmvid="1" id="wmvselect1"
                                            onchange="getSubCategoriesForSelect(this); wmvSelect(this);" required>
                                        <option value=""><?= $language::translate('Select Type') ?></option>
                                        <?php
                                        $types = (new Category())->findBy(['filter' => ['parent_id' => 0, 'is_active' => 1]]);
                                        /* @var Category $type */
                                        foreach ($types as $type) { ?>
                                            <option value="<?= $type->getId() ?>"><?= $type->getName() ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <input id="wmvinput1" name="main" data-title="<?= $language::translate('Type') ?>" data-symbol="" data-type="main" data-wmvid="1" type="text" class="form-control" placeholder="<?= $language::translate('Write Type') ?>" style="display:none;" disabled><br/>

                                    <div class="invalid-feedback">
                  Please selected any option.
                </div>
                                </div>
                                <div class="col-sm-12 mb-1">
                                  <label> <?= $language::translate('Select Brand') ?> <a onclick="return wmvSelect(this, 'cancel');" id="wmvcancel2" data-wmvid="2" style="display:none;cursor: pointer;">[Cancel]</a></label>
                                    <select name="brand" data-title="<?= $language::translate('Brand') ?>" data-symbol="" data-type="brand" class="mustiselam brand " data-wmvid="2" id="wmvselect2"
                                            onchange="getSubCategoriesForSelect(this); wmvSelect(this);" required>
                                        <option value=""><?= $language::translate('Select Brand') ?></option>
                                    </select>
                                    <input id="wmvinput2" name="brand" data-title="<?= $language::translate('Brand') ?>" data-symbol="" data-type="brand" data-wmvid="2" type="text" class="form-control" placeholder="<?= $language::translate('Write Brand') ?>" style="display:none;" disabled><br/>
                                </div>
                                <div class="col-sm-6 mb-1">
                                    <label> <?= $language::translate('Select Model') ?> <a onclick="return wmvSelect(this, 'cancel');" id="wmvcancel3" data-wmvid="3" style="display:none;cursor: pointer;">[Cancel]</a></label>
                                    <select name="model" data-title="<?= $language::translate('Model') ?>" data-symbol="" data-wmvid="3" id="wmvselect3" data-type="model" class="mustiselam model" onchange="getSubCategoriesForSelect(this); wmvSelect(this);" required>
                                        <option value=""><?= $language::translate('Select Model') ?></option>
                                    </select>
                                    <input id="wmvinput3" name="model" data-title="<?= $language::translate('Model') ?>" data-symbol="" data-type="model" data-wmvid="3" type="text" class="form-control" placeholder="<?= $language::translate('Write Model') ?>" style="display:none;" disabled><br/>
                                </div>
                                <div class="col-sm-6 mb-1">
                                    <label> <?= $language::translate('Select Generation') ?> <a onclick="return wmvSelect(this, 'cancel');" id="wmvcancel4" data-wmvid="4" style="display:none;cursor: pointer;">[Cancel]</a></label>
                                    <select name="generation" data-title="<?= $language::translate('Generation') ?>" data-wmvid="4" id="wmvselect4" data-symbol="" data-type="generation" class="mustiselam generation" onchange="getSubCategoriesForSelect(this); wmvSelect(this);"
                                            required >
                                        <option value=""><?= $language::translate('Select Generation') ?></option>
                                    </select>
                                    <input id="wmvinput4" name="generation" data-title="<?= $language::translate('Generation') ?>" data-symbol="" data-type="generation" data-wmvid="4" type="text" class="form-control" placeholder="<?= $language::translate('Write Generation') ?>" style="display:none;" disabled><br/>
                                </div>
                                <div class="col-sm-4 mb-1">
                                    <label> <?= $language::translate('Select Engine') ?> <a onclick="return wmvSelect(this, 'cancel');" id="wmvcancel5" data-wmvid="5" style="display:none;cursor: pointer;">[Cancel]</a></label>
                                    <select required name="engine" data-title="<?= $language::translate('Engine') ?>" data-symbol="" data-wmvid="5" id="wmvselect5" data-type="engine" class="mustiselam engine" onchange="getEcuForSelect(this); wmvSelect(this);"  >
                                        <option value=""><?= $language::translate('Select Engine') ?></option>
                                    </select>
                                    <input id="wmvinput5" name="engine" data-title="<?= $language::translate('Engine') ?>" data-symbol="" data-type="engine" data-wmvid="5" type="text" class="form-control" placeholder="<?= $language::translate('Write Engine') ?>" style="display:none;" disabled><br/>
                                </div>
                                <div class="col-sm-4 mb-1">
                                    <label> <?= $language::translate('Select Ecu') ?> <a onclick="return wmvSelect(this, 'cancel');" id="wmvcancel6" data-wmvid="6" style="display:none;cursor: pointer;">[Cancel]</a></label>
                                    <select required name="ecu" data-title="<?= $language::translate('Ecu') ?>" data-symbol="" data-wmvid="6" id="wmvselect6" data-type="ecu" class="mustiselam ecu" onchange="getVehicleByEcu(this); wmvSelect(this);"  >
                                        <option value=""><?= $language::translate('Select Ecu') ?></option>
                                    </select>
                                    <input id="wmvinput6" name="ecu" data-title="<?= $language::translate('Ecu') ?>" data-symbol="" data-type="ecu" data-wmvid="6" type="text" class="form-control" placeholder="<?= $language::translate('Write Ecu') ?>" style="display:none;" disabled><br/>
                                </div>

                                <div class="col-sm-4 mb-1">
                                    <label> <?= $language::translate('Select Gear') ?></label>
                                    <select id="gear" data-title="<?= $language::translate('Gear') ?>" data-symbol=""
                                            name="gear" class="mustiselam custom-select" required>
                                            <option value=""><?= $language::translate('Select Gear') ?></option>
                                        <option value="<?= $language::translate('Otomatik') ?>"><?= $language::translate('Otomatik') ?></option>
                                        <option value="<?= $language::translate('Yarı Otomatik') ?>"><?= $language::translate('Yarı Otomatik') ?></option>
                                        <option value="<?= $language::translate('Manuel') ?>"><?= $language::translate('Manuel') ?></option>
                                    </select>
                                </div>

                                <div class="col-sm-3 mb-1" style="    margin-bottom: 11px !important;">
                                    <label> <?= $language::translate('Torque') ?> (Nm)* </label>
                                    <input id="torque" data-title="<?= $language::translate('Torque') ?>" data-symbol="Nm"
                                           type="text" name="torque" placeholder="<?= $language::translate('Torque') ?> (Nm)*"
                                           class="form-control"  >

                                </div>

                                <div class="col-sm-3 mb-1" style="    margin-bottom: 11px !important;">
                                    <label> <?= $language::translate('Power') ?> (bhp)* </label>
                                    <input id="power" data-title="<?= $language::translate('Power') ?>" data-symbol="bhp"
                                           type="text" name="power" placeholder="<?= $language::translate('Power') ?> (bhp)*"
                                           class="form-control"  >
                                </div>

                                <div class="col-sm-3 mb-1">
                                    <label> <?= $language::translate('Vehicle Registration') ?> </label>
                                    <input id="vehicle-registration" data-title="<?= $language::translate('Vehicle Registration') ?>"
                                           data-symbol=""  type="text" name="vehicle_registration" placeholder="<?= $language::translate('Vehicle Registration') ?>"
                                           class="form-control"  >
                                </div>
                                <div class="col-sm-3 mb-1">
                                    <label> <?= $language::translate('Kilometer') ?></label>
                                    <input id="kilometer" data-title="<?= $language::translate('Kilometer') ?>" data-symbol=""
                                           type="number" name="kilometer" placeholder="<?= $language::translate('Kilometer') ?>"
                                           class="form-control"  required>
                                           <div class="invalid-feedback">
                  Please provide a valid Email.
                </div>
                                </div>
                            </div>
                        </section>
                        <h3><?= $language::translate('Select Ecu') ?></h3>
                        <section>
                            <div class="row">
                              <div class="col-sm-12 mb-1">
                                  <label> <?= $language::translate('When would you like the file?') ?> </label>
                                  <select class="mustiselam" name="file_time" data-title="<?= $language::translate('When would you like the file?') ?>" data-symbol=""  class="selectpicker" required>
                                      <option value=""><?= $language::translate('When would you like the file?') ?></option>
                                      <option value="<?= $language::translate('So fast as possible') ?>"><?= $language::translate('So fast as possible') ?></option>
                                      <option value="<?= $language::translate('1-2 Hours') ?>"><?= $language::translate('1-2 Hours') ?></option>
                                      <option value="<?= $language::translate('3-4 Hours') ?>"><?= $language::translate('3-4 Hours') ?></option>
                                      <option value="<?= $language::translate('Max 6 Hours') ?>"><?= $language::translate('Max 6 Hours') ?></option>
                                      <option value="<?= $language::translate('Within 1 Day') ?>"><?= $language::translate('Within 1 Day') ?></option>
                                   </select>
                              </div>

                                <div class="col-sm-6 mb-1">
                                    <label> <?= $language::translate('Reading Device') ?> </label>
                                    <select class="mustiselam" name="read_device" data-title="<?= $language::translate('Reading Device') ?>" data-symbol=""  class="selectpicker" required>
                                        <option value=""><?= $language::translate('Reading Device') ?></option>
                                        <option value="Alientech KessV2">Alientech KessV2</option>
										<option value="Alientech KessV3">Alientech KessV3</option>
										<option value="Alientech Ktag">Alientech Ktag</option>
                                        <option value="Autotuner">Autotuner - OBD</option>
                                        <option value="Autotuner">Autotuner - Bench</option>
                                        <option value="Autotuner">Autotuner - Boot</option>
										<option value="Bflash">Bflash - ODB</option>
										<option value="Bflash">Bflash - Bench</option>
										<option value="Bflash">Bflash - Boot</option>
                                        <option value="Bitbox">Bitbox</option>
                                        <option value="Byteshooter">Byteshooter</option>
                                        <option value="CMD">CMD</option>
                                        <option value="Dimsport Genius">Dimsport Genius</option>
                                        <option value="Dimsport Trasdata">Dimsport Trasdata</option>
                                        <option value="EVC BSL">EVC - BSL</option>
                                        <option value="EVC BDM">EVC - BDM</option>
                                        <option value="Magic Motorsport Flex OBD">Flex - OBD</option>
                                        <option value="Magic Motorsport Flex Bench">Flex - Bench</option>
                                        <option value="Magic Motorsport Flex Boot">Flex - Boot</option>
                                        <option value="PEmicro">PEmicro</option>
                                        <option value="Piasini">Piasini</option>
                                        <option value="PCM-Flash">PCM-Flash</option>
                                        <option value="OBD - Bench Programming">OBD - Bench Programming</option>
                                        <option value="Always Boot">Always Boot</option>
                                        <option value="Always OBD">Always OBD</option>
                                     </select>
                                </div>

                                <div class="col-sm-6 mb-1">
                                    <label> <?= $language::translate('Master / Slave') ?> </label>
                                    <select class="mustiselam" data-title="<?= $language::translate('Master / Slave') ?>" data-symbol=""  name="master_slave" required>
                                        <option value=""><?= $language::translate('Master / Slave') ?></option>
                                        <option value="Master"><?= $language::translate('Master') ?></option>
                                        <option value="Slave"><?= $language::translate('Slave') ?></option>
                                    </select>
                                </div>

                                <div class="col-sm-12 mb-1">
                                    <label> <?= $language::translate('Reading Type') ?> </label>
                                    <select class="mustiselam" data-title="<?= $language::translate('Reading Type') ?>" data-symbol=""   name="read_type">
                                        <option value=""><?= $language::translate('Reading Type') ?></option>
                                        <option value="Full Read"><?= $language::translate('Full Read') ?></option>
                                        <option value="ID Only - ID Only Vehicles"><?= $language::translate('ID Only - ID Only Vehicles') ?></option>
                                        <option value="Virtual Read"><?= $language::translate('Virtual Read') ?></option>
                                    </select>
                                </div>

                                <div class="col-sm-6 mb-1" style="    margin-bottom: 11px !important;">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1"><?= $language::translate('Manufacturer') ?></label>
                                        <input type="text" data-title="<?= $language::translate('Manufacturer') ?>" data-symbol=""
                                               name="manufacturer" class="form-control" id="exampleFormControlInput1"
                                               placeholder="<?= $language::translate('Manufacturer') ?>"  >
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-1" style="    margin-bottom: 11px !important;">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1"><?= $language::translate('Model') ?></label>
                                        <input type="text" data-title="<?= $language::translate('Model') ?>" data-symbol=""
                                               name="manufacturer_model" class="form-control" id="exampleFormControlInput1"
                                               placeholder="<?= $language::translate('Model') ?>"   >
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-1" style="    margin-bottom: 11px !important;">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1"><?= $language::translate('Equipment') ?></label>
                                        <input type="text" data-title="<?= $language::translate('Equipment') ?>" data-symbol=""
                                               name="equipment" class="form-control" id="exampleFormControlInput1"
                                               placeholder="<?= $language::translate('Equipment') ?>"   >
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-1" style="margin-bottom: 11px !important;">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1"><?= $language::translate('Software') ?></label>
                                        <input type="text" data-title="<?= $language::translate('Software') ?>" data-symbol=""
                                               name="software" class="form-control" id="exampleFormControlInput1"
                                               placeholder="<?= $language::translate('Software') ?>"   >
                                    </div>
                                </div>

                                <div class="col-sm-12 mb-1" style="margin-bottom: 11px !important;">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1"><?= $language::translate('Note') ?></label>
                                        <input id="note" data-title="<?= $language::translate('Note') ?>" data-symbol=""
                                               type="text" name="note" placeholder="<?= $language::translate('Note') ?>"
                                               class="form-control"  >
                                    </div>
                                </div>
                            </div>
                        </section>

                        <h3><?= $language::translate('Options') ?></h3>
                        <section id="tuning-additional-options">
                            <input type="hidden" name="tuning" value="" required>
                            <div class="tab-content" id="lineTabContent-3">
                                <div class="row mb-5 vehicle-tuning-block">

                                </div>
                            </div>
                        </section>
                        <h3><?= $language::translate('Files') ?></h3>
                        <section>
                            <div class="row">
                              <div class="col-lg-3 col-md-3">
                                    <div class="custom-file-container" data-upload-id="myFirstImage">
                                        <label>ECU  <?= $language::translate('File') ?> <a href="javascript:void(0)"
                                                          class="custom-file-container__image-clear"
                                                          title="Clear Image">x</a></label>
                                        <label class="custom-file-container__custom-file">
                                            <input type="file"
                                                   class="custom-file-container__custom-file__custom-file-input"
                                                    name="ecu_file" data-title="Ecu" data-symbol="" required>
                                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760"/ required >
                                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                                        </label>
                                        <div class="custom-file-container__image-preview"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <div class="custom-file-container" data-upload-id="myFirstImage2">
                                        <label>ID  <?= $language::translate('File') ?>  <a href="javascript:void(0)"
                                                           class="custom-file-container__image-clear"
                                                           title="Clear Image">x</a></label>
                                        <label class="custom-file-container__custom-file">
                                            <input type="file"
                                                   class="custom-file-container__custom-file__custom-file-input"
                                                    name="id_file" data-title="Id" data-symbol="">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>
                                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                                        </label>
                                        <div class="custom-file-container__image-preview"></div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3">
                                    <div class="custom-file-container" data-upload-id="myFirstImage3">
                                        <label>Dyno  <?= $language::translate('File') ?> <a href="javascript:void(0)"
                                                           class="custom-file-container__image-clear"
                                                           title="Clear Image">x</a></label>
                                        <label class="custom-file-container__custom-file">
                                            <input type="file"
                                                   class="custom-file-container__custom-file__custom-file-input"
                                                     name="dyno_file" data-title="Dyno" data-symbol="">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>
                                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                                        </label>
                                        <div class="custom-file-container__image-preview"></div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3">
                                    <div class="custom-file-container" data-upload-id="myFirstImage4">
                                        <label>Log  <?= $language::translate('File') ?>  <a href="javascript:void(0)"
                                                           class="custom-file-container__image-clear"
                                                           title="Clear Image">x</a></label>
                                        <label class="custom-file-container__custom-file">
                                            <input type="file"
                                                   class="custom-file-container__custom-file__custom-file-input"
                                                    name="log_file" data-title="Log" data-symbol="">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>
                                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                                        </label>
                                        <div class="custom-file-container__image-preview"></div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <h3><?= $language::translate('Send') ?></h3>
                        <section>
                            <div id="vehicle-step-info" class="row"></div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
	</div>
        <div class="col-lg-12 layout-spacing">
            <div  class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div id="vehicle-block" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <?php include("alt.php") ?>
    </div>
    <?php include("js.php") ?>
</div>

<script src="\assets\js\scrollspyNav.js"></script>
<script src="\plugins\jquery-step\jquery.steps.min.js"></script>
<script src="\plugins\jquery-step\custom-jquery.steps.js"></script>
<script src="\plugins\bootstrap-select\bootstrap-select.min.js"></script>
<style>

    .widget-table-three .table > tbody > tr > td:last-child .td-content {
        padding: 0;
        width: 100%;
        margin: 0 auto;
    }

    .wizard > .content > .body {
        padding: 4.5%;
        background: #0e1726;
    }

    .wizard > .actions a, .wizard > .actions a:hover, .wizard > .actions a:active {
      color: #ffffff;
          background-color: #009ef7;
          border: 1px solid #009ef7;
                  display: block;
        padding: 1.5em 5em;
        text-decoration: none;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
    }

    .wizard > .content > .body {
        padding: 4.5%;
        background: #ffffff;
        padding-bottom: 10px !important;
    }


    .form-group label, label {
        font-size: 15px;
        color: #565656;
        letter-spacing: 1px;
    }

    .tuning-active{
        box-shadow: 1px 1px 15px 5px #4361EE;
    }
</style>
<script src="\assets/js/alt.js"></script>
<script src="\plugins\highlight\highlight.pack.js"></script>
<script src="\plugins\file-upload\file-upload-with-preview.min.js?t=<?=time()?>"></script>

<script>
    var ecuUpload = new FileUploadWithPreview('myFirstImage')
    var idUpload = new FileUploadWithPreview('myFirstImage2')
    var dynoUpload = new FileUploadWithPreview('myFirstImage3')
    var logUpload = new FileUploadWithPreview('myFirstImage4')

</script>
<style>
    .select-validation-danger {
        border-color: #009ef7 !important;
    }
</style>
<script>
function wmvSelect(e, action) {
    if (action == 'cancel') {
        var id = $(e).data('wmvid');
        var input = $('#wmvinput'+id);
        var select = $('#wmvselect'+id);
        // select.val('');
        $(e).hide();
        input.prop('required', false);
        input.prop('disabled', true);
        select.prop('disabled', false);
        input.hide();
        select.show();

    }
  if (e.value == 'wmvmanual') {
    var selectId = $(e).data('wmvid');
    var input = $('#wmvinput'+selectId);
    var a = $('#wmvcancel'+selectId);
    var select = $('#wmvselect'+id);

    if (selectId != 6 || action != 'autoOpen') {
        console.log(selectId);
        for (var i=(selectId+1); i<5; i++) {
            var selectMe = '#wmvselect' + i;
            $(selectMe).val('wmvmanual', 'autoOpen').change();
        }
    }
    
    $(e).hide();
    input.prop('required', true);
    input.prop('disabled', false);
    select.prop('disabled', true);
    input.show();
    a.show();
  }
}

    var vehicleDatas = {};
    var readDatas = {};
    var fileUploadStep;
    var customerCredit = <?= $customer->getCredit() ?>;
    var nextAvailable = false;

    const toast = swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        padding: '2em'
    });

    var fileUploadSystem = {
        initialised: false,
        mobile: false,
        init: function () {

            if (!this.initialised) {
                this.initialised = true;
            } else {
                return;
            }

            this.fileUploadStep();
            this.validation();
            this.setData();
            this.finish();
        },
        fileUploadStep: function () {
            fileUploadStep = $("#file-upload-step").steps({
                headerTag: "h3",
                bodyTag: "section",
                transitionEffect: "slideLeft",
                autoFocus: true,
                cssClass: 'circle wizard',
                labels: {
                    cancel: "Cancel",
                    current: "current step:",
                    pagination: "Pagination",
                    finish: "<?= $language::translate('Send') ?>",
                    next: "<?= $language::translate('Next') ?>",
                    previous: "<?= $language::translate('Previous') ?>",
                    loading: "<?= $language::translate('Loading') ?> ..."
                },
                onStepChanging: function(e, currentIndex, newIndex) {
                    var validate = true;
                    if (newIndex > currentIndex) {

                        var currentStep = $('#file-upload-step').find('section.current');

                        currentStep.find('select').each(function (i, select) {
                            var selectButton = $(select).parent().find('div');
                            if ($(select).attr('required') && $(select).val() == '') {
                                validate = false;
                                $(select).addClass('select-validation-danger');
                            } else {
                                $(select).removeClass('select-validation-danger');
                            }
                        })
                        currentStep.find('input').each(function (i, input) {
                            if ($(input).attr('required') && $(input).val() == '') {
                                validate = false;
                                $(input).addClass('select-validation-danger');
                            } else {
                                $(input).removeClass('select-validation-danger');
                            }
                        });



                        if (newIndex == 4) {
                            fileUploadSystem.createdSummary();
                        }
                    }

                    return validate;
                }
            });
        },
        validation : function () {
            $('input, select').on('change', function () {
                if ($(this).val() != '') {
                    switch ($(this).prop("tagName")) {
                        case 'SELECT':
                            $(this).parent().find('button').removeClass('select-validation-danger');
                            break;
                        case 'INPUT':
                            $(this).removeClass('select-validation-danger');
                            break;
                    }
                }
            })
        },
        tuningSelect : function () {

            $('.tuning-select-button').on('click', function () {

                var tuningId = $(this).data('id');
                var tuningInput = $('input[name="tuning"]');
                var optionCredit = 0;

                $('.additional-option input').each(function (i, input) {
                    if ($(input).hasClass(tuningId + '-option')) {
                      //  $(input).removeAttr('disabled');
                    } else {
                        $(input).prop('checked', false);
                      //  $(input).attr('disabled', true);
                    }
                });

                tuningInput.val(tuningId);
                $('.col-md-3.tuning-active').removeClass('tuning-active');
                $(this).parent().addClass('tuning-active');

                $('#tuningTab' + tuningId + ' input').each(function (i, input) {

                    if ($(input).is(':checked')) {
                        optionCredit += $(input).data('credit');
                    }
                })

                $('span.total-credit').text($(this).data('credit') + optionCredit);
            });

        },
        setData: function () {
            $('input, select').on('change', function () {

                if ($(this).val() == 'wmvmanual') { return false;}

                var elementData = {};
                
                var wmvelementId = $(this).data('wmvid');

                // if (isNaN(wmvelementId) == false && $(this).val() == 'wmvmanual') {
                //     elementData.text = $('#wmvinput'+wmvelementId).val();
                //     elementData.val = $('#wmvinput'+wmvelementId).val();
                //     elementData.title = $('#wmvselect'+wmvelementId).data("title");
                //     elementData.symbol = $('#wmvselect'+wmvelementId).data("symbol");
                //     elementData.name = $('#wmvselect'+wmvelementId).attr('name');
                // }else {
                    elementData.text = ($(this).get(0).nodeName == 'SELECT' ? $(this).find(":selected").text() : $(this).val());
                    elementData.val = $(this).val();
                    elementData.title = $(this).data("title");
                    elementData.symbol = $(this).data("symbol");
                    elementData.name = $(this).attr('name');
                // }

                console.log(elementData);

                if (elementData.name == 'undefined' || elementData.text == 'undefined') {  }

                switch (fileUploadStep.steps("getCurrentIndex")) {
                    case 0:
                        vehicleDatas[elementData.name] = elementData;
                        break;
                    case 1:
                        readDatas[elementData.name] = elementData;
                        break;
                }
            });
        },
        finish: function () {
            $('a[href="#finish"]').on('click', function () {

          //    document.body.innerHTML += '<div id="load_screen"> <div class="loader"> <div class="loader-content"><div class="spinner-grow align-self-center"></div></div></div></div>';

                $(this).hide();
                $('a[href="#finish"]').hide();
                $(this).attr('disabled', 'disabled');
                var formData = new FormData();

                for ( var vkey in vehicleDatas ) formData.append(vkey, vehicleDatas[vkey]['val']);
                for ( var rkey in readDatas ) formData.append(rkey, readDatas[rkey]['val']);

                formData.append('tuning', $('.tuning-active button').data('id'));

                var options = [];

                $('.tuning-active input').each(function (i, option) {
                    if ($(option).is(':checked')) {
                        options.push($(option).data('id'));
                    }
                })

                formData.append('tuningOptions', options);

                formData.append('ecuFile', $('input[name="ecu_file"]')[0].files[0]);
                formData.append('idFile', $('input[name="id_file"]')[0].files[0]);
                formData.append('logFile', $('input[name="log_file"]')[0].files[0]);
                formData.append('dynoFile', $('input[name="dyno_file"]')[0].files[0]);

                if ($('input#vehicle-id').val() > 1) {
                    formData.append('vehicleId', vehicleId);
                }else {
                    formData.append('vehicleId', 1);
                }
                

                formData.append('torque', $('input[name="torque"]').val());
                formData.append('power', $('input[name="power"]').val());
                if ($('select[name="ecu"] option:selected').val() != 'wmvmanual') {
                    formData.append('ecu', $('select[name="ecu"] option:selected').text());
                    formData.append('wmvmanual', 0);
                }else {
                    formData.append('ecu', $('input[name="ecu"]').val());
                    formData.append('wmvmanual', 1);
                    formData.append('option_main', $('#wmvselect1 option:selected').text());
                    formData.append('option_brand', $('#wmvselect2 option:selected').text());
                    formData.append('option_model', $('#wmvselect3 option:selected').text());
                    formData.append('option_generation', $('#wmvselect4 option:selected').text());
                    formData.append('option_engine', $('#wmvselect5 option:selected').text());
                    formData.append('option_ecu', $('#wmvselect6 option:selected').text());
                }

                //$(this).prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "/ajax/create-customer-vehicle",
                    success: function (response) {
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                        if (response.success) {
                            $('#info-block').html('<div class="alert alert-success">' + response.message + '</div>');
                            setTimeout(function(){
                                window.location.href= '/panel/my-files';
                            }, 3000);
                        } else {
                            $('#info-block').html('<div class="alert alert-danger">' + response.message + '</div>');
                        }

                        var myobj = document.getElementById("load_screen");
                        myobj.remove();
                    },
                    error: function (error) {},
                    data: formData,
                    enctype: 'multipart/form-data',
                    cache: false,
                    contentType: false,
                    processData: false
                })
                //$(this).prop('disabled', false);
            })
        },
        createdSummary: function () {

            var VehicleFields = ['brand', 'model', 'generation', 'engine', 'ecu', 'gear', 'torque', 'power', 'kilometer'];
            var readFields = ['read_device', 'master_slave', 'read_type', 'manufacturer', 'manufacturer_model', 'equipment', 'software', 'note'];

            var summaryHtml = '<div class="col-sm-6 mb-1">' +
                '<div class="col-xl-12 col-md-12 col-sm-12 col-12">' +
                '<h5 class="mb-3"><?= $language::translate('Vehicle Information') ?></h5> ' +
                '</div> ' +
                '<div class="table-responsive"> ' +
                '<table class="table table-striped mb-4"> ' +
                '<tbody>';

            for ( var vkey in vehicleDatas ) {
                summaryHtml +=  '<tr><td>' + vehicleDatas[vkey]['title'] + '</td><td>' + vehicleDatas[vkey]['text'] + '</td></tr>';
            }

            summaryHtml += '</tbody></table></div></div>';

            summaryHtml += '<div class="col-sm-6 mb-1">' +
                '<div class="col-xl-12 col-md-12 col-sm-12 col-12">' +
                '<h5 class="mb-3"><?= $language::translate('Ecu Information') ?></h5> ' +
                '</div> ' +
                '<div class="table-responsive"> ' +
                '<table class="table table-striped mb-4"> ' +
                '<tbody>';

            for ( var rkey in readDatas ) {
                summaryHtml +=  '<tr><td>' + readDatas[rkey]['title'] + '</td><td>' + readDatas[rkey]['text'] + '</td></tr>';
            }

            summaryHtml += '</tbody></table></div></div>';

            summaryHtml += '<div class="col-sm-6 mb-1">' +
                '<div class="col-xl-12 col-md-12 col-sm-12 col-12">' +
                '<h5 class="mb-3"><?= $language::translate('Tuning Options') ?></h5> ' +
                '</div> ' +
                '<div class="table-responsive"> ' +
                '<table class="table table-striped mb-4"> ' +
                '<tbody>';

            summaryHtml +=  '<tr><td>' + $('.tuning-active button').data('title') + '</td></tr>';
            $('.tuning-active input').each(function (i, option) {
                if ($(option).is(':checked')) {
                    summaryHtml +=  '<tr><td>' + $(option).data('title') + '</td></tr>';
                }
            })

            summaryHtml += '</tbody></table></div></div>';

            summaryHtml += '<div class="col-sm-6 mb-1">' +
                '<div class="col-xl-12 col-md-12 col-sm-12 col-12">' +
                '<h5 class="mb-3"><?= $language::translate('Files') ?></h5> ' +
                '</div> ' +
                '<div class="table-responsive"> ' +
                '<table class="table table-striped mb-4"> ' +
                '<tbody>';

            summaryHtml +=  '<tr><td>' + $('input[name="ecu_file"]').data('title') + '</td><td>' + $('input[name="ecu_file"]').val().replace('C:\\fakepath\\', '') + '</td></tr>';
            summaryHtml +=  '<tr><td>' + $('input[name="id_file"]').data('title') + '</td><td>' + $('input[name="id_file"]').val().replace('C:\\fakepath\\', '') + '</td></tr>';
            summaryHtml +=  '<tr><td>' + $('input[name="log_file"]').data('title') + '</td><td>' + $('input[name="log_file"]').val().replace('C:\\fakepath\\', '') + '</td></tr>';
            summaryHtml +=  '<tr><td>' + $('input[name="dyno_file"]').data('title') + '</td><td>' + $('input[name="dyno_file"]').val().replace('C:\\fakepath\\', '') + '</td></tr>';
            summaryHtml += '</tbody></table></div></div>';

            $('#vehicle-step-info').html(summaryHtml);
            $('.bootstrap-select').removeClass('mustiselam');

        }
    };

    (function ($) {
        'use strict';

        jQuery(document).ready(function () {
            fileUploadSystem.init();
        });

    })(jQuery);


   function getSubCategoriesForSelect(element) {

         var type = $(element).data('type');

         resetChainSelect(type);

         if ($(element).val() && $(element).val() != 'wmvmanual') {
             $('button').prop('disabled', true);
             $.ajax({
                 url: '/ajax/category/' + $(element).val() + '/get-sub-categories-for-select',
                 success: function (response) {
                     if (response.success) {
                         pushAfterSelectCategory(element, response.result)
                     }
                 },
                 error: function (error) {
                 }
             })
             $('button').prop('disabled', false);
         }
     }

    function calculateCredit(tuningId)
    {
        var tuningCredit = $('#tuningButton' + tuningId).data('credit');
        var optionCredit = 0;
        var tuningInput = $('input[name="tuning"]');

        tuningInput.val(tuningId);
        $('.col-md-3.tuning-active').removeClass('tuning-active');
        $('#tuningTab'+tuningId).addClass('tuning-active');

        $('.additional-option input').each(function (i, input) {
            if ($(input).hasClass(tuningId + '-option')) {
              //  $(input).removeAttr('disabled');
            } else {
                $(input).prop('checked', false);
            }
        });



        $('#tuningTab' + tuningId + ' input').each(function (i, input) {

            if ($(input).is(':checked')) {
                optionCredit += $(input).data('credit');
            }
        })





        $('span.total-credit').text(tuningCredit + optionCredit);
    }

    var category_chain = ['main', 'brand', 'model', 'generation', 'engine'];

    function resetChainSelect(type) {

        var reset = false;

        $('#vehicle-select-block').find('select').each(function (i, select) {
            if (reset) {
                if ($(select).attr('id') != 'gear') {
                    var firstOption = $(select).find('option').first();
                    $(select).html('');
                    $(select).selectpicker('refresh');
                }
            }
            if (type === $(select).data('type')) {
                reset = true;
            }
        })

        $('input#torque').val('');
        $('input#power').val('');
        $('input#vehicle-id').val('');

    }

    function pushAfterSelectCategory(element, result) {

        var categoryType = getCategoryTypeByBeforeCategoryType($(element).data('type'));

        var categorySelectElement = $('select.' + categoryType);

        var selectOptionsHtml = categorySelectElement.html() + '\n';

        result.forEach(function (data) {
            selectOptionsHtml += '<option value="' + data.id + '">' + data.name + '</option>\n'
        });

        categorySelectElement.html(selectOptionsHtml);
        categorySelectElement.selectpicker('refresh');
        $('.bootstrap-select').removeClass('mustiselam');
    }

    function getCategoryTypeByBeforeCategoryType(type) {
        return category_chain[(category_chain.indexOf(type) + 1)];
    }

    function getEcuForSelect(element) {

        var ecu = $('select.ecu');

        var firstOption = ecu.find('option').first();
        ecu.html('');

        if ($(element).val()) {
            $('button').prop('disabled', true);
            $.ajax({
                url: '/ajax/vehicle/' + $(element).val() + '/get-ecu-for-select',
                success: function (response) {
                    if (response.success) {
                        var selectOptionsHtml = ecu.html() + '\n';
                        selectOptionsHtml += '<option value="wmvmanual"><?= $language::translate('Add a new option.') ?></option>\n'

                        if ($(element).val() != 'wmvmanual') {
                            response.result.forEach(function (data) {
                                selectOptionsHtml += '<option value="' + data.id + '">' + data.name + '</option>\n'
                            });
                        }


                        
                            ecu.html(selectOptionsHtml);
                            ecu.selectpicker('refresh');

                        $('.bootstrap-select').removeClass('mustiselam');
                    }
                },
                error: function (error) {
                }
            })
            $('button').prop('disabled', false);
        }
    }

    function getVehicleByEcu() {

        var ecu = $('select.ecu');
        var vehicleBlock = $('#vehicle-block');

        vehicleBlock.html('');

        if (ecu.val()) {

            $('button').prop('disabled', true);
            $.ajax({
                url: '/ajax/vehicle/' + ecu.val() + '/get-vehicle-by-id-with-html-for-home',
                success: function (response) {
                    if (response) {
                        vehicleBlock.html(response);
                        $('input#torque').val(vehicleTorque);
                        $('input#power').val(vehiclePower);
                        $('input#vehicle-id').val(vehicleId);
                        $('.vehicle-tuning-block').html(vehicleTuningHtml);
                        fileUploadSystem.tuningSelect();
                        fileUploadSystem.createdSummary();
                        if (ecu.val() != 'wmvmanual') {
                            $([document.documentElement, document.body]).animate({
                                scrollTop: ($("#vehicle-block").offset().top - 100)
                            }, "slow");
                        }

                    }
                },
                error: function (error) {
                }
            })
            $('button').prop('disabled', false);

            if (ecu.val() == 'wmvmanual') { ecu.prop('disabled', true); }
        }
    }

</script>
<style>
    @media(max-width: 575px) {
        .wizard > .actions a, .wizard > .actions a:hover, .wizard > .actions a:active {
           padding: 1em 2em;
        }
    }

    .mustiselam{
      border: 1px solid #c7c7c7;
      color: #565656!important;
      font-size: 15px;
      padding: 8px 10px;
      letter-spacing: 1px;
      background-color: #ffffff;
      height: auto;
      padding: 0.75rem 1.25rem;
      border-radius: 6px;
      box-shadow: none;
      width: 100%;
    }

    .btn:not(:disabled):not(.disabled) {
    cursor: pointer;
 }

  .table-striped tbody tr:nth-of-type(odd) {
   background-color: #f2f2f2 !important;
   border: none !important;
 }
 .table td, .table th {
      border-top: none !important;
 }
 </style>
</body>
</html>
