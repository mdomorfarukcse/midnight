<?php

use Pemm\Core\Container;
use Pemm\Model\Order;
use Pemm\Model\Customer;
use Pemm\Model\Setting;

global $container;

$setting = (new Setting())->find(1);

/* @var Customer $customer */
$customer = $container->get('customer');

$orders = (new Order())->findBy(['filter' => ['customer_id' => $customer->getId()]]);
$dateTime = new \DateTime();

$language = $container->get('language');

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('Credit history') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
	<link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\datatables.css">
    <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\dt-global_style.css">
    <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
    <link href="\assets\css\apps\invoice-list.css" rel="stylesheet" type="text/css">
  </head>
<body>
   	<?php include("ust2.php")?>

    <div class="main-container" id="container">
	<?php include("ust.php")?>
         <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">

                    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                        <div class="widget-content widget-content-area br-6" style="overflow-x: scroll">
                            <table class="table table-hover" style="width:100%">
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
                                                <td><span class="badge outline-badge-primary"><?= $order->getTotalCredit() ?> CRD</span></td>
                                                <td><span class="inv-amount"><?= $order->getTotal(true, true) ?></span></td>
                                                <td><span class="badge badge-success inv-status"><?=$language::translate($order->getPaymentStatus()) ?></span></td>
                                                <td>
                                                    <span class="badge outline-badge-success">
                                                        <?php
                                                        if (!empty($invoice = $order->getInvoice())) {?>
                                                            <a href="/panel/invoice/detail/<?= $invoice->getId() ?>"><?=$language::translate($invoice->getStatus()) ?> </a>
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

                </div>
				     <?php include("alt.php")?>
            </div>
     <?php include("js.php")?>
	         <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="\assets\js\custom.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="\plugins\table\datatable\datatables.js"></script>
    <script src="\plugins\table\datatable\button-ext\dataTables.buttons.min.js"></script>
    <script src="\assets\js\apps\invoice-list.js"></script>

	</body>
</html>
