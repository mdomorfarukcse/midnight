<?php

use Pemm\Core\Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Customer as CustomerModel;
use Pemm\Model\Order as OrderModel;
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

/* @var Request $request */
$request = $container->get('request');

/* @var Session $session */
$session = $container->get('session');

$language = $container->get('language');

$order = (new OrderModel())->find($container->get('detailId'));

if (empty($order)) {
    new RedirectResponse('/admin');
}

if ($request->isMethod('post')) {

    try {

        $order->setState($request->request->get('status'));

        $order->store();

        $session->getFlashBag()->add('success', 'Success');

    } catch (\Exception $exception) {
        $session->getFlashBag()->add('danger', $exception->getMessage());
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('Order') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
	<link href="<?= SITE_URL ?>\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\bootstrap-select\bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\bootstrap-toggle\bootstrap-toggle.min.css">
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
                <form action="" method="post" style="width: 100%" enctype="multipart/form-data">
                <div class="row layout-top-spacing">
                    <div class="col-xl-6 col-lg-6 col-sm-12 ">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $language::translate('Order Detail') ?></h5>
                                    <?php
                                    foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                                        <div class="alert alert-<?= $type ?>">
                                            <?php foreach ($messages as $message) { echo $message;} ?>
                                        </div>
                                    <?php }


                                    $customerModel = new CustomerModel();
                                   $customer2 = $customerModel->find(["id"=>$order->getCustomerId()]);

                                    ?>
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Status') ?>
                                            <select name="status" id="status" class="form-control" style="width: 50%">
                                            <?php foreach ($order::situations() as $state => $label) {?>
                                                    <option value="<?= $state ?>"<?= ($order->getState() == $state ? 'selected' : '') ?>><?= $label ?></option>
                                            <?php } ?>
                                            </select>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Customer') ?>
                                            <span class="badge badge-primary badge-pill"> <?= $customer2->getFirstName()." ".$customer2->getLastName() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Customer İp') ?>
                                            <span class="badge badge-primary badge-pill"><?= $order->getCustomerIp() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Items Total') ?>
                                            <span class="badge badge-primary badge-pill"><?= $order->getItemsTotal() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Adjustments') ?>
                                            <span class="badge badge-primary badge-pill"><?= $order->getAdjustments() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Total') ?>
                                            <span class="badge badge-primary badge-pill"><?= $order->getTotal() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Tax Amount') ?>
                                            <span class="badge badge-primary badge-pill"><?= $order->getTaxAmount() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Currency') ?>
                                            <span class="badge badge-primary badge-pill"> <?= $order->getCurrency() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Notes') ?>
                                            <span class="badge badge-primary badge-pill"><?= $order->getNotes() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Total Credit') ?>
                                            <span class="badge badge-primary badge-pill"> <?= $order->getTotalCredit() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Created At') ?>
                                            <span class="badge badge-primary badge-pill"> <?= $order->getCreatedAt() ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <div class="col-xl-6 col-lg-6 col-sm-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $language::translate('Payment Detail') ?></h5>
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Payment Status') ?>
                                            <span class="badge badge-primary badge-pill"><?= $order->getPaymentStatus() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Payment Type') ?>
                                            <span class="badge badge-primary badge-pill"><?= $order->getPaymentType() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Payment Error') ?>
                                            <span class="badge badge-primary badge-pill"><?= $order->getPaymentError() ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $language::translate('Address Detail') ?></h5>
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Country') ?>
                                            <span class="badge badge-primary badge-pill"><?= $order->getCountry() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('City') ?>
                                            <span class="badge badge-primary badge-pill"><?= $order->getCity() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Address') ?>
                                            <span class="badge badge-primary badge-pill"><?= $order->getAddress() ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= $language::translate('Invoce') ?></h5>
                                <ul class="list-group">
                                    <?php
                                    /* @var \Pemm\Model\Invoice $invoice */
                                    $invoice = $order->getInvoice(); ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= $language::translate('Number') ?>
                                        <span class="badge badge-primary badge-pill"><?= (!empty($invoice) ? $invoice->getNumber() : '') ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= $language::translate('File') ?>
                                        <span class="badge badge-primary badge-pill">
                                                 <input id="invoice" name="invoice" type="file" class="file">
                                                <?php
                                                if (!empty($invoice = $order->getInvoice())) {?>
                                                    <a href="/admin/file/download?file=<?= $invoice->getFile() ?>" target="_blank" class="btn download-btn"><i class="ti-download"></i></a>
                                                <?php } ?>
                                            </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-sm-12">
                        <button type="submit" class="btn btn-primary" style="width: 100%">Save</button>
                    </div>
                </div>
                </form>
            </div>
			  <?php include("alt.php")?>
    </div>
     <?php include("js.php")?>
	     <script src="<?= SITE_URL ?>\plugins\bootstrap-select\bootstrap-select.min.js"></script>
        <script src="<?= SITE_URL ?>\plugins\bootstrap-toggle\bootstrap-toggle.min.js"></script>
     <!-- BEGIN PAGE LEVEL SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?= SITE_URL ?>\plugins\table\datatable\datatables.js"></script>
	</body>
<style>
    .badge-pill {
        border-radius: .25rem !important;
    }

    .download-btn:hover {
        box-shadow: none;
    }

    .list-group-item {
   background-color: #0e1726 !important;
   border: 1px solid #162338 !important;
}
</style>
</html>
