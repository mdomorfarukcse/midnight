<?php

use Pemm\Core\Container;
use Pemm\Core\Language;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

/* @var Language $language*/
$language = $container->get('language');

/* @var Session $session*/
$session = $container->get('session');

?>
<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('P Codes') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
	<link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="\plugins\bootstrap-select\bootstrap-select.min.css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\datatables.css">
    <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\dt-global_style.css">
	<link rel="stylesheet" href="\assets/css/themify-icons.css">
 <link rel="stylesheet" href="\assets/css/ie7/ie7.css">


  </head>
<body>
   	<?php include("ust2.php")?>
	  
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
	<?php include("ust.php")?>
         <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="row layout-top-spacing">
                    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                        <div class="widget-content widget-content-area br-6">
                            <table id="p-codes-list-datatable" class="table table-striped" style="width:100%"></table>
                        </div>
                    </div>
                </div>
            </div>
			  <?php include("alt.php")?>
    </div>
     <?php include("js.php")?>
	     <script src="\plugins\bootstrap-select\bootstrap-select.min.js"></script>
     <!-- BEGIN PAGE LEVEL SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="\plugins\table\datatable\datatables.js"></script>
        <script>

            var pCodesListDatatable;

            $(function () {

                if (getPCodesListDataTable().length > 0) getPCodesListData()
            });

            function getPCodesListData() {

                $('#p-codes-list-datatable').dataTable( {
                    processing: true,
                    serverSide: true,
                    destroy : true,
                    pageLength: 20,
                    lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
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
                    ajax: {
                        type: "POST",
                        url: "/ajax/p-codes/list-for-datatable",
                        data: function (data) {

                            var settings = $("#p-codes-list-datatable").dataTable().fnSettings();
                            var order = {};

                            $.each( data.order, function (k,v){
                                order = v;
                            })

                            switch (order.column) {
                                case 0:
                                    order.column = 'code';
                                    break;
                                case 1:
                                    order.column = 'description';
                                    break;
                            }

                            return {
                                filter : {
                                    query : data.search.value,
                                    is_active : 1
                                },
                                pagination : {
                                    draw    : settings.iDraw,
                                    page    : (data.start/data.length) + 1,
                                    start   : data.start,
                                    limit   : data.length
                                },
                                order : {
                                    field : order.column,
                                    sort : order.dir.toUpperCase()
                                }
                            };
                        },
                        complete: function(response) {},
                    },
                    columns: [
                        {
                            data : "code",
                            title : '<?= $language::translate('Code') ?>',
                            orderable: true,
                            className : "dt-center",
                            render : function (code) {
                                return code;
                            }
                        },
                        {
                            data : "description",
                            title : "<?= $language::translate('Description') ?>",
                            className : "dt-center",
                            render : function (description) {
                                return description;
                            }
                        }
                    ]
                });
            }

            function getPCodesListDataTable() { return $('#p-codes-list-datatable'); }
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
        </style>
	</body>
</html>
