<?php

use Pemm\Core\Container;
use Pemm\Model\ReadMethod;
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

$readMethods = (new ReadMethod())->findAll();

$language = $container->get('language');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $language::translate('Read Methods') ?> - <?= SITE_NAME ?> </title>
    <?php include("css.php")?>
    <link href="<?= SITE_URL ?>\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
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

            </div>
            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                    <div class="widget-content widget-content-area br-6">
                        <a href="/admin/read-method/new" class="btn btn-outline-info btn-sm col-md-12 new-entity-button"><i class="ti-plus"></i>  <?= $language::translate('Read Methods') ?> </a>
                        <table id="read-method-list" class="table table-striped" style="width:100%">
                            <thead>
                            <tr>
                                <th><?= $language::translate('ID') ?></th>
                                <th><?= $language::translate('Image') ?></th>
                                <th><?= $language::translate('Name') ?></th>
                                <th><?= $language::translate('Surname') ?></th>
                                <th><?= $language::translate('Status') ?></th>
                                <th><?= $language::translate('Operation') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($readMethods)) {
                                /* @var ReadMethod $readMethod */
                                foreach ($readMethods as $readMethod) { ?>
                                    <tr>
                                        <td><?= $readMethod->getId() ?></td>
                                        <td><img src="<?= $readMethod->getImage(true) ?>" width="100"></td>
                                        <td><?= $readMethod->getName() ?></td>
                                        <td><?= $readMethod->getSurname() ?></td>
                                        <td><?= $readMethod->getIsActive() ? 'Active' : 'Passive'?></td>
                                        <td><a class="btn btn-outline-info btn-sm operation-icons" href="/admin/read-method/detail/<?= $readMethod->getId() ?>"><i class="ti-pencil-alt"></i></td>
                                    </tr>
                                <?php }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php include("alt.php")?>
    </div>
    <?php include("js.php")?>
    <script src="<?= SITE_URL ?>\plugins\select2\select2.min.js"></script>
    <!-- BEGIN PAGE LEVEL SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?= SITE_URL ?>\plugins\table\datatable\datatables.js"></script>
    <script>
        var readMethodDataTable = $('#read-method-list').dataTable( {
            lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
            language : { url : "/ajax/datatable/language"},
            scrollX : true
        });
    </script>
    <style>

        div.dataTables_wrapper div.dataTables_info {
            display: none;
        }

        .dataTables_length, .dataTables_filter {
            margin: 20px;
        }

        .dataTables_paginate {
            padding: 20px;
        }
        .badge-dark {
            color: #fff;
            background-color: #3b3f5c;
            border-radius: 0;
        }
        .operation-icons {
            margin: 5px;
        }

    </style>

</body>
</html>
