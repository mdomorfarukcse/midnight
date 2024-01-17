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
    'pagination' => ['limit' => 10, 'page' => 1]
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
                <div class="row layout-top-spacing">

                  <?php if($setting->getDuyuruStatus()) { ?>

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="alert alert-icon-left alert-light-success " style="margin-bottom:0" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" data-dismiss="alert" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12" y2="17"></line></svg>
                            <strong><?= $language::translate('Duyuru') ?>!</strong> <?= $setting->getAnnouncement() ?>
                    </div>
                  </div>
                    <?php } ?>
                    <?php
                        if (!empty($awaitingPaymentAlert)) {?>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                                <div class="alert alert-icon-left alert-light-danger " style="margin-bottom:0"
                                     role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <svg xmlns="http://www.w3.org/2000/svg" data-dismiss="alert" width="24"
                                             height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-x close">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-alert-triangle">
                                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                        <line x1="12" y1="9" x2="12" y2="13"></line>
                                        <line x1="12" y1="17" x2="12" y2="17"></line>
                                    </svg>
                                    <strong><?= $language::translate('Awaiting Payment') ?>
                                        !</strong> <a href="/panel/buy-credit" class="link-badge-info"><?= $language::translate('Pay') ?></a> <?= $language::translate('or') ?>
                                    <a href="/panel/my-files" class="link-badge-info"><?= $language::translate('See your files') ?></a>
                                </div>
                            </div>
                        <?php } 
                    ?>

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
                                                    <p><span  <?= $creditLog['style'] ?> ><?= $creditLog['credit'] ?> CRD</span></p>
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
                                <div class="mt-container mx-auto ps ps--active-y" style="    height: 280px;">
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
                                                    <a href="/panel/my-files">    <p><?= $file['title'] ?></p> </a>
                                                        <span class="badge badge-info"><?= $file['status'] ?></span>
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

                        <div class="widget widget-account-invoice-three">

                            <div class="widget-heading">
                                <a href="/panel/my-profile">  <div class="wallet-usr-info">
                                    <div class="usr-name">
                                      <span><img src="<?= $customer->getAvatar(true) ?>" alt="admin-profile" class="img-fluid"> <?= $customer->getFullName() ?></span>
                                    </div>
                                    <div class="add">
                                        <span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></span>
                                    </div>
                                </div></a>
                                <div class="wallet-balance">
                                    <p><?= $language::translate('Balance') ?></p>
                                    <h5 class=""><span class="w-currency"><?= $customer->getCredit() ?></span> <?= $language::translate('Credit') ?></h5>
                                </div>
                                <div style="display: none;" id="evcbalancepanel" class="wallet-balance">
                                    <p>EVC <?= $language::translate('Balance') ?></p>
                                    <h5 class=""><span class="w-currency" id="evc_balance">?</span> <?= $language::translate('Credit') ?></h5>

                                    <input type="hidden" id="evcnumber" value="<?=$customer->getEvcnumber();?>"/>
                                </div>
                            </div>

                            <div style="     margin-bottom: 106px;" class="widget-amount">

                                <div class="w-a-info funds-received">
                                    <span><?= $language::translate('Purchased') ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-up"><polyline points="18 15 12 9 6 15"></polyline></svg></span>
                                    <p><?= $customer->homeTotalOrderCredit() ?></p>
                                </div>

                                <div class="w-a-info funds-spent">
                                    <span><?= $language::translate('Spent') ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></span>
                                    <p><?= $customer->totalSpendingCredit() ?></p>
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
                                                            <a href="/panel/invoice/detail/<?= $invoice->getId() ?>"><?= $order->getNumber() ?></a>
                                                        <?php } else {?>
                                                           <?= $order->getNumber() ?>
                                                        <?php }
                                                        ?>


                                                </td>
                                                <td>
												<?php
                                                        if (!empty($invoice = $order->getInvoice())) {?>
                                                      <a href="/panel/invoice/detail/<?= $invoice->getId() ?>">
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
                                                            <a href="/panel/invoice/detail/<?= $invoice->getId() ?>"><?= $language::translate($invoice->getStatus())  ?></a>
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
    color: #000000 !important;
    letter-spacing: normal;
    white-space: nowrap;
}

</style>
 </body>
</html>
