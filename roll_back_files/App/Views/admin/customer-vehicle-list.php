<?php
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

$language = $container->get('language');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $language::translate('Customer Vehicles') ?> - <?= SITE_NAME ?> </title>
    <?php include("css.php")?>
    <link href="<?= SITE_URL ?>\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\bootstrap-select\bootstrap-select.min.css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\table\datatable\datatables.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\assets\css\forms\theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\table\datatable\dt-global_style.css">
    <link rel="stylesheet" href="\assets/css/themify-icons.css">
    <link rel="stylesheet" href="\assets/css/ie7/ie7.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/colreorder/1.6.1/css/colReorder.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
     <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.1/css/fixedColumns.bootstrap.min.css">
     <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap.min.css">




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
                            <table id="customer-vehicle-list-datatable" class="table table-striped table-bordered nowrap " style="width:100% !important">
                            </table>
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
        <script src="https://cdn.datatables.net/colreorder/1.6.1/js/dataTables.colReorder.min.js"></script>
         <script src="https://cdn.datatables.net/fixedcolumns/4.2.1/js/dataTables.fixedColumns.min.js"></script>



         <script>
        $(function() {

            if (getCustomerVehicleListDataTable().length > 0) getCustomerVehicleListData()
        });

        function getCustomerVehicleListData() {

            var customerVehicleDataTable = $('#customer-vehicle-list-datatable').dataTable({
              order: [[0, 'desc']],

              processing: true,
             serverSide: true,
             searching: true,
             destroy : true,
             lengthMenu: [[6, 15, 30, 45], [6, 15, 30, 45]],
             scrollY:        true,
             scrollX:        true,
              paging:         true,
             fixedColumns:   {
                 left: 1,
                 right: 1
             },
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

                colReorder: true,


                // Samet Row Color Function
                rowCallback: function( row, data, index ) {

               if (data.status === 'awaiting_payment')
                  $('span', row).css('color', '#ffffff');
               if (data.status === 'awaiting_payment')
                  $('span', row).css('border', '1px solid #e7515a');
               if (data.status === 'awaiting_payment')
                  $('span', row).css('background-color', '#e7515a');

               if (data.status === 'completed')
                  $('span', row).css('color', '#ffffff');
               if (data.status === 'completed')
                  $('span', row).css('border', '1px solid #00BC9C');
               if (data.status === 'completed')
                  $('span', row).css('background-color', '#00BC9C');
               if (data.status === 'pending')
                  $('span', row).css('color', '#ffffff');
               if (data.status === 'pending')
                  $('span', row).css('border', '1px solid #2196F3');
               if (data.status === 'pending')
                  $('span', row).css('background-color', '#2196F3');

               if (data.status === 'process')
                  $('span', row).css('color', '#ffffff');
               if (data.status === 'process')
                  $('span', row).css('border', '1px solid #e2a03f');
               if (data.status === 'process')
                  $('span', row).css('background-color', '#e2a03f');
               if (data.status === 'cancel')
                  $('span', row).css('color', '#ffffff');
               if (data.status === 'cancel')
                  $('span', row).css('border', '1px solid #e7515a');
               if (data.status === 'cancel')
                  $('span', row).css('background-color', '#e7515a');
                },

                columns: [
                {
                        data: "id",
                        name: "id",
                        title: '<?= $language::translate('ID') ?>',
                        orderable: true,
                        visible: true,
                        className: "dt-center",
                        render: function(id) {
                            return id;
                        }
                    },
                    {
                        data: "vehicle_id",
                        title: "<?= $language::translate('Vehicle') ?>",
                        className: "dt-center",
                        render: function(vehicle_id, type, row) {
                            return row.vehicle_full_name;
                        }
                    },
					{
                        data: "vehicle_registration",
                        title: "<?= $language::translate('Vehicle Registration') ?>",
                        className: "dt-center",
                        render: function(vehicle_registration) {
                            return vehicle_registration;
                        }
                    },
                    {
                        data: "file_time",
                        title: "<?= $language::translate('File Time') ?>",
                        className: "dt-center",
                        render: function(file_time) {
                          return '<span class="badge badge-' + translate(status) + '"> <i class="ti-time"> </i>  ' + translate(file_time) + '</span>';


                        }
                    },
                    {
                        data: "status",
                        title: "<?= $language::translate('Status') ?>",
                        className: "dt-center",
                        render : function (status) {
                          return '<span class="btn  btn-sm">' + translate(status) + '</span>';
                        }
                    },
                    {
                        data: "tuning",
                        title: "<?= $language::translate('Tuning') ?>",
						visible: false,
                        className: "dt-center",
                        render: function(tuning, type, row) {
                            return row.tuning_name;
                        }
                    },
                    

                    {
                        data: "options",
                        title: "<?= $language::translate('Options') ?>",
						visible: false,
                        width: "200px",
                        className: "dt-center",
                        render: function(options) {
                            return options;
                        }
                    },
                    {
                        data: "ecu",
                        title: "<?= $language::translate('Ecu') ?>",
						visible: false,
                        className: "dt-center",
                        render: function(ecu) {
                            return ecu;
                        }
                    },
                    {
                        data: "reading_device",
                        title: "<?= $language::translate('Reading Device') ?>",
						visible: false,
                        className: "dt-center",
                        render: function(reading_device) {
                            return reading_device;
                        }
                    },
                    {
                        data: "software",
                        title: "<?= $language::translate('Software') ?>",
						visible: false,
                        className: "dt-center",
                         render: function(method) {
                            return method;
                        }
                    },
                    {
                        data: "equipment",
                        title: "<?= $language::translate('Equipment') ?>",
						visible: false,
                        className: "dt-center",
                         render: function(method) {
                            return method;
                        }
                    },
                    {
                        data: "master_slave",
                        title: "<?= $language::translate('Master Slave') ?>",
						visible: false,
                        className: "dt-center",
                        render: function(master_slave) {
                            return master_slave;
                        }
                    },
					{
                        data: "customer_id",
                        title: "<?= $language::translate('Customers') ?>",
                        className: "dt-center",
                        render: function(customer_id, type, row) {
                            return row.customer_full_name;
                        }
                    },
                    {
                        data: "total_credit",
                        title: "<?= $language::translate('Total Credit') ?>",
						visible: false,
                        className: "dt-center",
                        render: function(total_credit) {
                            return total_credit;
                        }
                    },
                    {
                        data: "id",
                        title: "<?= $language::translate('Operation') ?>",
                        className: "dt-center",
                        render: function(id) {
                            var operationHtml =
                                '<a class="btn btn-info btn-sm operation-icons" href="/admin/customer/vehicle/detail/' +
                                id + '"><i class="ti-search"></i>' +
                                '<a class="btn btn-danger btn-sm operation-icons" id="modelabi" href="/admin/customer/vehicle/delete/' +
                                id + '"><i class="ti-trash"></i>';
                            return operationHtml;
                        }
                    }
                ],
                ajax: {
                    type: "POST",
                    url: "/ajax/admin/customer/vehicle/list-for-datatable",
                    data: function(data) {

                        var settings = $("#customer-vehicle-list-datatable").dataTable().fnSettings();

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

        function getCustomerVehicleListDataTable() {
            return $('#customer-vehicle-list-datatable');
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

        .btn:not(:disabled):not(.disabled) {
    cursor: pointer;
 }

 table.dataTable tbody tr>.dtfc-fixed-left, table.dataTable tbody tr>.dtfc-fixed-right {
    z-index: 1;
	 
}

table.dataTable.table-striped>tbody>tr.odd>* {
    background: #fafafa;
}

div.dataTables_scrollBody {
    border-left: none !important;
}

.dataTable.table-striped.table > thead > tr > th {
    background: transparent;
    border-top: none !important;
   border-bottom: none !important;
}

div.dataTables_scrollFootInner table.table-bordered tr th:first-child, div.dataTables_scrollHeadInner table.table-bordered tr th:first-child {
    border-left: none !important;
}

        </style>

</body>

</html>
