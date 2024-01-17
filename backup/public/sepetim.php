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

$customer = $container->get('customer');


$currency = $container->get('currency');



?>
<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('My Cart') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
	<link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="\plugins\bootstrap-select\bootstrap-select.min.css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\datatables.css">
    <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\dt-global_style.css">
	<link rel="stylesheet" href="\assets/css/themify-icons.css">
 <link rel="stylesheet" href="\assets/css/ie7/ie7.css">
     <link rel="stylesheet" type="text/css" href="\plugins\bootstrap-touchspin\jquery.bootstrap-touchspin.min.css">
    <style>
        #demo_vertical::-ms-clear, #demo_vertical2::-ms-clear { display: none; }
        input#demo_vertical { border-top-right-radius: 5px; border-bottom-right-radius: 5px; }
        input#demo_vertical2 { border-top-right-radius: 5px; border-bottom-right-radius: 5px; }
    </style>
    <link rel="stylesheet" type="text/css" href="\assets\css\widgets\modules-widgets.css">

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
                            <?php
                            /* @var Customer $customer */
                            $customer = $container->get('customer');
                                if ($customer->hasCustomerGroupPromotion()) {?>
                                    <div style="    margin: 10px;" class="alert alert-info" role="alert">
                                        <i class="ti-check-box"></i> <?= $language::translate(
                                            'Special promotion available for you!'
                                        ) ?>
                                    </div>
                                <?php }
                            if ($customer->hasCustomerGroupBonusCredit()) { ?>
                                <div style="    margin: 10px;" class="alert alert-success" role="alert">
                                    <i class="ti-check-box"></i>
                                    <?= $language::translate(':type:amount bonus credit for you', [
                                            ':amount' => $customer->getCustomerGroup()->getBonusCredit(),
                                            ':type' => $customer->getCustomerGroup()->getBonusCreditType() == 'percent' ? '%' : ''
                                    ]) ?>
                                </div>
                            <?php
                            }
                            ?>
                            <table id="multi-column-ordering" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
									    <th><?= $language::translate('Product') ?></th>
                                        <th><?= $language::translate('Unit price') ?></th>
                                        <th><?= $language::translate('Quantity') ?></th>
                                        <th><?= $language::translate('Total') ?></th>
                                        <th><?= $language::translate('Tax') ?></th>
                                        <th></th>
                                     </tr>
                                </thead>
                                <tbody>
                                <?php

                                use Pemm\Model\Customer;
                                use Pemm\Model\Cart;

                                if (!$customer->cart->isEmpty()) {

                                    $customer->cart->calculator();

                                    /* @var Cart $cart */
                                    $calculateSubTotal = 0;
                                    foreach ($customer->cart->getList() as $cart) { ?>
                                        <tr>
                                            <td><?= $cart->getProduct()->getName() ?>  </td>
                                            <td>
                                                <?php
                                                    if ($customer->hasCustomerGroupPromotion()) {?>
                                                        <del class="text-danger"><?= $cart->getProduct()->getPrice(
                                                                true,
                                                                false,
                                                                true,
                                                                true
                                                            ) ?></del><br>
                                                        <span class="text-success"><?= $cart->getProduct(
                                                            )->getDiscountedPriceByCustomerGroupPromotion(
                                                                $customer,
                                                                true,
                                                                false,
                                                                true,
                                                                true
                                                            ) ?></span>
                                                    <?php } else  {?>
                                                        <?= $cart->getProduct()->getPrice(true, false, true, true) ?>
                                                    <?php } ?>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input data-product="<?= $cart->getProduct()->getId() ?>" id="demo_vertical" type="text" name="demo_vertical" value="<?= $cart->getQuantity() ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <?= $cart->getCartTotalPriceExTax(true, true) ?>
                                            </td>
                                            <td>%<?= $cart->getProduct()->getTaxRate() ?></td>
                                            <td><button class="btn btn-primary btn-sm" onclick="deleteCart(<?= $cart->getId() ?>)">
                                                    <i class="ti-trash"></i>
                                                </button></td>
                                        </tr>
                                    <?php }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

	                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                        <div class="widget widget-four">
                            <div class="widget-heading">
                                <h5 class=""><?= $language::translate('Order Details') ?></h5>
                            </div>
                            <div class="widget-content">

                                <div class="order-summary">
                                <form action="/panel/checkout" method="post" id="checkout-form">
                                    <input type="hidden" name="payment_method" value="">
									<div class="form-row row mb-4">
									        <div class="col">
                                             <select class="form-control" name="country" id="country" required onchange="getCities($(this).val())">
                                                <option value=""><?= $language::translate('Select Country') ?></option>
                                                 <?php
                                                 foreach ((new Pemm\Model\Country())->findAll() as $country) {
                                                   echo '<option value="'.$country->getId().'" '.(($customer->getCountry() == $country->getId()) ? 'selected':'').'>'.$country->getName().'</option>';
                                                 }
                                                 ?>
                                            </select>
                                        </div>
                                        </div>
									<div class="form-row row mb-4 ">
									        <div class="col">
                                              <input type="text" name="city" class="form-control" id="city" value="<?= $customer->getCity() ?>">
                                        </div>
                                    </div>
									<div class="form-group mb-4">
                                            <label for="exampleFormControlTextarea1"><?= $language::translate('Address') ?></label>
                                            <textarea name="address" class="form-control"><?= $customer->getAddress() ?></textarea>
                                        </div>
 								</form>
                                </div>

                            </div>
                        </div>
                    </div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                        <div class="widget widget-four">
                            <div class="widget-heading">
                                <h5 class=""><?= $language::translate('Order Amount') ?></h5>

                            </div>
                            <div class="widget-content">

                                <div class="order-summary">

                                    <div class="summary-list summary-income">

                                        <div class="summery-info">

                                            <div class="w-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                            </div>

                                            <div class="w-summary-details">

                                                <div class="w-summary-info">
                                                    <h6><?= $language::translate('Subtotal') ?>
                                                        <span class="summary-count">
                                                            <?= $customer->cart->getListTotalPriceExTax(true, true) ?>
                                                        </span>
                                                    </h6>
                                                 </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="summary-list summary-profit">

                                        <div class="summery-info">

                                            <div class="w-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7" y2="7"></line></svg>
                                            </div>
                                            <div class="w-summary-details">

                                                <div class="w-summary-info">
                                                    <h6><?= $language::translate('Tax') ?>
                                                        <span class="summary-count">
                                                             <?= $customer->cart->getListTotalTax(true, true) ?>
                                                        </span>
                                                    </h6>
                                                 </div>

                                            </div>

                                        </div>

                                    </div>
                                    <?php
                                    if ($customer->hasCustomerGroupPromotion()) {?>
                                        <div class="summary-list summary-average">
                                            <div class="summery-info">
                                                <div class="w-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                         class="feather feather-tag">
                                                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                                        <line x1="7" y1="7" x2="7" y2="7"></line>
                                                    </svg>
                                                </div>
                                                <div class="w-summary-details">
                                                    <div class="w-summary-info">
                                                        <h6><?= $language::translate('Discount') ?>
                                                            <span class="summary-count">
                                                            - <?= $customer->cart->getListTotalAdjustments(
                                                                    true,
                                                                    true
                                                                ) ?>
                                                        </span>
                                                        </h6>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                    <?php }
                                    if ($customer->hasCustomerGroupBonusCredit()) {?>
                                    <div class="summary-list summary-average">
                                        <div class="summery-info">
                                            <div class="w-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-tag">
                                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                                    <line x1="7" y1="7" x2="7" y2="7"></line>
                                                </svg>
                                            </div>
                                            <div class="w-summary-details">
                                                <div class="w-summary-info">
                                                    <h6><?= $language::translate('Bonus Credit') ?>
                                                        <span class="summary-count text-success">
                                                            <?= $customer->cart->getBonusCredit() ?>
                                                        </span>
                                                    </h6>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                    <?php }
                                    ?>
                                    <div class="summary-list summary-expenses">

                                        <div class="summery-info">

                                            <div class="w-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                            </div>
                                            <div class="w-summary-details">

                                                <div class="w-summary-info">
                                                    <h6><?= $language::translate('Grand Total') ?>
                                                        <span class="summary-count">
                                                            <?php
                                                                if ($customer->hasCustomerGroupPromotion()) {?>
                                                                    <?= $customer->cart->priceRenderer(
                                                                        $customer->cart->getListTotalPrice(
                                                                            false,
                                                                            false
                                                                        ) -
                                                                        $customer->cart->getListTotalAdjustments(
                                                                            false,
                                                                            false
                                                                        ), true, true
                                                                    )
                                                                    ?>
                                                                <?php } else { ?>
                                                                    <?= $customer->cart->getListTotalPrice(
                                                                        true,
                                                                        true
                                                                    ) ?>
                                                                <?php }
                                                            ?>
                                                        </span>
                                                    </h6>
                                                 </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-four">
                            <div class="widget-heading">
                                <h5 class=""><?= $language::translate('Payment Method') ?></h5>
                            </div>
                            <div style="padding-top: 33px;padding-bottom: 33px;" class="widget-content">
                                 <div class="row">
                                     <?php if($setting->getPaypal_status()) { ?>
                                     <div class="col-md-4 col-sm-4 col-12 payment-method" style="text-align:center;" data-method="paypal" >
                                         <img class="img-fluid" style="     margin-bottom: 73px;   width: 183px;" src="\assets/img/paypal.png">
                                     </div>
                                     <?php } ?>
                                     <?php if($setting->getIyzico_status()) { ?>
                                    <div class="col-md-4 col-sm-4 col-12 payment-method" style="text-align:center;" data-method="master" >
                                        <img class="img-fluid" style="      margin-bottom: 73px;  width: 183px;" src="\assets/img/master.png">
                                    </div>
                                     <?php } ?>
                                     <?php if($setting->getMollie_status()) { ?>
                                    <div class="col-md-4 col-sm-4 col-12 payment-method" style="text-align:center;" data-method="mollie">
                                        <img class="img-fluid" style="      margin-bottom: 73px;  width: 183px;" src="\assets/img/mollie.png">
                                    </div>
                                     <?php } ?>
                                     <?php if($setting->getStripe_status()) { ?>
                                     <div class="col-md-4 col-sm-4 col-12 payment-method" style="text-align:center;" data-method="stripe">
                                         <img class="img-fluid" style="      margin-bottom: 73px;     width: 253px;" src="\assets/img/stripe.png">
                                     </div>
                                     <?php } ?>
                                     <?php if(!empty($setting->getbtcpayserver_storeid())) { ?>
                                     <div class="col-md-4 col-sm-4 col-12 payment-method" style="text-align:center;" data-method="btcpayserver">
                                         <img class="img-fluid" style="     margin-bottom: 73px;   width: 183px;" src="\assets/img/btcpayserver.png">
                                     </div>
                                     <?php } ?>
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
     <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="\plugins\table\datatable\datatables.js"></script>
        <style>
            .payment-method {
                cursor: pointer;
                position: relative;
                -webkit-transform: translateY(0);
                transition: all .6s cubic-bezier(0.165, 0.84, 0.44, 1);
            }

            .payment-method:hover {
                transform: scale(1.25, 1.25);
            }

        </style>
    <script>

        $('.payment-method').on('click', function () {
            $('input[name="payment_method"]').val($(this).data('method'));
            $('#checkout-form').submit();
        });

        $('#demo_vertical').on('change', function() {
            var $this = $(this);
            if ($this.val()) {
                $('button').prop('disabled', true);
                $.ajax({
                    method : 'post',
                    url : '/ajax/add-to-cart',
                    data : {
                        productId : $this.data('product'),
                        quantity : $this.val()
                    },
                    success: function (response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function (error) {}
                })
                $('button').prop('disabled', false);
            }
        });

        function deleteCart(cartId)
        {
            $('button').prop('disabled', true);
            $.ajax({
                method : 'post',
                url : '/ajax/delete-cart',
                data : {
                    cartId : cartId
                },
                success: function (response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function (error) {}
            })
            $('button').prop('disabled', false);
        }

        function getCities(country) {
            $('button').prop('disabled', true);
            $.ajax({
                url : '/ajax/city-list',
                data : {
                    country : country
                },
                success: function (response) {
                    var selectOptions = '<option value=""><?= $language::translate('Select City') ?></option>';
                    if (response.success) {
                        response.cities.forEach(function (city) {
                            selectOptions += '<option value="' + city.id + '">' + city.name + '</option>'
                        })
                        $('#city').html(selectOptions);
                    }
                },
                error: function (error) {}
            })
            $('button').prop('disabled', false);
        }
    </script>
	<style>
	div.dataTables_wrapper div.dataTables_info {
    display: none;
}
</style>
<style media="screen">
.table-striped tbody tr:nth-of-type(odd) {
background-color: #060818!important;
}
</style>
    <script src="\plugins\table\datatable\datatables.js"></script>
    <script src="\plugins\table\datatable\button-ext\dataTables.buttons.min.js"></script>
    <script src="\assets\js\apps\invoice-list.js"></script>
	    <script src="\plugins\bootstrap-touchspin\jquery.bootstrap-touchspin.min.js"></script>
    <script src="\plugins\bootstrap-touchspin\custom-bootstrap-touchspin.js"></script>

	</body>
</html>
