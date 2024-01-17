<?php
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

$language = $container->get('language');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $language::translate('Countries') ?> - <?= SITE_NAME ?> </title>
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
                                    <label for="vehicle-select"><?= $language::translate('Country Select') ?></label>
                                    <select class="form-control" id="country-select" name="vehicle_id" onchange="getCountryListData()"></select>
                                </div>
                            </form>
                        </div>
                        <a href="/admin/country/new" class="btn btn-outline-info btn-sm col-md-12 new-entity-button"><i class="ti-plus"></i> <?= $language::translate('Country') ?></a>
                        <a href="/admin/city/new" class="btn btn-outline-info btn-sm col-md-12 new-entity-button"><i class="ti-plus"></i> <?= $language::translate('City') ?></a>
                        <table id="country-list-datatable" class="table table-striped" style="width:100%"></table>
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

            $("#country-select").select2({
                ajax: {
                    url: '/ajax/admin/country/list-for-select',
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

            if (getCountryListDataTable().length > 0) getCountryListData();
        });

        function getCountryListData() {

            var vehicleTuningDataTable = $('#country-list-datatable').dataTable( {
                processing: true,
                serverSide: true,
                destroy : true,
                lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
                language : { url : "/ajax/datatable/language"},
                scrollX: true,
                columns: [
                    {
                        data : "id",
                        name: "id",
                        title : '<?= $language::translate('ID') ?>',
                        orderable: true,
                        className : "dt-center",
                        render : function (id) {
                            return id;
                        }
                    },
                    {
                        data : "country_id",
                        title : "<?= $language::translate('Country') ?>",
                        className : "dt-center",
                        orderable: false,
                        render : function (country_id, type, row) {
                            return row.country_name;
                        }
                    },
                    {
                        data : "name",
                        title : "<?= $language::translate('City') ?>",
                        className : "dt-center",
                        orderable: false,
                        render : function (name) {
                            return name;
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
                        render : function (id) {
                            var operationHtml = '<a class="btn btn-outline-info btn-sm operation-icons" href="/admin/city/detail/' + id + '"><i class="ti-pencil-alt"></i>';
                            return operationHtml;
                        }
                    }
                ],
                ajax: {
                    type: "POST",
                    url: "/ajax/admin/city/list-for-datatable",
                    data: function (data) {

                        var settings = $("#country-list-datatable").dataTable().fnSettings();
                        var filter = {};

                        filter.datatable_query = data.search.value;

                        if ($('#country-select').val()) {
                            filter.country_id = $('#country-select').val()
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

        function getCountryListDataTable() { return $('#country-list-datatable'); }
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
