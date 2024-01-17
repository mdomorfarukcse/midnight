<?php

use Pemm\Core\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\AdditionalOption;
use Pemm\Model\TuningAdditionalOption;
use Pemm\Model\Tuning;
use Pemm\Model\Helper;
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

/* @var Request $request */
$request = $container->get('request');

/* @var Session $session */
$session = $container->get('session');

$language = $container->get('language');

$tuning = $container->has('detailId') ? (new Tuning())->find($container->get('detailId')) : new Tuning();

if (!empty($container->get('detailId'))) {
    $tuning->getOptions();
}

$additionalOptions = (new AdditionalOption())->findAll();

if (!empty($additionalOptions)) {
    /* @var AdditionalOption $additionalOption */
    /* @var TuningAdditionalOption $option */
    foreach ($additionalOptions as $additionalOption) {
        $has = false;
        foreach ($tuning->options as $option) {
            if ($option->getAdditionalOption()->getId() == $additionalOption->getId()) {
                $has = true;
            }
        }

        if (!$has) {
            $tuning->options[] = (new TuningAdditionalOption())->setAdditionalOption($additionalOption);
        }
    }
}

if ($request->isMethod('post')) {

    try {

        $new = empty($tuning->getId());

        $tuning
            ->setCode($request->request->get('code'))
            ->setName($request->request->get('name'))
            ->setCredit($request->request->get('credit'))
            ->setSortOrder($request->request->getInt('sort_order'))
            ->setIsActive($request->request->getInt('is_active'));

        $tuning->store();

        $tuning->saveOption($request->request->get('option'));

        $session->getFlashBag()->add('success', 'Success');

        if ($new) header('location: /admin/tuning/detail/' . $tuning->getId() . '?confirm_message=Success');

    } catch (\Exception $exception) {
        $session->getFlashBag()->add('danger', $exception->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $language::translate('Tuning') ?> - <?= SITE_NAME ?> </title>
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
            <form action="" method="post" enctype="multipart/form-data" style="width: 100%">
                <div class="row layout-top-spacing">
                    <div class="col-xl-12 col-lg-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= $language::translate('Tuning') ?></h5>
                                <?php
                                foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                                    <div class="alert alert-<?= $type ?>">
                                        <?php foreach ($messages as $message) { echo $message;} ?>
                                    </div>
                                <?php }
                                ?>
                                <div class="row">
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12">
                                        <label for="code"> <?= $language::translate('Code') ?></label>
                                        <input type="text" name="code" class="form-control" id="code" value="<?= $tuning->getCode() ?>">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                        <label for="name"> <?= $language::translate('Name') ?></label>
                                        <input type="text" name="name" class="form-control" id="name" value="<?= $tuning->getName() ?>">
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12">
                                        <label for="credit"> <?= $language::translate('Credit') ?></label>
                                        <input type="text" name="credit" class="form-control" id="credit" value="<?= $tuning->getCredit() ?>">
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12">
                                        <label for="sort_order"> <?= $language::translate('Sort Order') ?></label>
                                        <input type="text" name="sort_order" class="form-control" id="sort_order" value="<?= $tuning->getSortOrder() ?>">
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12">
                                        <label for="is_active"> <?= $language::translate('Status') ?></label><br>
                                        <input type="checkbox" value="1" name="is_active" <?= !$tuning->getIsActive() ?: 'checked'; ?> data-toggle="toggle">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row layout-top-spacing">
                    <div class="col-xl-12 col-lg-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= $language::translate('Tuning Options') ?></h5>
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-sm-12">
                                        <table id="tuning-list" class="table table-striped" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th><?= $language::translate('Option name') ?></th>
                                                <th><?= $language::translate('Option Credit') ?></th>
                                                <th><?= $language::translate('Status') ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if (!empty($tuning->options)) {
                                                /* @var TuningAdditionalOption $_option */
                                                foreach ($tuning->options as $key => $_option) { ?>
                                                    <tr>
                                                        <td><?= $_option->getAdditionalOption()->getName() ?></td>
                                                        <td><input type="number" name="option[<?= $_option->getAdditionalOption()->getId() ?>][credit]" class="form-control col-md-6 col-sm-12" value="<?= $_option->getCredit() ?>"></td>
                                                        <td><input type="checkbox" name="option[<?= $_option->getAdditionalOption()->getId() ?>][is_active]"
                                                                <?= $_option->getIsActive() ? 'checked' : '' ?>
                                                                   class="form-check-input" id="tuning-option"></td>
                                                    </tr>
                                                <?php }
                                            } ?>
                                            </tbody>
                                        </table>
                                        <ul class="row list-unstyled">

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row layout-top-spacing">
                    <div class="col-xl-12 col-lg-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary" style="width: 100%">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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

    .table-striped tbody tr:nth-of-type(odd) {
    background-color: #0e1726!important;
}

</style>
</html>
