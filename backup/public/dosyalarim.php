<?php

use Pemm\Core\Container;
use Pemm\Model\Customer;
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

/* @var Customer $customer */
$customer = $container->get('customer');

$language = $container->get('language');

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('My Files') ?> - <?= SITE_NAME ?> </title>
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
                            <table id="customer-vehicle-list-datatable" class="table table-striped" style="width:100%"></table>
                        </div>
                    </div>
                </div>
            </div>
			  <?php include("alt.php")?>
    </div>
     <?php include("js.php")?>
	     <script src="\plugins\bootstrap-select\bootstrap-select.min.js"></script>
     <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="\plugins\table\datatable\datatables.js"></script>
    <script>

        var customerVehicleListDatatable;

        $(function () {

            if (getCustomerVehicleListDataTable().length > 0) getCustomerVehicleListData()
        });

        function getCustomerVehicleListData() {

            $('#customer-vehicle-list-datatable').dataTable( {
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
                ajax: {
                    type: "POST",
                    url: "/ajax/customer-vehicle/list-for-datatable",
                    data: function (data) {

                        var settings = $("#customer-vehicle-list-datatable").dataTable().fnSettings();
                        var order = {};

                        $.each( data.order, function (k,v){
                            order = v;
                        })

                        switch (order.column) {
                            case 0:
                                order.column = 'id';
                                break;
                            case 1:
                                order.column = 'vehicle_id';
                                break;
                            case 2:
                                order.column = 'ecu';
                                break;
                            case 3:
                                order.column = 'tuning';
                                break;
                            case 4:
                                order.column = 'total_credit';
                                break;
                            case 5:
                                order.column = 'status';
                                break;
                        }

                        return {
                            filter : {
                                datatable_query : data.search.value,
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
                order: [[ 0, "desc" ]],
                // Samet Row Color Function
                rowCallback: function( row, data, index ) {

                 if (data.status === 'awaiting_payment')
                    $('span', row).css('color', '#e67980');
                 if (data.status === 'awaiting_payment')
                    $('span', row).css('border', '1px solid #2c1c2b');
                 if (data.status === 'awaiting_payment')
                    $('span', row).css('background-color', '#2c1c2b');

                 if (data.status === 'completed')
                    $('span', row).css('color', '#4dc187');
                 if (data.status === 'completed')
                    $('span', row).css('border', '1px solid #0c272b');
                 if (data.status === 'completed')
                    $('span', row).css('background-color', '#0c272b');
                 if (data.status === 'pending')
                    $('span', row).css('color', '#abacb2');
                 if (data.status === 'pending')
                    $('span', row).css('border', '1px solid #181e2e');
                 if (data.status === 'pending')
                    $('span', row).css('background-color', '#181e2e');

                 if (data.status === 'process')
                    $('span', row).css('color', '#eab764');
                 if (data.status === 'process')
                    $('span', row).css('border', '1px solid #282625');
                 if (data.status === 'process')
                    $('span', row).css('background-color', '#282625');
                 if (data.status === 'cancel')
                    $('span', row).css('color', '#e67980');
                 if (data.status === 'cancel')
                    $('span', row).css('border', '1px solid #2c1c2b');
                 if (data.status === 'cancel')
                    $('span', row).css('background-color', '#2c1c2b');

                },
                columns: [

                  {
                      data : "date",
                      title : '<?= $language::translate('Date') ?>',
                      orderable: true,
                      className : "dt-center",
                      render : function (date) {
                          return date;
                      }
                  },

                    {
                        data : "vehicle",
                        title : "<?= $language::translate('Vehicle') ?>",
                        className : "dt-center",
                        render : function (vehicle, type, row) {
                            return '<img width="20" src="' + row.brand_image + '"> '  + vehicle;
                        }
                    },

                    {
                        data : "tuning",
                        title : "<?= $language::translate('Operation') ?>",
                        render : function (tuning) {
                            return '<a class="shadow-none badge badge-dark ">' + tuning + '</a>';
                        }
                    },

                    {
                        data : "total_credit",
                        title : "<?= $language::translate('Credit') ?>",
                        render : function (total_credit) {
                            return total_credit + ' CRD';
                        }
                    },
                    {
                        data : "status",
                        title : "<?= $language::translate('Status') ?>",
                        render : function (status) {
                            return '<span class="badge badge-' + translate(status) + '">' + translate(status) + '</span>';
                        }
                    },


                    {
                        data : "status",
                        title : "<?= $language::translate('Details') ?>",
                        render : function (data, type, row) {
                            return '<a href="/panel/my-files/detail/'+row.id+'" data-id="'+row.id+'" class="btn btn-sq badge-success detail-btn"><i class="ti-eye"></i> </a> ';
                        }
                    }
                ]
            });
        }

        function getCustomerVehicleListDataTable() { return $('#customer-vehicle-list-datatable'); }


        function detailVehicle(id){

            var customerCreditTotal = <?= $customer->getCredit() ?>

            $('#ecu_file2').hide();
            $('#ecu_file').hide();
            $('#id_file').hide();
            $('#log_file').hide();
            $('#dyno_file').hide();

            $.get("/ajax/customer-vehicle/detail?vehicle_id="+id, function(data, status){

                $('#vehicle_name').html(data.vehicle);
                $('#vehicle_model').html(data.model);
                $('#kilometer').html(data.kilometer);
                $('#power').html(data.power);
                $('#torque').html(data.torque);
                $('#ecu').html(data.ecu);
                $('#master_slave').html(data.master_slave);
                $('#plaka').html(data.plaka);
                $('#reading_type').html(data.reading_type);
                $('#reading_device').html(data.reading_device);
                $('#notes').html(data.notes);
                $('#admin_note').html(data.admin_note);
                $('#total_credit').html(data.credit);
                $('#tuning_name').html(data.tuning.name);
                $('#tuning_credit').html(data.tuning.credit);
                $('#tuning_options').html(data.opt);
                $('#tuning_options_credit').html(data.credit - data.tuning.credit);

                if (data.status === "awaiting_payment") {
                    $('#pay').attr("href", "/panel/customer-vehicle/" + id + "/pay");
                    $('#pay').removeClass("d-none");
                    if (customerCreditTotal <= data.credit) {
                        $('#buy-credit').attr("href", "/panel/buy-credit");
                        $('#buy-credit').removeClass("d-none");
                    } else {
                        $('#pay').attr("href", "/panel/customer-vehicle/" + id + "/pay");
                        $('#pay').removeClass("d-none");
                    }
                } else {
                    $('#pay').addClass("d-none");
                    $('#buy-credit').addClass("d-none");
                }

                if(data.file_ecu !== "") {
                    $('#ecu_file2').attr("href", "/panel/file/download?id=" +
                        id + "&file=ecu");
                    $('#ecu_file2').show();
                }
                if(data.ecu !== "") {
                    $('#ecu_file').attr("href", "/panel/file/download?id=" +
                        id + "&file=ecu");
                    $('#ecu_file').show();
                }
                if(data.file_log !== "") {
                    $('#log_file').attr("href", "/panel/file/download?id=" +
                        id + "&file=log");
                    $('#log_file').show();
                }
                if(data.file_dyno !== "") {
                    $('#dyno_file').attr("href", "/panel/file/download?id=" +
                        id + "&file=dyno");
                    $('#dyno_file').show();
                }
                if(data.file_dyno !== "") {
                    $('#id_file').attr("href", "/panel/file/download?id=" +
                        id + "&file=id");
                    $('#id_file').show();
                }

                $('#exampleModal').modal();


            });
        }

        function fileDetails(id){

            $('#ecu_file_file').hide();
            $('#log_file_file').hide();
            $('#dyno_file_file').hide();
            $('#id_file_file').hide();
            $('#original_ecu_file').hide();
            $('#original_log_file').hide();
            $('#original_id_file').hide();
            $('#original_dyno_file').hide();

            $.get("/ajax/customer-vehicle/detail?vehicle_id="+id, function(data, status){


                // System Files
                if(data.file_ecu !== "") {
                    $('#ecu_file_file').attr("href", "/panel/file/download?id=" +
                        id + "&file=ecu");
                    $('#ecu_file_file').show();
                }
                if(data.file_log !== "") {
                    $('#log_file_file').attr("href", "/panel/file/download?id=" +
                        id + "&file=log");
                    $('#log_file_file').show();
                }

                if(data.file_dyno !== "") {
                    $('#dyno_file_file').attr("href", "/panel/file/download?id=" +
                        id + "&file=dyno");
                    $('#dyno_file_file').show();
                }

                // Original Files

                if (data.original_ecu !== "") {
                    $('#original_ecu_file').attr("href", "/panel/file/download?id=" +
                        id + "&file=original_ecu");
                    $('#original_ecu_file').show();
                }

                if (data.original_log !== "") {
                    $('#original_log_file').attr("href", "/panel/file/download?id=" +
                        id + "&file=original_log");
                    $('#original_log_file').show();
                }

                if (data.original_dyno !== "") {
                    $('#original_dyno_file').attr("href", "/panel/file/download?id=" +
                        id + "&file=original_dyno");
                    $('#original_dyno_file').show();
                }

                $('#fileModal').modal();
            });
        }

    </script>
	<style>
	div.dataTables_wrapper div.dataTables_info {
        display: none;
    }
    .dataTables_length {
        margin: 20px;
    }

    .dataTables_paginate {
        padding: 20px;
    }
    .btn-dark {
    color: #fff !important;
    background-color: #1f1f2d;
    border-color: #1f1f2d;
    box-shadow: 0 10px 20px -10px #1f1f2d;
}
.tabloduzelt{
  padding: 10px 21px 10px 2px;
}
    </style>

<div class="modal fade bd-example-modal-xl " id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl " role="document">
    <div class="modal-content">
      <div style="background: #1f1f2d;" class="modal-header">
        <h5 style="color:#fff;" class="modal-title" id="exampleModalLabel"> <?= $language::translate('File Details') ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span style="color: #fff;" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
        <div class="row">
       <div class="col-md-6">

<div class="card" style="margin-bottom:15px;" >
   <div class="card-body">
        <h5 class="card-title"><?= $language::translate('Modification Requests') ?></h5>
        <table class="table table-striped table-responsive">

          <tbody>
            <tr>
               <td  class="tabloduzelt" id="tuning_name" ></td>
              <td  class="tabloduzelt"  id="tuning_credit"></td>
             </tr>
            <tr>
               <td style="white-space: pre-line"  class="tabloduzelt" id="tuning_options"></td>
               <td  class="tabloduzelt"  id="tuning_options_credit"></td>
             </tr>
            <tr>
               <td class="tabloduzelt" style="font-weight:700" ><?= $language::translate('Total Credit') ?>: </td>
              <td class="tabloduzelt" id="total_credit" style="font-weight:700"></td>
             </tr>
          </tbody>
        </table>
    </div>
</div>
<div class="card" style="display: none;">
    <div class="card-body">
    <h5 class="card-title"><?= $language::translate('File Transactions') ?></h5>
        <table class="table table-striped table-responsive">
          <thead>
             <tr>
               <th style="background: rgb(255 255 255 / 74%);" scope="col"><?= $language::translate('Credit') ?></th>
               <th style="background: rgb(255 255 255 / 74%);" scope="col"><?= $language::translate('Details') ?></th>
               <th style="background: none" scope="col"><?= $language::translate('Date') ?></th>
              </tr>
           </thead>
          <tbody>
            <tr >
               <td  class="tabloduzelt">-2.00</td>
                <td  class="tabloduzelt">Stage 1</td>
              <td  class="tabloduzelt">19 Mar 2022</td>
             </tr>
            <tr >
              <td  class="tabloduzelt" >-1.00</td>
             <td  class="tabloduzelt"  >Pop & Bang</td>
             <td  class="tabloduzelt"  >19 Mar 2022</td>
             </tr>
          </tbody>
        </table>
    </div>
</div>



</div>
       <div class="col-md-6">

         <div class="card" >
            <div class="card-body">
             <h5 class="card-title"><?= $language::translate('File Details') ?></h5>
             <table class="table table-striped table-responsive">

               <tbody>
                 <tr>
                    <td  class="tabloduzelt"><?= $language::translate('Vehicle') ?>: </td>
                   <td  style="white-space: pre-line;" class="tabloduzelt" id="vehicle_name"></td>
                  </tr>
                 <tr>
                    <td  class="tabloduzelt" ><?= $language::translate('Model') ?>:</td>
                   <td  class="tabloduzelt"  id="vehicle_model"></td>
                  </tr>
                 <tr>
                    <td  class="tabloduzelt" ><?= $language::translate('Kilometer') ?>:</td>
                   <td  class="tabloduzelt" id="kilometer"></td>
                  </tr>
                 <tr>
                    <td  class="tabloduzelt" ><?= $language::translate('Power') ?>:</td>
                   <td  class="tabloduzelt" id="power"></td>
                  </tr>
                 <tr>
                    <td  class="tabloduzelt" ><?= $language::translate('Torque') ?>:</td>
                   <td  class="tabloduzelt" id="torque"></td>
                  </tr>
                 <tr>
                    <td  class="tabloduzelt" ><?= $language::translate('Ecu') ?>:</td>
                   <td  style="white-space: pre-line" class="tabloduzelt"  id="ecu"></td>
                  </tr>
                 <tr>
                    <td  class="tabloduzelt" ><?= $language::translate('Master / Slave') ?>:</td>
                   <td  class="tabloduzelt" id="master_slave"></td>
                  </tr>
                  <tr>
                     <td  class="tabloduzelt" ><?= $language::translate('Vehicle Registration') ?>:</td>
                    <td  class="tabloduzelt" id="plaka"></td>
                   </tr>
                 <tr>
                 <tr>
                    <td  class="tabloduzelt" ><?= $language::translate('Reading Type') ?>:</td>
                   <td  class="tabloduzelt" id="reading_type"></td>
                  </tr>
                 <tr>
                 <tr>
                    <td  class="tabloduzelt" ><?= $language::translate('Reading Device') ?>:</td>
                   <td  class="tabloduzelt" id="reading_device"></td>
                  </tr>
                 <tr>
                 <tr style="display: none;">
                    <td  style="" ><?= $language::translate('File') ?>:</td>
                   <td  class="tabloduzelt"  ><a href=""><span style="  padding: 0 23px;  line-height: 36px; border-radius: 0;" class="badge badge-primary badge-pill"><i class="ti-upload"> </i> <?= $language::translate('Your uploaded file') ?>  </span> </a></td>
                  </tr>
                 <tr>
                    <td  class="tabloduzelt" ><?= $language::translate('Notes') ?>:</td>
                   <td  class="tabloduzelt" id="notes"></td>
                  </tr>
                 <tr>
                     <td  class="tabloduzelt" ><?= $language::translate('Admin Note') ?>:</td>
                     <td  class="tabloduzelt" id="admin_note"></td>
                 </tr>
               </tbody>
             </table>
         </div>
         </div>

       </div>
     </div>
      </div>
    </div>
      <div class="modal-footer">
          <a href="#" id="buy-credit" type="button" class="btn btn-success d-none"> <?= $language::translate('Buy Credit') ?></a>
          <a href="#" id="pay" type="button" class="btn btn-success d-none"> <?= $language::translate('Pay') ?></a>
        <button type="button" class="btn btn-primary" data-dismiss="modal"> <?= $language::translate('Close') ?></button>
       </div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-xl" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl " role="document">
        <div class="modal-content">
            <div style="background: #1f1f2d;" class="modal-header">
                <h5 style="color:#fff;" class="modal-title" id="exampleModalLabel"> <?= $language::translate('File Details') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span style="color: #fff;" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="card" style="margin-bottom:15px;" >
                                <div class="card-body">
                                  <div class="container">
<div class="row">
  <div class="col-md-12 col-lg-12">
    <blockquote style=" padding: 10px 20px 3px 14px;" class="blockquote media-object">
     <h5 class="text-center"><?= $language::translate('Tuning Files') ?>   </h5>
    </blockquote>
  </div>
<div class=" col-md-4 col-lg-4">
<a  style="width: 100%;"  id="ecu_file_file"  href="#"  class="btn btn-outline-info mb-2"><i class="ti-download"> </i> Ecu <?= $language::translate('File') ?></a>
</div>
<div class=" col-md-4 col-lg-4">
<a  style="width: 100%;"  id="log_file_file" href="#"  class="btn btn-outline-success mb-2"><i class="ti-download"> </i> Log <?= $language::translate('File') ?></a>
</div>
<div class=" col-md-4 col-lg-4">
<a  style="width: 100%;" id="dyno_file_file"  href="#"  class="btn btn-outline-warning mb-2"><i class="ti-download"> </i> Dyno <?= $language::translate('File') ?></a>
</div>

<div class="col-md-12 col-lg-12">
  <blockquote style="    margin-top: 33px;
 padding: 10px 20px 3px 14px;" class="blockquote media-object">
   <h5 class="text-center"><?= $language::translate('Original Files') ?>  </h5>
  </blockquote>
</div>
<div class=" col-md-4 col-lg-4">
<a  style="width: 100%;"  id="original_ecu_file"  href="#"  class="btn btn-outline-info mb-2"><i class="ti-download"> </i> Ecu <?= $language::translate('File') ?></a>
</div>
<div class=" col-md-4 col-lg-4">
<a  style="width: 100%;"  id="original_log_file" href="#"  class="btn btn-outline-success mb-2"><i class="ti-download"> </i> Log <?= $language::translate('File') ?></a>
</div>
<div class=" col-md-4 col-lg-4">
<a  style="width: 100%;" id="original_dyno_file"  href="#"  class="btn btn-outline-warning mb-2"><i class="ti-download"> </i> Dyno <?= $language::translate('File') ?></a>
</div>
</div>

                                 </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"> <?= $language::translate('Close') ?></button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
