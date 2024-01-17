<?php
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

$language = $container->get('language');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $language::translate('Vehicle Tunings') ?> - <?= SITE_NAME ?> </title>
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
                        <div class="col-xl-6 col-lg-6 col-sm-12 pt-3">
                            <form action="" method="get">
                                <div class="form-group">
                                    <label for="vehicle-select">Vehicle Select</label>
                                    <select class="form-control" id="vehicle-select" name="vehicle_id" onchange="getVehicleTuningListData()"></select>
                                </div>
                            </form>
                        </div>
                        <table id="vehicle-tuning-list-datatable" class="table table-striped" style="width:100%"></table>
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

        $(function () {

            $("#vehicle-select").select2({
                ajax: {
                    url: '/ajax/admin/vehicle/list-for-select',
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
                width: '100%',
                minimumInputLength: 3
            });

            if (getVehicleTuningListDataTable().length > 0) getVehicleTuningListData();
        });

        function getVehicleTuningListData() {

            var vehicleTuningDataTable = $('#vehicle-tuning-list-datatable').dataTable( {
                processing: true,
                serverSide: true,
                destroy : true,
                lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
                language : { url : "/ajax/datatable/language"},
                scrollX: true,
                columns: [
                    {
                        data : "vehicle_id",
                        title : "<?= $language::translate('Tuning') ?>",
                        className : "dt-center",
                        orderable: false,
                        render : function (vehicle_id, type, row) {
                            return row.tuning_name;
                        }
                    },
                    {
                        data : "vehicle_brand_id",
                        title : "<?= $language::translate('Brand') ?>",
                        className : "dt-center",
                        orderable: false,
                        render : function (vehicle_brand_id, type, row) {
                            return '<img title="' + row.vehicle_brand_name + '" src="' + row.vehicle_brand_image + '" width="40">';
                        }
                    },
                    {
                        data : "vehicle_full_name",
                        title : "<?= $language::translate('Vehicle Name') ?>",
                        className : "dt-center",
                        orderable: false,
                        render : function (vehicle_full_name) {
                            return vehicle_full_name;
                        }
                    },
                    {
                        data : "difference_power",
                        title : "<?= $language::translate('D. Power') ?>",
                        className : "dt-center",
                        orderable: false,
                        render : function (difference_power) {
                            return difference_power;
                        }
                    },
                    {
                        data : "difference_torque",
                        title : "<?= $language::translate('D. Torque') ?>",
                        className : "dt-center",
                        orderable: false,
                        render : function (difference_torque) {
                            return difference_torque;
                        }
                    },
                    {
                        data : "method",
                        title : "<?= $language::translate('Method') ?>",
                        className : "dt-center",
                        orderable: false,
                        render : function (method) {
                            return method;
                        }
                    },
                    {
                        data : "options",
                        title : "<?= $language::translate('Options') ?>",
                        width: '200px',
                        orderable: false,
                        render : function (options) {
                            return '<div style="width: 200px;">' + options.join("<br>") + '</div>';
                        }
                    },
                    {
                        data : "is_active",
                        title : "<?= $language::translate('Status') ?>",
                        className : "dt-center",
                        render : function (is_active) {
                            return is_active ? 'Active' : 'Passive';
                        }
                    },
                    {
                        data : "id",
                        title : "<?= $language::translate('Operation') ?>",
                        className : "dt-center",
                        orderable: false,
                        render : function (id, type, row) {
                            var operationHtml = '<a class="btn btn-outline-info btn-sm operation-icons" href="/admin/vehicle/detail/' + row.vehicle_id + '"><i class="ti-pencil-alt"></i>';
                            return operationHtml;
                        }
                    }
                ],
                ajax: {
                    type: "POST",
                    url: "/ajax/admin/vehicle/tuning/list-for-datatable",
                    data: function (data) {

                        var settings = $("#vehicle-tuning-list-datatable").dataTable().fnSettings();
                        var filter = {};

                        filter.datatable_query = data.search.value;

                        if ($('#vehicle-select').val()) {
                            filter.vehicle_id = $('#vehicle-select').val()
                        }

                        return {
                            filter : filter,
                            pagination : {
                                draw    : settings.iDraw,
                                page    : (data.start/data.length) + 1,
                                start   : data.start,
                                limit   : data.length
                            },
                            order : {
                                field : settings.aoColumns[data.order[0].column].data,
                                sort : data.order[0].dir.toUpperCase()
                            }
                        };
                    },
                    complete: function(response) {},
                }
            });
        }

        function getVehicleTuningListDataTable() { return $('#vehicle-tuning-list-datatable'); }
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
        .new-entity-button {
            margin: 10px;
            line-height: 10px;
        }

        .new-entity-button:hover {
            color: #fff !important;
        }
    </style>
</body>
</html>
