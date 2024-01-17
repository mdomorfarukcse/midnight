<?php

use Pemm\Core\Container;
use Pemm\Model\Category;
use Pemm\Model\Vehicle;
use Pemm\Model\Order;
use Pemm\Model\User;
use Pemm\Model\CustomerVehicle;
use Pemm\Model\Customer;
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

/* @var User $user */
$user = $container->get('user');

$categoryInstance = new Category();
$categoryInstance->getCategories();
$categories = $categoryInstance->getCategoryByParentId(0);

$language = $container->get('language');

$customerVehicleModel = new CustomerVehicle();
        $lastUpdatedVehicles = $customerVehicleModel->findBy([
            'filter' => ['deleted' => 0],
            'pagination' => ['limit' => 5, 'page' => 1],
            'order' => ['field' => 'id', 'sort' => 'desc']
        ]);

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
  'filter' => ['payment_status' => 'completed'],
     'order' => ['field' => 'created_at', 'sort' => 'desc'],
    'pagination' => ['limit' => 5, 'page' => 1]
]);


$i = 0;

$creditLogs = [];

if (!empty($orders)) {
    /* @var Order $order */
    foreach ($orders as $order) {
        $creditLogs[$i] = [
            'type' => 'credit',
            'credit' => '+' . $order->getTotalCredit(),
            'title' => $language::translate('Credit Charge'),
            'style' => 'class="badge badge-success"',
            'timestamp' => DateTime::createFromFormat('Y-m-d H:i:s', $order->getCreatedAt())->getTimestamp(),
            'dateFormat' => DateTime::createFromFormat('Y-m-d H:i:s', $order->getCreatedAt())->format('d M H:i'),
            'class' => 'PP'
        ];
        $i++;
    }
}

$customerVehicles = (new CustomerVehicle())->findBy([
    'filter' => [],
    'order' => ['field' => 'created_at', 'sort' => 'desc'],
    'pagination' => ['limit' => 10, 'page' => 1]
]);

$files = [];

$dateTime = new \DateTime();

if (!empty($customerVehicles)) {
    /* @var CustomerVehicle $customerVehicle */
    foreach ($customerVehicles as $key => $customerVehicle) {
        if (in_array($customerVehicle->getStatus(), ['pending',  'process', 'completed'])) {
            $creditLogs[$i] = [
                'type' => 'vehicle',
                'credit' => '-' . $customerVehicle->getTotalCredit(),
                'title' => ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('vehicle_full_name') : $customerVehicle->vehicle->getFullName()),
                'style' => 'class="badge badge-danger"',
                'timestamp' => DateTime::createFromFormat('Y-m-d H:i:s', $customerVehicle->getCreatedAt())->getTimestamp(),
                'dateFormat' => DateTime::createFromFormat('Y-m-d H:i:s', $customerVehicle->getCreatedAt())->format('d M H:i'),
                'class' => 'BW'
            ];
        }

        $interval = $dateTime->diff(DateTime::createFromFormat('Y-m-d H:i:s', $customerVehicle->getCreatedAt()));
        $diff = ($interval->format('%a') > 0) ? $interval->format('%a days %h hours ago') :
            ($interval->format('%h') < 2 ? 'now' : $interval->format('%h hours ago'));

        $files[$i] = [
            'title' => ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('vehicle_full_name') : $customerVehicle->vehicle->getFullName()),
            'status' => $customerVehicle->getStatus(),
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('Home') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
	<link href="<?= SITE_URL ?>\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\bootstrap-select\bootstrap-select.min.css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link href="<?= SITE_URL ?>\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\assets\css\forms\theme-checkbox-radio.css">
    <link href="<?= SITE_URL ?>\assets\css\tables\table-basic.css" rel="stylesheet" type="text/css">
    <link href="<?= SITE_URL ?>\assets\css\components\custom-list-group.css" rel="stylesheet" type="text/css">

  </head>
<body>
   	<?php include("header.php")?>

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
	<?php include("sidebar.php")?>
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">

				<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
          <div class="widget widget-table-one" style="padding: 20px 20px 60px 20px !important;">
                            <div class="widget-heading">
                                <h5 class=""><?= $language::translate('Credit Transactions') ?></h5>
                            </div>

                            <div class="widget-content" style="height: 238px;">
                                <?php
                                if (empty($creditLogs)) {?>
                                    <div class="t-text">
                                        <?= $language::translate('No data available') ?>
                                    </div>
                                <?php } else {
                                    foreach ($creditLogs as $key => $creditLog) {
                                        if ($key > 3) continue;
                                        ?>
                                        <div class="transactions-list t-info">
                                            <div class="t-item">
                                                <div class="t-company-name">
                                                    <div class="t-icon">
                                                        <div class="avatar avatar-xl">
                                                            <span class="avatar-title"><?= $creditLog['class'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="t-name">
                                                        <h4><?= $creditLog['title'] ?></h4>
                                                        <p class="meta-date"><?= $creditLog['dateFormat'] ?></p>
                                                    </div>
                                                </div>
                                                <div class="t-rate rate-inc">
                                                    <p><span <?= $creditLog['style'] ?> ><?= $creditLog['credit'] ?> CRD</span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                 }
                                ?>
                            </div>
                        </div>
                    </div>

					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">

                        <div class="widget widget-activity-four">

                            <div class="widget-heading">
                                <h5 class=""><?= $language::translate('File Transactions') ?></h5>
                            </div>

                            <div class="widget-content">

                              <div class="mt-container mx-auto ps ps--active-y" style="height: 280px;">
                                    <div class="timeline-line">
                                        <?php
                                        if (empty($files)) {?>
                                            <div class="t-text">
                                                <?= $language::translate('No data available') ?>
                                            </div>
                                        <?php } else {
                                            foreach ($files as $file) {?>
                                                <div class="item-timeline timeline-primary">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p><?= $file['title'] ?></p>
                                                        <span class="badge"><?= $file['status'] ?></span>
                                                        <p class="t-time"><?= $file['diff'] ?></p>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                         }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-three">
                            <div class="widget-heading">
                                <h5 class=""><?= $language::translate('Summary') ?></h5>
                                <div class="task-action">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle" href="#" role="button" id="pendingTask" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                        </a>
                                    </div>
                                </div>

                            </div>
                            <?php
                                $summary = (new Order())->summaryTotal(true)
                            ?>
                            <div class="widget-content">
                                <div class="order-summary">
                                    <div class="summary-list">
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                        </div>
                                        <div class="w-summary-details">
                                            <div class="w-summary-info">
                                                <h6><?= $language::translate('Total Customer') ?></h6>
                                                <p class="summary-count"><?= (new Customer())->summaryTotal() ?></p>
                                            </div>
                                            <div class="w-summary-stats">
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 100%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="summary-list">
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7" y2="7"></line></svg>
                                        </div>
                                        <div class="w-summary-details">
                                            <div class="w-summary-info">
                                                <h6><?= $language::translate('Total Credit') ?></h6>
                                                <p class="summary-count"><?= $summary->orderCreditTotal ?> CRD</p>
                                            </div>
                                            <div class="w-summary-stats">
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 100%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="summary-list">
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                        </div>
                                        <div class="w-summary-details">
                                            <div class="w-summary-info">
                                                <h6><?= $language::translate('Total Payment') ?></h6>
                                                <p class="summary-count"><?= $summary->orderTotal ?></p>
                                            </div>
                                            <div class="w-summary-stats">
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-warning" role="progressbar" style="width: 100%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
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

                    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                        <div class="widget-content widget-content-area br-6" style="overflow-x: scroll">
                             <table class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><?= $language::translate('Order Number') ?></th>
                                        <th><?= $language::translate('Invoice Number') ?></th>
                                        <th><?= $language::translate('Date') ?></th>
                                        <th><?= $language::translate('Credit') ?></th>
                                        <th><?= $language::translate('Amount') ?></th>
                                        <th><?= $language::translate('State') ?></th>
                                        <th><?= $language::translate('Invoice') ?></th>
                                     </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($orders)) {
                                        /* @var Order $order */
                                        foreach ($orders as $order) { ?>
                                            <tr>
                                                <td>
                        <?php
                                                        if (!empty($invoice = $order->getInvoice())) {?>
                                                            <a href="/admin/invoice/detail/<?= $invoice->getId() ?>"><?= $order->getNumber() ?></a>
                                                        <?php } else {?>
                                                           <?= $order->getNumber() ?>
                                                        <?php }
                                                        ?>


                                                </td>
                                                <td>
                        <?php
                                                        if (!empty($invoice = $order->getInvoice())) {?>
                                                      <a href="/admin/invoice/detail/<?= $invoice->getId() ?>">
                                                        <span class="inv-number"><?= (!empty($order->getInvoice()) ? $order->getInvoice()->getNumber() : '') ?></span>
                                                    </a>

                                                        <?php }
                                                        ?>
                                                </td>
                                                <td>
                                                    <span class="inv-date">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                                        </svg> <?= DateTime::createFromFormat('Y-m-d H:i:s', $order->getCreatedAt())->format('d M Y H:i') ?>
                                                    </span>
                                                </td>
                                                <td><span class="badge badge-primary"><?= $order->getTotalCredit() ?> CRD</span></td>
                                                <td><span class="inv-amount"><?= $order->getTotal(true, true) ?></span></td>
                                                <td><span class="badge badge-success inv-status"><?= $language::translate($order->getPaymentStatus()) ?></span></td>
                                                <td>
                                                    <span class="badge badge-success">
                                                        <?php
                                                        if (!empty($invoice = $order->getInvoice())) {?>
                                                            <a href="/admin/invoice/detail/<?= $invoice->getId() ?>"><?= $language::translate($invoice->getStatus())  ?></a>
                                                        <?php } else {?>
                                                            <?= $language::translate('Fatura Hazırlanıyor') ?>
                                                        <?php }
                                                        ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                                        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                                            <div class="widget widget-table-three">
                                                <div class="widget-heading">
                                                    <h5 class=""><?= $language::translate('Pending Transactions') ?></h5>
                                                </div>
                                                <div class="widget-content">
                                                    <div class="table-responsive">
                                                        <table class="table table-scroll">
                                                            <tbody>
                                                            <?php
                                                            if (count($pendingVehicles)) {
                                                                foreach ($pendingVehicles as $pendingVehicle) { ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="td-content product-name"><img src="<?= $pendingVehicle->vehicle->brand->getImage(true) ?>" alt="product">
                                                                                <div class="align-self-center">
                                                                                    <p class="prd-name"><?= ($pendingVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $pendingVehicle->getWMVdata('vehicle_full_name') : $pendingVehicle->vehicle->getFullName()) ?></p>
                                                                                    <p class="prd-category text-primary"><?= $pendingVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $pendingVehicle->getPower() . " BHP - " . $pendingVehicle->getTorque() . " KW" : $pendingVehicle->vehicle->engine->getName() ?></p>
                                                                                    <span class="badge badge-primary mr-2 mt-1"><?= $pendingVehicle->vehicleTuning->getName() ?></span>
                                                                                    <?php
                                                                                    if (!empty($pendingVehicle->vehicleAdditionalOptions)) {

                                                                                        /* @var \Pemm\Model\VehicleTuning $vehicleTuning */
                                                                                        foreach ($pendingVehicle->vehicleAdditionalOptions as $vehicleTuning) {
                                                                                          ?>

                                                                                                <span class="badge badge-dark mr-2 mt-1"><?= $vehicleTuning->additionalOption->getName() ?></span>
                                                                                         <?php
                                                                                       }
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php }
                                                            }
                                                            ?>
                                                              </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                                            <div class="widget widget-table-three">
                                                <div class="widget-heading">
                                                    <h5 class=""><?= $language::translate('Completed Transactions') ?></h5>
                                                </div>
                                                <div class="widget-content">
                                                    <div class="table-responsive">
                                                        <table class="table table-scroll">
                                                            <tbody>
                                                            <?php
                                                            if (count($completedVehicles)) {
                                                                foreach ($completedVehicles as $pendingVehicle) { ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="td-content product-name"><img src="<?= $pendingVehicle->vehicle->brand->getImage(true) ?>" alt="product">
                                                                                <div class="align-self-center">
                                                                                    <p class="prd-name"><?= ($pendingVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $pendingVehicle->getWMVdata('vehicle_full_name') : $pendingVehicle->vehicle->getFullName()) ?></p>
                                                                                    <p class="prd-category text-primary"><?= $pendingVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $pendingVehicle->getPower() . " BHP - " . $pendingVehicle->getTorque() . " KW" : $pendingVehicle->vehicle->engine->getName() ?></p>
                                                                                    <span class="badge badge-primary mr-2 mt-1"><?= $pendingVehicle->vehicleTuning->getName() ?></span>
                                                                                    <?php
                                                                                    if (!empty($pendingVehicle->vehicleAdditionalOptions)) {

                                                                                        /* @var \Pemm\Model\VehicleTuning $vehicleTuning */
                                                                                        foreach ($pendingVehicle->vehicleAdditionalOptions as $vehicleTuning) {
                                                                                            ?>

                                                                                            <span class="badge badge-dark mr-2 mt-1"><?= $vehicleTuning->additionalOption->getName() ?></span>
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php }
                                                            }
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                    					<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                                            <div class="widget widget-table-three">
                                                <div class="widget-heading">
                                                    <h5 class=""><?= $language::translate('Last Added Vehicles') ?></h5>
                                                </div>
                                                <div class="widget-content">
                                                    <div class="table-responsive">
                                                        <table class="table table-scroll">
                                                            <tbody>
                                                            <?php
                                                            if (!empty($lastCreatedVehicles)) {
                                                                /* @var Vehicle $lastCreatedVehicle */
                                                                foreach ($lastCreatedVehicles as $lastCreatedVehicle) { ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="td-content product-name"><img src="<?= $lastCreatedVehicle->brand->getImage(true) ?>" alt="product">
                                                                                <div class="align-self-center">
                                                                                    <p class="prd-name"><?= $lastCreatedVehicle->getFullName() ?></p>
                                                                                    <p class="prd-category text-primary"><?= $lastCreatedVehicle->engine->getName() ?></p>
                                                                                    <?php
                                                                                    if (!empty($lastCreatedVehicle->hasTuning())) {
                                                                                        /* @var \Pemm\Model\VehicleTuning $vehicleTuning */
                                                                                        foreach ($lastCreatedVehicle->tunings as $vehicleTuning) {
                                                                                            if ($vehicleTuning->tuning && $vehicleTuning->getIsActive()) {    ?>
                                                                                                <span class="badge badge-primary mr-2 mt-1"><?= $vehicleTuning->tuning->getName() ?></span>
                                                                                                <span class="badge badge-dark mr-2 mt-1"><?= $vehicleTuning->getDifferenceTorque() ?></span>
                                                                                                <span class="badge badge-dark mr-2 mt-1"><?= $vehicleTuning->getDifferencePower() ?></span>
                                                                                            <?php }
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php }
                                                            }
                                                            ?>
                                                              </tbody>
                                                        </table>
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
     <script src="<?= SITE_URL ?>\plugins\bootstrap-select\bp.js"></script>


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
                    url : '/ajax/admin/category/' + $(element).val() + '/get-sub-categories-for-select',
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
                    url : '/ajax/admin/vehicle/' + $(element).val() + '/get-ecu-for-select',
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
                    url : '/ajax/admin/vehicle/' + ecu.val() + '/get-vehicle-by-id-with-html-for-home',
                    success: function (response) {
                        if (response) {
                            vehicleBlock.html(response);
                            $([document.documentElement, document.body]).animate({
                                scrollTop: ($(".tab-pane.active #vehicle-block").offset().top - 100)
                            }, "slow");
                        }
                    },
                    error: function (error) {}
                })
                $('button').prop('disabled', false);
            }

        }

    </script>
    <style media="screen">
    .table-striped tbody tr:nth-of-type(odd) {
      background-color: #060818 !important;
      border: none !important;
    }
    .table td, .table th {
         border-top: none !important;
    }

    .table > tbody > tr > td {
        padding: 0;
        padding: 14px 21px 14px 21px;
        color: #e7e7e7 !important;
        letter-spacing: normal;
        white-space: nowrap;
    }

    </style>
 </body>
</html>
