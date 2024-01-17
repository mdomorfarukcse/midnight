﻿<?php
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

$language = $container->get('language');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $language::translate('Customer Group') ?> - <?= SITE_NAME ?> </title>
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
                            <a href="/admin/customer-group/new" class="btn btn-outline-info btn-sm col-md-12 new-entity-button"><i
                                        class="ti-plus"></i> <?= $language::translate('Customer Group') ?></a>
                            <table id="customer-group-list-datatable" class="table table-striped" style="width:100%"></table>
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

            if (getCustomerListDataTable().length > 0) getCustomerListData()
        });

        function getCustomerListData() {

            var customerDataTable = $('#customer-group-list-datatable').dataTable({
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
                        data: "name",
                        title: "<?= $language::translate('Name') ?>",
                        className: "dt-center",
                        render: function(name) {
                            return name;
                        }
                    },
                    {
                        data: "process_type",
                        title: "<?= $language::translate('Process type') ?>",
                        className: "dt-center",
                        render: function(process_type) {
                            return process_type;
                        }
                    },
                    {
                        data: "multiplier",
                        title: "<?= $language::translate('Multiplier') ?>",
                        className: "dt-center",
                        render: function(multiplier) {
                            return multiplier;
                        }
                    },
                    {
                        data: "status",
                        title: "<?= $language::translate('Status') ?>",
                        className: "dt-center",
                        render: function(status) {
                          if(status == 1){
                            return  'Active';
                          }else{
                            return 'Pasif';
                          }
                        }
                    },

                    {
                        data: "id",
                        title: "<?= $language::translate('Operation') ?>",
                        className: "dt-center",
                        render: function(id) {
                            var operationHtml =
                                '<a class="btn btn-outline-info btn-sm operation-icons" href="/admin/customer-group/detail/' +
                                id + '"><i class="ti-pencil-alt"></i>';
                            return operationHtml;
                        }
                    }
                ],
                ajax: {
                    type: "POST",
                    url: "/ajax/admin/customer/group-list-for-datatable",
                    data: function(data) {

                        var settings = $("#customer-group-list-datatable").dataTable().fnSettings();

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

        function getCustomerListDataTable() {
            return $('#customer-group-list-datatable');
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
