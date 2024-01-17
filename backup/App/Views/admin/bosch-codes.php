<?php

use Pemm\Core\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\BoschCodes;
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

/* @var Request $request */
$request = $container->get('request');

/* @var Session $session */
$session = $container->get('session');

$language = $container->get('language');

$boschCodes = $container->has('detailId') ? (new BoschCodes())->find($container->get('detailId')) : new BoschCodes();

if ($request->isMethod('post')) {

    try {

        $new = empty($boschCodes->getId());

        $boschCodes
            ->setManufacturerNumber($request->request->get('manufacturer_number'))
            ->setEcu($request->request->get('ecu'))
            ->setEcuBrand($request->request->get('ecu_brand'))
            ->setIsActive(@intval($request->request->getInt('is_active')));

        $boschCodes->store();

        $session->getFlashBag()->add('success', 'Success');

        if ($new) header('location: /admin/bosch-codes/detail/' . $boschCodes->getId() . '?confirm_message=Success');

    } catch (\Exception $exception) {
        $session->getFlashBag()->add('danger', $exception->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $language::translate('Bosch Codes') ?> - <?= SITE_NAME ?> </title>
    <?php include("css.php")?>
    <link href="<?= SITE_URL ?>\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\bootstrap-select\bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\bootstrap-toggle\bootstrap-toggle.min.css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\table\datatable\datatables.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\assets\css\forms\theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\table\datatable\dt-global_style.css">
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
                <div class="col-xl-6 col-lg-6 col-sm-12 ">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= $language::translate('Bosch Codes') ?></h5>
                            <form action="" method="post" enctype="multipart/form-data" style="width: 100%">
                                <?php
                                foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                                    <div class="alert alert-<?= $type ?>">
                                        <?php foreach ($messages as $message) { echo $message;} ?>
                                    </div>
                                <?php }
                                ?>
                                <div class="form-group row">
                                    <label for="manufacturer_number" class="col-sm-2 col-form-label"> <?= $language::translate('Manufacturer Number') ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="manufacturer_number" class="form-control" id="manufacturer_number" value="<?= $boschCodes->getManufacturerNumber() ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="ecu" class="col-sm-2 col-form-label"> <?= $language::translate('Ecu') ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="ecu" class="form-control" id="ecu" value="<?= $boschCodes->getEcu() ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="ecu_brand" class="col-sm-2 col-form-label"> <?= $language::translate('Ecu Brand') ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="ecu_brand" class="form-control" id="ecu_brand" value="<?= $boschCodes->getEcuBrand() ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">Status</div>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="1" name="is_active" <?= !$boschCodes->getIsActive() ?: 'checked'; ?> data-toggle="toggle">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" style="width: 100%">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include("alt.php")?>
    </div>
    <?php include("js.php")?>
    <script src="<?= SITE_URL ?>\plugins\bootstrap-select\bootstrap-select.min.js"></script>
    <script src="<?= SITE_URL ?>\plugins\bootstrap-toggle\bootstrap-toggle.min.js"></script>
    <!-- BEGIN PAGE LEVEL SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?= SITE_URL ?>\plugins\table\datatable\datatables.js"></script>
</body>
<script>
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
</style>
</html>
