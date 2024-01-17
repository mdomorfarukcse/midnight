<?php

use Pemm\Core\Container;
use Pemm\Model\Order;
use Pemm\Model\Customer;
use Pemm\Model\Invoice;
use Pemm\Model\OrderItem;
use Pemm\Model\Currency;
use Pemm\Model\Setting;

global $container;

$setting = (new Setting())->find(1);

/* @var Customer $customer */
$customer = $container->get('customer');

/* @var Customer $customer */
$currency = $container->get('currency');

/* @var Setting $setting */
$setting = $container->get('setting');

/* @var Invoice $invoice */
$invoice = $container->get('invoice');
$invoice->getCustomer();

$datetime = DateTime::createFromFormat('Y-m-d H:i:s', $invoice->getCreatedAt())->format('d M Y');

$language = $container->get('language');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $language::translate('Invoice') ?> - <?= SITE_NAME ?> </title>
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
            <div class="row invoice layout-top-spacing layout-spacing">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="doc-container">
                        <div class="row">
                            <div class="col-xl-9">
                                <div class="invoice-container">
                                    <div class="invoice-inbox">
                                        <div id="ct" class="">
                                            <div class="invoice-00001">
                                                <div class="content-section">
                                                    <div class="inv--head-section inv--detail-section">
                                                        <div class="row">
                                                            <div class="col-sm-6 col-12 ">
                                                                  <img style="width: 210px; margin-bottom: 15px; " src="<?=$setting->getsiteUrl();?>/assets/img/<?=$setting->getLogo2();?>">
                                                                <div class="d-flex">
                                                                   <h3 style="    margin-left: 0;" class="in-heading align-self-center"><?= SITE_NAME ?></h3>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 text-sm-right">
                                                                <p class="inv-list-number"><span class="inv-title"><?= $language::translate('Invoice') ?> : </span> <span class="inv-number"><?= $invoice->getNumber() ?></span></p>
                                                            </div>
                                                            <div class="col-sm-6 align-self-center  ">
                                                                <p class="inv-street-addr" style="width: 50%"><?= $setting->getAddress() ?></p>
                                                                <p class="inv-email-address"><?= $setting->getEmail() ?></p>
                                                                <p class="inv-email-address"><?= $setting->getPhone() ?></p>
                                                            </div>
                                                            <div class="col-sm-6 align-self-center mt-3 text-sm-right">
                                                                <p class="inv-created-date"><span class="inv-title"><?= $language::translate('Invoice Date') ?> : </span> <span class="inv-date"><?= $datetime ?></span></p>
                                                                <p class="inv-due-date"><span class="inv-title"><?= $language::translate('Due Date') ?> : </span> <span class="inv-date"><?= $datetime ?></span></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="inv--detail-section inv--customer-detail-section">

                                                        <div class="row">

                                                            <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4 align-self-center">
                                                                <p class="inv-to"><?= $language::translate('Invoice To') ?></p>
                                                            </div>



                                                            <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4">
                                                                <p class="inv-customer-name"><?= $invoice->customer->getFullName(); ?></p>
                                                                <p class="inv-street-addr"><?= $invoice->getOrder()->getAddress() . ' ' . $invoice->getOrder()->getCity() . ' ' . $invoice->getOrder()->getCountry() ?></p>
                                                                <p class="inv-email-address"><?= $invoice->customer->getEmail(); ?></p>
                                                                <p class="inv-email-address"><?= $invoice->customer->getContactNumber(); ?></p>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="inv--product-table-section">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead class="">
                                                                <tr>
                                                                    <th scope="col"> <?= $language::translate('Row') ?></th>
                                                                    <th scope="col"><?= $language::translate('Items') ?></th>
                                                                    <th class="text-right" scope="col"><?= $language::translate('Qty') ?></th>
                                                                    <th class="text-right" scope="col"><?= $language::translate('Price') ?></th>
                                                                    <th class="text-right" scope="col"><?= $language::translate('Amount') ?></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $orderItems = (new OrderItem())->findBy(['filter' => ['order_id' => $invoice->getOrder()->getId()]]);
                                                                /* @var OrderItem $orderItem */
                                                                foreach ($orderItems as $key => $orderItem) {?>
                                                                    <tr>
                                                                        <td><?= $key ?></td>
                                                                        <td><?= $orderItem->getProductName() ?></td>
                                                                        <td class="text-right"><?= $orderItem->getQuantity() ?></td>
                                                                        <td class="text-right"><?= $currency->get($invoice->getOrder()->getCurrency())->getSymbol() . ($orderItem->getUnitsTotal() / $orderItem->getQuantity()) ?></td>
                                                                        <td class="text-right"><?= $orderItem->getUnitsTotal($invoice->getOrder()->getCurrency(), true, true) ?></td>
                                                                    </tr>
                                                                <?php }?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="inv--total-amounts">

                                                        <div class="row mt-4">
                                                            <div class="col-sm-5 col-12 order-sm-0 order-1">
                                                            </div>
                                                            <div class="col-sm-7 col-12 order-sm-1 order-0">
                                                                <div class="text-sm-right">
                                                                    <div class="row">
                                                                        <div class="col-sm-8 col-7">
                                                                            <p class=""><?= $language::translate('Sub Total') ?> : </p>
                                                                        </div>
                                                                        <div class="col-sm-4 col-5">
                                                                            <p class=""><?= $invoice->getOrder()->getItemsTotal(true, true) ?></p>
                                                                        </div>
                                                                        <div class="col-sm-8 col-7">
                                                                            <p class=""><?= $language::translate('Tax Amount') ?> : </p>
                                                                        </div>
                                                                        <div class="col-sm-4 col-5">
                                                                            <p class="">
                                                                                <?= $invoice->getOrder()->getTaxAmount(true, true) ?>
                                                                            </p>
                                                                        </div>
                                                                        <?php
                                                                            if ($invoice->getOrder()->getAdjustments() > 0) {?>
                                                                                <div class="col-sm-8 col-7">
                                                                                    <p class=""><?= $language::translate('Adjustments') ?> : </p>
                                                                                </div>
                                                                                <div class="col-sm-4 col-5">
                                                                                    <p class="">
                                                                                        - <?= $invoice->getOrder()->getAdjustments(true, true) ?>
                                                                                    </p>
                                                                                </div>
                                                                            <?php }
                                                                        ?>
                                                                        <div class="col-sm-8 col-7 grand-total-title">
                                                                            <h4 class=""><?= $language::translate('Grand Total') ?> : </h4>
                                                                        </div>
                                                                        <div class="col-sm-4 col-5 grand-total-amount">
                                                                            <h4 class=""><?= $invoice->getOrder()->getTotal(true, true) ?> </h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="inv--note">

                                                        <div class="row mt-4">
                                                            <div class="col-sm-12 col-12 order-sm-0 order-1">
                                                                <p><?= $language::translate('Note: Thank you for doing Business with us.') ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3">
                                <div class="invoice-actions-btn">
                                    <div class="invoice-action-btn">
                                        <div class="row">

                                            <div class="col-xl-12 col-md-3 col-sm-6">
                                                    <a href="javascript:void(0);" class="btn btn-secondary btn-print  action-print"><?= $language::translate('Download') ?></a>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include("alt.php")?>
    </div>
    <?php include("js.php")?>
    <script src="\plugins\bootstrap-select\bootstrap-select.min.js"></script>
    <script src="/assets/js/html2canvas.js"></script>
    <script src="/assets/js/jspdf.js"></script>
    <script src="/assets/js/apps/invoice-preview.js"></script>

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script>

        var invoiceNumber = "<?= $invoice->getNumber() ?>";

        function downloadInvoice () {
            var current = new Date($.now());

            html2canvas($('.invoice-container'), {
                background: '#fff',
                onrendered: function (canvas) {
                    var myImage = canvas.toDataURL("image/jpeg, 1.0");
                    var imgWidth =  (canvas.width * 135) / 220;
                    var imgHeight = (canvas.height * 100) / 150;
                    var pdf = new jsPDF('p', 'pt', 'a4');
                    pdf.addImage(myImage, 'png', 0, 0, imgWidth, imgHeight);
                    pdf.save('invoice-' + invoiceNumber + '.pdf');
                }
            });
        }
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
    <style>
        .actions-btn-tooltip.tooltip {
            opacity: 1;
            top: -11px!important;
        }
        .actions-btn-tooltip .arrow:before {
            border-top-color: #3b3f5c;
        }
        .actions-btn-tooltip .tooltip-inner {
            background: #3b3f5c;
            color: #fff;
            font-weight: 700;
            border-radius: 30px;
            box-shadow: 0px 5px 15px 1px rgba(113, 106, 202, 0.2);
            padding: 4px 16px;
        }
        .invoice-container {
            width: 100%;
        }
        .invoice-inbox {
            padding: 1;
            background-color: #ffffff;
            box-shadow: rgb(145 158 171 / 24%) 0px 0px 2px 0px, rgb(145 158 171 / 24%) 0px 16px 32px -4px;
            border-radius: 6px;
        }
        .invoice-inbox .inv-number {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 0;
            color: #888ea8;
        }
        .invoice-inbox .invoice-action svg {
            cursor: pointer;
            font-weight: 600;
            color: #888ea8;
            margin-right: 6px;
            vertical-align: middle;
            fill: rgba(0, 23, 55, 0.08);
        }
        .invoice-inbox .invoice-action svg:not(:last-child) {
            margin-right: 15px;
        }
        .invoice-inbox .invoice-action svg:hover {
            color: #4361ee;
            fill: rgba(27, 85, 226, 0.23921568627450981);
        }

        /*
        ===================

             Invoice

        ===================
        */

        /*    Inv head section   */

        .invoice .content-section .inv--head-section {
            padding: 36px 35px;
            margin-bottom: 40px;
            padding-bottom: 25px;
            border-bottom: 1px solid #ebedf2;
        }

        .inv--customer-detail-section {
            padding: 36px 35px;
            padding-top: 0;
        }

        .invoice .content-section .inv--head-section h3.in-heading {
          font-size: 18px;
          font-weight: 600;
          color: #ffffff;
          margin: 0;
          margin-left: 0px;
         }
        .invoice .content-section .inv--head-section .company-logo {
            width: 36px;
            height: 36px;
        }
        .invoice .content-section .inv--head-section div.company-info {
            display: flex;
            justify-content: flex-end;
        }
        .invoice .content-section .inv--head-section div.company-info svg {
            width: 42px;
            height: 42px;
            margin-right: 10px;
            color: #4361ee;
            fill: rgba(27, 85, 226, 0.23921568627450981);
        }
        .invoice .content-section .inv--head-section .inv-brand-name {
            font-size: 23px;
            font-weight: 600;
            margin-bottom: 0;
            align-self: center;
        }

        /*    Inv detail section    */

        .invoice .content-section .inv--detail-section .inv-to {
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 15px;
        }
        .invoice .content-section .inv--detail-section .inv-customer-name {
            font-weight: 700;
            margin-bottom: 2px;
            font-size: 13px;
            color: #4361ee;
        }
        .invoice .content-section .inv--detail-section .inv-detail-title {
            font-weight: 700;
            margin-bottom: 0;
            font-size: 15px;
            margin-bottom: 15px;
        }
        .invoice .content-section .inv--detail-section .inv-details {
            font-weight: 700;
            margin-bottom: 15px;
        }
        .invoice .content-section .inv--detail-section .inv-street-addr {
            font-weight: 600;
            margin-bottom: 2px;
            font-size: 13px;
        }
        .invoice .content-section .inv--detail-section .inv-email-address {
            font-weight: 600;
            margin-bottom: 2px;
            font-size: 13px;
        }

        /*inv-list-number*/
        .invoice .content-section .inv--detail-section .inv-list-number {
            margin-bottom: 2px;
        }
        .invoice .content-section .inv--detail-section .inv-list-number .inv-title {
            font-weight: 400;
            font-size: 20px;
        }
        .invoice .content-section .inv--detail-section .inv-list-number .inv-number {
            font-weight: 400;
            font-size: 18px;
            color: #4361ee;
        }

        /*inv-created-date*/
        .invoice .content-section .inv--detail-section .inv-created-date {
            margin-bottom: 2px;
        }
        .invoice .content-section .inv--detail-section .inv-created-date .inv-title {
            font-weight: 700;
            font-size: 13px;
        }
        .invoice .content-section .inv--detail-section .inv-created-date .inv-date {
            font-size: 13px;
            font-weight: 600;
        }

        /*inv-due-date*/
        .invoice .content-section .inv--detail-section .inv-due-date {
            margin-bottom: 2px;
        }
        .invoice .content-section .inv--detail-section .inv-due-date .inv-title {
            font-weight: 700;
            font-size: 13px;
        }
        .invoice .content-section .inv--detail-section .inv-due-date .inv-date {
            font-size: 13px;
            font-weight: 600;
        }

        /*    Inv product table section    */
        .invoice .content-section .inv--product-table-section {
            padding: 30px 0;
        }
        .invoice .content-section .inv--product-table-section table {
            margin-bottom: 0;
        }
        .invoice .content-section .inv--product-table-section thead tr {
            border: none;
        }
        .invoice .content-section .inv--product-table-section th {
            padding: 9px 22px;
            font-size: 11px!important;
            border: none;
            border-top: 1px solid #e0e6ed;
            border-bottom: 1px solid #e0e6ed;
            color: #515365!important;
        }
        .invoice .content-section .inv--product-table-section th:first-child {
            padding-left: 35px;
        }
        .invoice .content-section .inv--product-table-section th:last-child {
            padding-right: 35px;
        }
        .invoice .content-section .inv--product-table-section tr td:first-child {
            padding-left: 35px;
        }
        .invoice .content-section .inv--product-table-section tr td:last-child {
            padding-right: 35px;
        }
        .invoice .content-section .inv--product-table-section td {
            color: #515365;
            font-weight: 600;
            border: none;
            padding: 10px 25px;
            vertical-align: top!important;
        }
        .invoice .content-section .inv--product-table-section tbody tr:nth-of-type(even) td {
          background-color: #060818;
        }

        /*inv--payment-info*/
        .invoice .content-section .inv--payment-info {
            font-size: 13px;
            font-weight: 600;
        }
        .invoice .content-section .inv--payment-info .inv-title {
            color: #4361ee;
            font-weight: 600;
            margin-bottom: 15px;
            width: 65%;
            margin-left: auto;
        }
        .invoice .content-section .inv--payment-info p {
            margin-bottom: 0;
            display: flex;
            width: 65%;
            margin-left: auto;
            justify-content: space-between;
        }
        .invoice .content-section .inv--payment-info span {
            font-weight: 500;
            display: inline-block;
            white-space: nowrap;
        }
        .invoice .content-section .inv--payment-info .inv-subtitle {
            font-weight: 600;
            font-size: 13px;
            display: inline-block;
            white-space: normal;
            margin-right: 4px;
        }

        /*inv--total-amounts*/
        .invoice .content-section .inv--total-amounts {
            padding: 0 35px;
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #ebedf2;
        }
        .invoice .content-section .inv--total-amounts .grand-total-title h4 {
            position: relative;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 0;
            padding: 0;
            color: #0e1726;
            display: inline-block;
            letter-spacing: 1px;
        }
        .invoice .content-section .inv--total-amounts .grand-total-amount h4 {
            position: relative;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 0;
            padding: 0;
            color: #0e1726;
            display: inline-block;
            letter-spacing: 1px;
        }

        /*inv--note*/
        .inv--note {
            padding: 0 25px;
            padding-bottom: 25px;
        }
        .inv--note p {
            margin-bottom: 0;
            font-weight: 600;
            color: #888ea8;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            #ct, #ct * {
                visibility: visible;
            }
            .doc-container {
                position: absolute;
                left: 0;
                right: 0;
                top: 0;
            }
        }

        @page { size: auto;  margin: 0mm; }

        /*
        ===============================
            Invoice Actions Button
        ===============================
        */

        .invoice-actions-btn {
            padding: 25px;
            padding-top: 32px;
            padding-bottom: 32px;
            background-color: #ffffff;
            box-shadow: rgb(145 158 171 / 24%) 0px 0px 2px 0px, rgb(145 158 171 / 24%) 0px 16px 32px -4px;
            border-radius: 6px;
        }
        .invoice-actions-btn label {
            font-size: 14px;
            font-weight: 600;
            color: #515365;
        }

        /* Invoice Actions -> action-btn */

        .invoice-actions-btn .invoice-action-btn a {
            -webkit-transform: none;
            transform: none;
        }
        .invoice-actions-btn .invoice-action-btn a.btn-send {
            width: 100%;
            margin-bottom: 20px;
        }
        .invoice-actions-btn .invoice-action-btn a.btn-print {
            width: 100%;
            margin-bottom: 20px;
        }
        .invoice-actions-btn .invoice-action-btn a.btn-download {
            width: 100%;
            margin-bottom: 20px;
        }
        .invoice-actions-btn .invoice-action-btn a.btn-edit {
            width: 100%;
        }
        @media (max-width: 1199px) {
            .invoice-actions-btn {
                margin-top: 25px;
            }
            .invoice-actions-btn .invoice-action-btn a.btn-send {
                margin-bottom: 0;
            }
            .invoice-actions-btn .invoice-action-btn a.btn-print {
                margin-bottom: 0;
            }
            .invoice-actions-btn .invoice-action-btn a.btn-download {
                margin-bottom: 0;
            }
        }
        @media (max-width: 767px) {
            .invoice-actions-btn .invoice-action-btn a.btn-send {
                margin-bottom: 20px;
            }
            .invoice-actions-btn .invoice-action-btn a.btn-print {
                margin-bottom: 20px;
            }
        }
        @media (max-width: 575px) {
            .invoice .content-section .inv--payment-info .inv-title {
                margin-top: 25px;
            }
            .invoice .content-section .inv--payment-info .inv-title {
                margin-left: 0;
                margin-right: auto;
                margin-bottom: 6px;
                width: auto;
            }
            .invoice .content-section .inv--payment-info p {
                margin-left: 0;
                margin-right: auto;
                width: auto;
                justify-content: flex-start;
            }
            .invoice .content-section .inv--payment-info .inv-subtitle {
                min-width: 140px;
            }
            .invoice-actions-btn .invoice-action-btn a.btn-download {
                margin-bottom: 20px;
            }
            .invoice .content-section .inv--payment-info span {
                white-space: normal;
            }

        }
    </style>
</body>
</html>
