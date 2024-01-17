<?php
use Pemm\Model\Setting;
use Pemm\Model\Order as OrderModel;

global $container;

$language = $container->get('language');
$setting = (new Setting())->find(1);


		if (isset($_GET['delete']) && !empty($deleteid = $_GET['delete'])) {

        $OrderModel = new OrderModel();
        $customerOrders = $OrderModel->find($deleteid);
        if (!empty($customerOrders)) {
            $customerOrders
                ->delete($customerOrders->getNumber());
        }

        header('location: /admin/customer/order/list');
		}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $language::translate('Purchases') ?> - <?= SITE_NAME ?> </title>
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
                        <table data-order='[[ 0, "desc" ]]' id="order-list-datatable" class="table table-striped" style="width:100%"></table>
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

            if (getOrderListDataTable().length > 0) getOrderListData()
        });

        function getOrderListData() {

            var ordersDataTable = $('#order-list-datatable').dataTable( {
                processing: true,
                serverSide: true,
                destroy : true,
                lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
                language : { url : "/ajax/datatable/language"},
                scrollX: true,
                order: [[ 0, "desc" ]],
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
                        data : "customer_id",
                        title : "<?= $language::translate('Customer') ?>",
                        className : "dt-center",
                        render : function (customer_id, type, row) {
                            return row.customer_full_name;
                        }
                    },
                    {
                        data : "items_total",
                        title : "<?= $language::translate('Items Total') ?>",
                        className : "dt-center",
                        render : function (items_total) {
                            return items_total;
                        }
                    },
                    {
                        data : "total",
                        title : "<?= $language::translate('Total') ?>",
                        className : "dt-center",
                        render : function (total) {
                            return total;
                        }
                    },
                    {
                        data : "tax_amount",
                        title : "<?= $language::translate('Tax Amount') ?>",
                        className : "dt-center",
                        render : function (tax_amount, type, row) {
                            return tax_amount;
                        }
                    },
                    {
                        data : "currency",
                        title : "<?= $language::translate('Currency') ?>",
                        width : "200px",
                        className : "dt-center",
                        render : function (currency) {
                            return currency;
                        }
                    },
                    {
                        data : "total_credit",
                        title : "<?= $language::translate('Total Credit') ?>",
                        className : "dt-center",
                        render : function (total_credit) {
                            return total_credit;
                        }
                    },
                    {
                        data : "state",
                        title : "<?= $language::translate('State') ?>",
                        className : "dt-center",
                        render : function (state) {
                            return state;
                        }
                    },
                    {
                        data : "payment_type",
                        title : "<?= $language::translate('Payment Type') ?>",
                        className : "dt-center",
                        render : function (payment_type) {
                            return payment_type;
                        }
                    },
                    {
                        data : "payment_status",
                        title : "<?= $language::translate('Payment Status') ?>",
                        className : "dt-center",
                        render : function (payment_status) {
                            return payment_status;
                        }
                    },
                    {
                        data : "country",
                        title : "<?= $language::translate('Country') ?>",
                        className : "dt-center",
                        render : function (country) {
                            return country;
                        }
                    },
                    {
                        data : "id",
                        title : "<?= $language::translate('Operation') ?>",
                        className : "dt-center",
                        render : function (id, type, row) {
                            var operationHtml = '<a class="btn btn-outline-info btn-sm operation-icons" href="/admin/order/detail/' + id + '"><i class="ti-pencil-alt"></i>';
							//console.log(row.payment_status);
							if (row.payment_status == 'pending') {
							operationHtml += '<a class="btn btn-outline-danger btn-sm operation-icons" href="/admin/customer/order/list?delete=' + id + '"><i class="ti-trash"></i>';
							}
							
                            return operationHtml;
                        }
                    }
                ],
                ajax: {
                    type: "POST",
                    url: "/ajax/admin/customer/order/list-for-datatable",
                    data: function (data) {

                        var settings = $("#order-list-datatable").dataTable().fnSettings();

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

        function getOrderListDataTable() { return $('#order-list-datatable'); }
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
