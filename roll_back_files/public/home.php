<?php
use Pemm\Core\Container;
use Pemm\Model\Category;
use Pemm\Model\Vehicle;
use Pemm\Model\Order;
use Pemm\Model\Customer;
use Pemm\Model\Helper;
use Pemm\Model\CustomerVehicle;
use Pemm\Model\Setting;

global $container;

/* @var Customer $customer */
$customer = $container->get('customer');
$setting = (new Setting())->find(1);
$language = $container->get('language');

$categoryInstance = new Category();
$categoryInstance->getCategories();
$categories = $categoryInstance->getCategoryByParentId(0);

$awaitingPaymentAlert = (new CustomerVehicle())->findBy(['filter' => ['status' => 'awaiting_payment']]);

$pendingVehicles = (new CustomerVehicle())->findBy([
    'filter' => ['deleted' => 0, 'status' => 'pending'],
    'pagination' => ['limit' => 5, 'page' => 1],
    'order' => ['field' => 'id', 'sort' => 'desc']
]);

$completedVehicles = (new CustomerVehicle())->findBy([
    'filter' => ['deleted' => 0, 'status' => 'completed'],
    'pagination' => ['limit' => 5, 'page' => 1],
    'order' => ['field' => 'id', 'sort' => 'desc']
]);

$lastCreatedVehicles = (new Vehicle())->findBy([
    'filter' => ['is_active' => 1],
    'order' => ['field' => 'created_at', 'sort' => 'desc'],
    'pagination' => ['limit' => 5, 'page' => 1]]);

$orders = (new Order())->findBy([
    'filter' => ['customer_id' => $customer->getId(), 'payment_status' => 'completed'],
    'order' => ['field' => 'created_at', 'sort' => 'desc'],
    'pagination' => ['limit' => 5, 'page' => 1]
]);

$i = 0;
$creditLogs = [];

if (!empty($orders)) {
    /* @var Order $order */
    foreach ($orders as $order) {
        $ek = "";
        if(trim(strtolower($order->getNotes()))=="evc") $ek = "EVC ";

        $creditLogs[$i] = [
            'type' => 'credit',
            'credit' => '+' . $order->getTotalCredit(),
            'title' => $ek.$language::translate('Credit Charge'),
            'style' => 'class="badge badge-success"',
            'timestamp' => DateTime::createFromFormat('Y-m-d H:i:s', $order->getCreatedAt())->getTimestamp(),
            'dateFormat' => DateTime::createFromFormat('Y-m-d H:i:s', $order->getCreatedAt())->format('d M H:i'),
            'class' => 'PP'
        ];
        $i++;
    }
}

$customerVehicles = (new CustomerVehicle())->findBy([
    'filter' => ['customer_id' => $customer->getId()],
    'order' => ['field' => 'created_at', 'sort' => 'desc'],
    'pagination' => ['limit' => 5, 'page' => 1]
]);

$files = [];

$dateTime = new \DateTime();

if (!empty($customerVehicles)) {
    /* @var CustomerVehicle $customerVehicle */
    foreach ($customerVehicles as $key => $customerVehicle) {
  //      if (in_array($customerVehicle->getStatus(), ['pending', 'completed'])) {
            $creditLogs[$i] = [
                'type' => 'vehicle',
                'credit' => '-' . $customerVehicle->getTotalCredit(),
                'title' => ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('vehicle_full_name') : $customerVehicle->vehicle->getFullName()),
                'style' => 'class="badge badge-danger"',
                'timestamp' => DateTime::createFromFormat('Y-m-d H:i:s', $customerVehicle->getCreatedAt())->getTimestamp(),
                'dateFormat' => DateTime::createFromFormat('Y-m-d H:i:s', $customerVehicle->getCreatedAt())->format('d M H:i'),
                'class' => 'BW'
            ];
    //    }

        $interval = $dateTime->diff(DateTime::createFromFormat('Y-m-d H:i:s', $customerVehicle->getCreatedAt()));
        $diff = ($interval->format('%a') > 0) ? $interval->format('%a days %h hours ago') :
            ($interval->format('%h') < 2 ? 'now' : $interval->format('%h hours ago'));

        $files[$i] = [
            'title' => ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('vehicle_full_name') : $customerVehicle->vehicle->getFullName()),
            'status' => $customerVehicle->getStatus(),
            'style' => 'class="badge badge-success"',
            'diff' => $diff,
            'dateFormat' => DateTime::createFromFormat('Y-m-d H:i:s', $customerVehicle->getCreatedAt())->format('d M H:i')
        ];
        $i++;
    }
}

if (!empty($creditLogs)) {
    usort($creditLogs, function($a, $b) {
        return $b['timestamp'] > $a['timestamp'];
    });
}

// Get the current day and time
$currentDay = date('D');
$currentHour = date('H:i');
$currentDateTime = date('Y-m-d H:i');
// Define opening hours
$openingHours = [
    'Mon' => ['08:00', '20:00'],
    'Tue' => ['08:00', '20:00'],
    'Wed' => ['08:00', '20:00'],
    'Thu' => ['08:00', '20:00'],
    'Fri' => ['08:00', '20:00'],
    'Sat' => ['10:00', '22:00'],
    'Sun' => ['10:00', '22:00'],
];
 $service_status = $service_txt = '';
// Check if it's a weekday or weekend
if ($currentDay != 'Sat' && $currentDay != 'Sun') {
    // Check if current time is within opening hours
    if ($currentHour >= $openingHours[$currentDay][0] && $currentHour <= $openingHours[$currentDay][1]) {
        $service_status = "#0fb559";
		$service_txt = "Open";
    } else {
        $service_status = '#f13b36';
		$service_txt = "Closed";
    }
} else {
    // Check if it's Saturday or Sunday
    if ($currentHour >= $openingHours['Sat'][0] && $currentHour <= $openingHours['Sat'][1] && $currentDay == 'Sat') {
       $service_status = "#0fb559";
		$service_txt = "Open";
    } elseif ($currentHour >= $openingHours['Sun'][0] && $currentHour <= $openingHours['Sun'][1] && $currentDay == 'Sun') {
       $service_status = "#0fb559";
		$service_txt = "Open";
    } else {
        $service_status = '#f13b36';
		$service_txt = "Closed";
    }
}
$creditColor = '';
if($customer->getCredit() == 0){
	$creditColor = "#f13b36";
}elseif($customer->getCredit() < 15){
	$creditColor = "#ffa200";
}elseif($customer->getCredit() > 15){
	$creditColor = "#0fb559";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('Home') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
	<link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="\plugins\bootstrap-select\bootstrap-select.min.css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
    <link href="\assets\css\tables\table-basic.css" rel="stylesheet" type="text/css">
    <link href="\assets\css\components\custom-list-group.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="\assets/css/elements/alert.css">
    </head>
<body>

   	<?php include("ust2.php")?>
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
	<?php include("ust.php")?>
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
				                  <?php if($setting->getDuyuruStatus()) { ?>

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="alert alert-icon-left alert-light-success " style="margin-bottom: 0;margin-top: 55px;margin-right: -15px;margin-left: -15px;" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" data-dismiss="alert" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12" y2="17"></line></svg>
                            <strong><?= $language::translate('Duyuru') ?>!</strong> <?= $setting->getAnnouncement() ?>
                    </div>
                  </div>
                    <?php } ?>
				<style>
					.bg-aqua, .callout.callout-info, .alert-info, .label-info, .modal-info .modal-body {
						background-color: #009ef7 !important;
						color: #fff !important;
					}
					.small-box {
						border-radius: 5px;
						position: relative;
						display: block;
						margin-bottom: 20px;
						box-shadow: 0 1px 1px rgba(0,0,0,0.1);
					}
					.small-box>.inner {
						padding: 10px;
					}
					.small-box h3 {
						font-size: 25px;
						font-weight: bold;
						margin: 0 0 10px 0;
						white-space: nowrap;
						padding: 0;
						font-family: 'Source Sans Pro',sans-serif;
						line-height: 1.1;
    					color: inherit;
					}
					.small-box p {
						font-size: 12px;
						color: inherit;
						font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
						font-weight: 500;
					}
					.small-box>.small-box-footer {
						position: relative;
						text-align: right;
						padding: 5px 2px;
						color: #fff;
						color: rgba(255,255,255,0.8);
						display: block;
						z-index: 10;
						background: rgb(0 0 0 / 39%);
						text-decoration: none;
					}
					.small-box .icon {
						-webkit-transition: all .3s linear;
						-o-transition: all .3s linear;
						transition: all .3s linear;
						position: absolute;
						top: -10px;
						right: 10px;
						z-index: 0;
						font-size: 70px;
						color: rgba(0,0,0,0.15);
					}
					.small-box .icon .fontkismi{
					    font-size: 40px;
						color: rgba(0,0,0,0.15);
						font-weight: bold;
					}
					.small-box-footer .fontkismi {
						color: rgba(255,255,255,0.8);
						font-size: 0.875rem;
					}
					.bg-red, .callout.callout-danger, .alert-danger, .alert-error, .label-danger, .modal-danger .modal-body {
						background-color: #ff5c57 !important;
						color: #fff !important;
					}
					.bg-yellow{
						background-color: <?= $creditColor ?> !important;
						color: #fff !important;
					}
					.processing{
						background-color: #ffc500 !important;
						color: #fff !important;
					}
					.bg-success {
						background-color: #48cf85!important;
					}
					.btn-aqua {
						color: #fff;
						font-size: 13px;
						padding: 6px 25px;
						font-weight: 500;
					}
					.btn-aqua:hover {
						color:#fff;
					}
					.btn-success {
						color: #fff;
						background-color: #28c76f;
					}
					.btn-success:hover {
						background-color: #3ceb89;
					}
					.fileTable > tbody > tr:nth-child(even) > td, .orderTable > tbody > tr:nth-child(even) > td {
						background: #eee !important;
   	 					color: #6e6b7b !important;
					}
					.fileTable > tbody > tr:nth-child(odd) > td, .orderTable > tbody > tr:nth-child(odd) > td {
						background: #fff !important;
					}
					.fileTable > thead > tr:first-child > th, .orderTable > thead > tr:first-child > th {
						background: #eee !important;
   	 					color: black !important;
					}
					.service_staus{
						background: <?= $service_status ?>;
						color: #fff;
					}
					.small-box>.small-box-footer:hover {
						background: #000;
						color: #fff;
						opacity: 0.6;
					}
				</style>
				
				<div class="row">
					<div class="col-lg-4 col-xs-6">
						<div class="small-box bg-aqua">
							<div class="inner">
								<p>CURRENT QUEUE</p>
								<?php 
									$queuefiles = $VehiclePendingCount + $VehicleProcessingCount;
								?>
								
								<h3><?= ($queuefiles > 0) ? $queuefiles : 0 ?> Files</h3>
							</div>
							<div class="icon">
								<i class="ti-file fontkismi"></i>
							</div>
							<a href="/panel/file-upload" class="small-box-footer">Upload File <i class="ti-zip fontkismi"></i></a>
						</div>
					</div>
					
					<div class="col-lg-4 col-xs-6">
						<div class="small-box service_staus">
							<div class="inner">
								<p>FILE SERVICE STATUS</p>
								<h3><?= $service_txt  ?></h3>
							</div>
							<div class="icon">
								<i class="ti-desktop fontkismi"></i>
							</div>
							<a href="#" class="small-box-footer"><?= $currentDateTime ?> <i class="ti-time fontkismi"></i></a>
						</div>
					</div>

					<div class="col-lg-4 col-xs-6">
						<div class="small-box bg-yellow">
							<div class="inner">
								<p>CREDITS</p>
								<h3><span class="w-currency"><?= $customer->getCredit() ?></span> CRD</h3>
							</div>
							<div class="icon">
								<i class="ti-server fontkismi"></i>
							</div>
							<a href="/panel/buy-credit" class="small-box-footer">Buy Credits <i class="ti-shopping-cart fontkismi"></i></a>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-12 col-lg-12 col-sm-12 pb-3">
						<div class="widget widget-table-one">
							<h5>Recent file service</h5>
							<table class="table fileTable mt-3" style="width:100%">
								<thead>
									<tr>
										<th>ID</th>
										<th>Date</th>
										<th>Vehicle</th>
										<th>Modification</th>
										<th>Credit</th>
										<th>Status</th>
										<th>Details</th>
									</tr>
								</thead>
                                <tbody>
									<?php

                                    if (!empty($customerVehicles)) {
                                        /* @var Order $order */
                                        foreach ($customerVehicles as $customerVehicle) {  ?>
									<tr>
										<td><?= $customerVehicle->getId() ?></td>
										<td><?= $customerVehicle->getCreatedAt() ?></td>
										<td>
											<?= (!empty($customerVehicle->getVehicle()->brand->getImage())) ? '<img width="25" class="mr-1" src="/images/category/'.$customerVehicle->getVehicle()->brand->getImage().'">' : '' ?><?= $customerVehicle->getVehicle()->getFullName() ?></td>										<td><span class="badge badge-dark"><?= $customerVehicle->vehicleTuning->getName() ?></span></td>
										<td><?= $customerVehicle->getTotalCredit() ?> CRD</td>
										<td>
											<?php if($customerVehicle->getStatus() == 'pending'){ ?>
												<span class="badge bg-aqua text-capitalize"><?= $customerVehicle->getStatus() ?></span>
											<?php } ?>
											<?php if($customerVehicle->getStatus() == 'process'){ ?>
												<span class="badge processing text-capitalize">Processing</span>
											<?php } ?>
											<?php if($customerVehicle->getStatus() == 'cancel'){ ?>
												<span class="badge bg-red text-capitalize">Canceled</span>
											<?php } ?>
											<?php if($customerVehicle->getStatus() == 'completed'){ ?>
												<span class="badge bg-success text-capitalize">Completed</span>
											<?php } ?>
										</td>
										<td><a href="panel/my-files/detail/<?= $customerVehicle->getId() ?>" class="btn btn-sq btn-success detail-btn"><i class="ti-eye"></i></a></td>
									</tr>
									<?php }
                                    }
                                    ?>
								</tbody>
							</table>
							<a href="/panel/my-files" class="btn btn-aqua bg-aqua">View All File Services</a>
						</div>
					</div>
				</div>
				<?php
                                        //foreach ($orders as $order) { echo "<pre>"; var_dump( $order); echo "<pre>"; }
				?>
				<div class="row">
					<div class="col-xl-12 col-lg-12 col-sm-12 pb-3">
						<div class="widget widget-table-one">
							<h5>Recent Orders</h5>
							<table class="table orderTable mt-3" style="width:100%">
								<thead>
									<tr>
										<th>ORDER NO</th>
										<th>DATE</th>
										<th>CUSTOMER</th>
										<th>AMOUNT</th>
										<th>STATUS</th>
										<th>OPTIONS</th>
									</tr>
								</thead>
                                <tbody>
									<?php
                                    if (!empty($orders)) {
                                        /* @var Order $order */
                                        foreach ($orders as $order) { 
											$invoice = $order->getInvoice();
									?>
									<tr>
										<td><a href="/panel/credit-reports"><?= $order->getId() ?></a></td>
										
										<td>
											<?= DateTime::createFromFormat('Y-m-d H:i:s', $order->getCreatedAt())->format('d M Y H:i') ?>
											
										</td>
										<td><?= $customerVehicle->getCustomer()->getFirstName().' '. $customerVehicle->getCustomer()->getLastName() ?></td>
										<td><?= $order->getTotal(true, true) ?></td>
										<td>
											<?php if($order->getPaymentStatus() == 'pending'){ ?>
												<span class="badge bg-aqua text-capitalize"><?= $customerVehicle->getStatus() ?></span>
											<?php } ?>
											<?php if($order->getPaymentStatus() == 'process'){ ?>
												<span class="badge processing text-capitalize">Processing</span>
											<?php } ?>
											<?php if( $order->getPaymentStatus()  == 'cancel'){ ?>
												<span class="badge bg-red text-capitalize">Canceled</span>
											<?php } ?>
											<?php if($order->getPaymentStatus() == 'completed'){ ?>
												<span class="badge bg-success text-capitalize">Completed</span>
											<?php } ?>
										</td>
										<td><a href="/panel/invoice/detail/<?= $invoice->getId() ?>" class="btn btn-sq btn-success detail-btn"><i class="ti-file"></i></a></td>
									</tr>
									<?php }
                                    }
                                    ?>
								</tbody>
							</table>
							<a href="/panel/credit-reports" class="btn btn-aqua bg-aqua">View all orders</a>
						</div>
					</div>
				</div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 pb-3">
                        <div class="widget widget-table-two">
                            <div class="widget-content">
                               <ul class="nav nav-tabs  mb-3" id="lineTab" role="tablist">
                                   <?php
                                   if(!empty($categories)) {
                                       usort($categories, function ($item1, $item2) {
                                           return $item1->getSortOrder() <=> $item2->getSortOrder();
                                       });
                                       $i = 1;
                                       /* @var Category $main */
                                       foreach ($categories as $main) {
                                           if (!$main->isActive()) continue;
                                           ?>
                                           <li class="nav-item">
                                               <a style="text-align:center;"  class="nav-link <?= ($i != 1 ?: 'active') ?>" id="<?= $main->getSlug() ?>-tab"
                                                  data-toggle="tab" href="#<?= $main->getSlug() ?>" role="tab"
                                                  aria-controls="<?= $main->getSlug() ?>" aria-selected="true">
                                                   <img style="    width: 70px;" src="<?= $main->getIcon(true) ?>"> <br> <?= $language::translate($main->getName()) ?>
                                               </a>
                                           </li>
                                       <?php $i++; }
                                   }
                                   ?>
                                </ul>

                            <div class="tab-content" id="lineTabContent-3">
                                <?php
                                if(!empty($categories)) {
                                    $i = 1;
                                    /* @var Category $main */
                                    /* @var Category $brand */
                                    foreach ($categories as $main) {
                                        if (!$main->isActive()) continue; ?>
                                        <div class="tab-pane <?= ($i == 1 ? 'active' : '') ?>" id="<?= $main->getSlug() ?>" role="tabpanel" aria-labelledby="<?= $main->getSlug() ?>-tab">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <select id="brand-<?= $main->getId() ?>" data-main="<?= $main->getId() ?>" data-type="brand" class="selectpicker" onchange="getSubCategoriesForSelect(<?= $main->getId() ?>, this)">
                                                        <option value=""><?= $language::translate('Select Brand') ?></option>
                                                        <?php foreach ($main->getChildren() as $brand) {
                                                            if (!$brand->isActive() || $brand->getType() != 'brand') continue; ?>
                                                            <option value="<?= $brand->getId() ?>"><?= $brand->getName() ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <select id="model-<?= $main->getId() ?>" data-type="model"  class="selectpicker"  onchange="getSubCategoriesForSelect(<?= $main->getId() ?>, this)">
                                                        <option><?= $language::translate('Select Model') ?></option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <select id="generation-<?= $main->getId() ?>" data-type="generation"  class="selectpicker" onchange="getSubCategoriesForSelect(<?= $main->getId() ?>, this)">
                                                        <option><?= $language::translate('Select Generation') ?></option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <select id="engine-<?= $main->getId() ?>" data-type="engine"  class="selectpicker" onchange="getEcuForSelect(this)">
                                                        <option><?= $language::translate('Select Engine') ?></option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <select class="selectpicker" id="ecu-<?= $main->getId() ?>" onchange="getVehicleByEcu()" data-type="ecu">
                                                        <option><?= $language::translate('Select Ecu') ?></option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <button style="width: 100%;    height: 49px;" class="btn btn-primary mb-2" onclick="getVehicleByEcu()"><?= $language::translate('Send') ?></button>
                                                </div>
                                            </div>
                                            <div id="vehicle-block">

                                            </div>
                                         </div>
                                    <?php $i++; }
                                }?>

                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		 <?php include("alt.php")?>
		 </div>
        <!--  END CONTENT AREA  -->
    </div>
     <?php include("js.php")?>
	     <script src="\plugins\bootstrap-select\bp.js"></script>
  <style>
		 .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
    width: 100%;
}
.widget-table-three .table > tbody > tr > td:last-child .td-content {
    padding: 0;
    width: 100%;
    margin: 0 auto;
}
</style>
 <script src="\assets/js/alt.js"></script>
<script>

    var category_chain = ['main', 'brand', 'model', 'generation', 'engine'];

    function getSubCategoriesForSelect(mainId, element) {

        var main = $(element).data('main');
        var type = $(element).data('type');

        resetChainSelect(type);

        if ($(element).val()) {
            $('button').prop('disabled', true);
            $.ajax({
                url : '/ajax/category/' + $(element).val() + '/get-sub-categories-for-select',
                success: function (response) {
                    if (response.success) {
                        pushAfterSelectCategory(element, response.result)
                    }
                },
                error: function (error) {}
            })
            $('button').prop('disabled', false);
        }
    }

    function resetChainSelect(type)
    {
        var reset = false;

        $('.tab-pane.active select').each(function (i, select){
            if (reset) {
                var firstOption = $(select).find('option').first();
                $(select).html('<option value="">' + firstOption.text() + '</option>');
                $(select).selectpicker('refresh');
            }
            if (type === $(select).data('type')) {
                reset = true;
            }
        })

    }

    function pushAfterSelectCategory(element, result)
    {
        var categoryType = getCategoryTypeByBeforeCategoryType($(element).data('type'));
        var categorySelectElement = $('.tab-pane.active').find('[data-type="' + categoryType + '"]');

        var selectOptionsHtml = categorySelectElement.html() + '\n';

        result.forEach(function(data) {
            selectOptionsHtml += '<option value="' + data.id + '">' + data.name + '</option>\n'
        });

        categorySelectElement.html(selectOptionsHtml);
        categorySelectElement.selectpicker('refresh')
    }

    function getCategoryTypeByBeforeCategoryType(type)
    {
        return category_chain[(category_chain.indexOf(type)+1)];
    }

    function getEcuForSelect(element) {

        var ecu = $('.tab-pane.active').find('[data-type="ecu"]')
        var firstOption = ecu.find('option').first();
        ecu.html('<option value="">' + firstOption.text() + '</option>');

        if ($(element).val()) {
            $('button').prop('disabled', true);
            $.ajax({
                url : '/ajax/vehicle/' + $(element).val() + '/get-ecu-for-select',
                success: function (response) {
                    if (response.success) {
                        var selectOptionsHtml = ecu.html() + '\n';

                        response.result.forEach(function(data) {
                            selectOptionsHtml += '<option value="' + data.id + '">' + data.name + '</option>\n'
                        });

                        ecu.html(selectOptionsHtml);
                        ecu.selectpicker('refresh')
                    }
                },
                error: function (error) {}
            })
            $('button').prop('disabled', false);
        }
    }

    function getVehicleByEcu() {

        var ecu = $('.tab-pane.active').find('[data-type="ecu"]');
        var vehicleBlock = $('.tab-pane.active #vehicle-block');

        vehicleBlock.html('');

        if (ecu.val()) {
            $('button').prop('disabled', true);
            $.ajax({
                url : '/ajax/vehicle/' + ecu.val() + '/get-vehicle-by-id-with-html-for-home',
                success: function (response) {
                    if (response) {
                        console.log(response);
                        vehicleBlock.html(response);
                        $([document.documentElement, document.body]).animate({
                            scrollTop: (vehicleBlock.offset().top - 100)
                        }, "slow");
                    }
                },
                error: function (error) {}
            })
            $('button').prop('disabled', false);
        }
    }

</script>

<script type="text/javascript">


    function checkEvcBalance() {
        var evcnumber = $("#evcnumber").val();

        $.ajax({
            url: "<?= SITE_URL ?>/evc/api.php?islem=checkBalance&customer="+evcnumber,
            type: "GET",
            data: null,
            dataType: "json",
            success: function (response) {
                if(response.status=="OK"||response.status=="ok") {
                    $("#evcbalancepanel").show();
                    $("#evc_balance").html(response.balance);

                }else{
                    $("#evcbalancepanel").hide();
                }
            },
            error: function (xhr, status) {
                $("#evcbalancepanel").hide();
            }
        });

    }

    function checkEvcResellerStatus(){
        var evcnumber = $("#evcnumber").val();

        $.ajax({
            url: "<?= SITE_URL ?>/evc/api.php?islem=evcResellerCustomerControl&customer="+evcnumber,
            type: "GET",
            data: null,
            dataType: "json",
            success: function (response) {
                if(response.status=="OK"||response.status=="ok") {
                    checkEvcBalance();
                }else{
                $("#evcbalancepanel").hide();
                }
            },
            error: function (xhr, status) {
                $("#evcbalancepanel").hide();
            }
        });

    }


     $(document).ready(function() {
         checkEvcResellerStatus();
      // If no cookie with our chosen name (e.g. no_thanks)...
      if ($.cookie("no_thanks") == null) {

        // Show the modal, with delay func.zz
        $('#ornekModal').appendTo("body");
        function show_modal(){
          $('#ornekModal').modal();
        }

        // Set delay func. time in milliseconds
        window.setTimeout(show_modal, 100);
        }

      // On click of specified class (e.g. 'nothanks'), trigger cookie, which expires in 100 years
      $(".nothanks").click(function() {
        $.cookie('no_thanks', 'true', { expires: 36500, path: '/' });
      });
    });


 </script>

<style media="screen">
.table-striped tbody tr:nth-of-type(odd) {
  background-color: #e8e8e8 !important;
  border: none !important;
}
.table td, .table th {
     border-top: none !important;
}

.table > tbody > tr > td {
    padding: 0;
    padding: 14px 21px 14px 21px;
    color: #6e6b7b !important;
    letter-spacing: normal;
    white-space: nowrap;
}

</style>
 </body>
</html>
