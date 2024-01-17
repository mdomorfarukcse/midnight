<?php
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

$language = $container->get('language');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $language::translate('Categories') ?> - <?= SITE_NAME ?> </title>
    <?php include("css.php")?>
    <link href="<?= SITE_URL ?>\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\bootstrap-select\bootstrap-select.min.css">
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
                    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                        <div class="widget-content widget-content-area br-6">
                            <a href="/admin/vehicle/category/new"
                                class="btn btn-outline-info btn-sm col-md-12 new-entity-button"><i class="ti-plus"></i>
                                <?= $language::translate('Category') ?></a>
                            <table id="vehicle-list-datatable" class="table table-striped"></table>
                        </div>
                    </div>
                </div>
            </div>
            <?php include("alt.php")?>
        </div>
        <?php include("js.php")?>
        <script src="<?= SITE_URL ?>\plugins\bootstrap-select\bootstrap-select.min.js"></script>
        <!-- BEGIN PAGE LEVEL SCRIPTS -->

        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="<?= SITE_URL ?>\plugins\table\datatable\datatables.js"></script>
        <script>
        $(function() {
            if (getVehicleListDataTable().length > 0) getVehicleListData()
        });

        function getVehicleListData() {

            var vehicleDataTable = $('#vehicle-list-datatable').dataTable({
              processing: true,
             serverSide: true,
             searching: true,
             destroy : true,
             lengthMenu: [[10, 15, 30, 45], [10, 15, 30, 45]],
             scrollX: true,
             language : {
                 sDecimal:        ",",
                 sEmptyTable:     "<?= $language::translate('No data available in the table') ?>",
                 sInfo:           "<?= $language::translate('Showing records from _TOTAL_ to _START_ to _END_') ?>",
                 sInfoEmpty:      "<?= $language::translate('No Records Found') ?>",
                 sInfoFiltered:   "<?= $language::translate('(found in _MAX_ record)') ?>",
                 sInfoPostFix:    "",
                 sInfoThousands:  ".",
                 sLengthMenu:     "<?= $language::translate('Show _MENU_ record on page') ?>",
                 sLoadingRecords: "<?= $language::translate('Loading...') ?>",
                 sProcessing:     "<?= $language::translate('Processing...') ?>",
                 sSearch:         "<?= $language::translate('Search') ?>",
                 sZeroRecords:    "<?= $language::translate('No matching records found') ?>",
                 oPaginate: {
                     sFirst:    "<?= $language::translate('First') ?>",
                     sLast:     "<?= $language::translate('Last') ?>",
                     sNext:     "<?= $language::translate('sNext') ?>",
                     sPrevious: "<?= $language::translate('sPrevious') ?>"
                 }
             },
                scrollX: true,
                columns: [{
                        data: "id",
                        name: "id",
                        title: '<?= $language::translate('ID') ?>',
                        orderable: true,
                        className: "dt-center",
                        render: function(id) {
                            return id;
                        }
                    },
                    {
                        data: "category_icon",
                        title: "<?= $language::translate('Category') ?>",
                        className: "dt-center",
                        render: function(category_icon) {
                            return '<img  src="/icon/category/' + category_icon +
                                '" width="50">';
                        }
                    },
                    {
                        data : "type_name",
                        title : "<?= $language::translate('Name') ?>",
                        className : "dt-center",
                        render : function (type_name) {
                            return type_name;
                        }
                    },

                    {
                        data: "is_active",
                        title: "<?= $language::translate('Status') ?>",
                        className: "dt-center",
                        render: function(is_active) {
                            return is_active == 1 ? 'Active' : 'Passive';
                        }
                    },
                    {
                        data: "id",
                        title: "<?= $language::translate('Operation') ?>",
                        className: "dt-center",
                        render: function(id) {
                            var operationHtml =
                                '<a class="btn btn-outline-info btn-sm operation-icons" href="/admin/vehicle/category/detail/' +
                                id + '"><i class="ti-pencil-alt"></i>';
                            return operationHtml;
                        }
                    }
                ],
                ajax: {
                    type: "POST",
                    url: "/ajax/admin/category/list-for-datatable",
                    data: function(data) {

                        var settings = $("#vehicle-list-datatable").dataTable().fnSettings();

                        return {
                            filter: {
                                datatable_query: data.search.value
                            },
                            pagination: {
                                draw: settings.iDraw,
                                page: (data.start / data.length) + 1,
                                start: data.start,
                                limit: data.length
                            },
                            order: {
                                field: settings.aoColumns[data.order[0].column].data,
                                sort: data.order[0].dir.toUpperCase()
                            }
                        };
                    },
                    complete: function(response) {},
                }
            });
        }

        function getVehicleListDataTable() {
            return $('#vehicle-list-datatable');
        }
        </script>
        <style>
        div.dataTables_wrapper div.dataTables_info {
            display: none;
        }

        .dataTables_length,
        .dataTables_filter {
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
