<?php
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

$language = $container->get('language');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $language::translate('Invoices') ?> - <?= SITE_NAME ?> </title>
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
                        <table id="invoice-list-datatable" class="table table-striped" style="width:100%"></table>
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

        $(function () {
            if (getInvoiceListDataTable().length > 0) getInvoiceListData()
        });

        function getInvoiceListData() {

            var customerInvoiceDataTable = $('#invoice-list-datatable').dataTable( {
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
                        data : "number",
                        title : "<?= $language::translate('Invoice Number') ?>",
                        className : "dt-center",
                        orderable: true,
                        render : function (number) {
                            return number;
                        }
                    },
                    {
                        data : "customer_id",
                        title : "<?= $language::translate('Customer') ?>",
                        className : "dt-center",
                        orderable: true,
                        render : function (customer_id, type, row) {
                            return row.customer_full_name;
                        }
                    },
                    {
                        data : "order_id",
                        title : "<?= $language::translate('Order') ?>",
                        className : "dt-center",
                        orderable: true,
                        render : function (order_id, type, row) {
                            return row.order_number;
                        }
                    },

                    {
                        data : "status",
                        title : "<?= $language::translate('Status') ?>",
                        className : "dt-center",
                        orderable: true,
                        render : function (status) {
                            return status;
                        }
                    },
                    {
                        data : "id",
                        title : "<?= $language::translate('Operation') ?>",
                        className : "dt-center",
                        render : function (id) {
                            var operationHtml = '<a class="btn btn-outline-info btn-sm operation-icons" href="/admin/invoice/detail/' + id + '"><i class="ti-pencil-alt"></i>';
                            return operationHtml;
                        }
                    }
                ],
                ajax: {
                    type: "POST",
                    url: "/ajax/admin/customer/invoice/list-for-datatable",
                    data: function (data) {

                        var settings = $("#invoice-list-datatable").dataTable().fnSettings();

                        return {
                            filter : {
                                datatable_query : data.search.value
                            },
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

        function getInvoiceListDataTable() { return $('#invoice-list-datatable'); }
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
