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
     <title><?= $language::translate('Charge Credit') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
	<link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="\plugins\bootstrap-select\bootstrap-select.min.css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\datatables.css">
    <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\dt-global_style.css">
    <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
    <link href="\assets\css\apps\invoice-list.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="\assets/css/themify-icons.css">
 <link rel="stylesheet" href="\assets/css/ie7/ie7.css">
     <link rel="stylesheet" type="text/css" href="\plugins\bootstrap-touchspin\jquery.bootstrap-touchspin.min.css">
    <style>
        #demo_vertical::-ms-clear, #demo_vertical2::-ms-clear { display: none; }
        input#demo_vertical { border-top-right-radius: 5px; border-bottom-right-radius: 5px; }
        input#demo_vertical2 { border-top-right-radius: 5px; border-bottom-right-radius: 5px; }
    </style>
  </head>
<body>
   	<?php include("ust2.php")?>

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
	<?php include("ust.php")?>
      <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">
                    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                        <div class="widget-content widget-content-area br-6" style="overflow-x: scroll">
                            <?php
                            /* @var Customer $customer */
                            $customer = $container->get('customer');
                            if ($customer->hasCustomerGroupPromotion()) { ?>
                                <div class="alert alert-success" role="alert">
                                    <i class="ti-check-box"></i> <?= $language::translate(
                                        'Special promotion available for you!'
                                    ) ?>
                                </div>
                            <?php
                            }
                            if ($customer->hasCustomerGroupBonusCredit()) { ?>
                                <div class="alert alert-success" role="alert">
                                    <i class="ti-check-box"></i>
                                    <?= $language::translate(':type:amount bonus credit for you', [
                                        ':amount' => $customer->getCustomerGroup()->getBonusCredit(),
                                        ':type' => $customer->getCustomerGroup()->getBonusCreditType(
                                        ) == 'percent' ? '%' : ''
                                    ]) ?>
                                </div>
                                <?php
                            }
                            ?>
                            <table id="product-list" class="table table-striped" style="min-width: 500px;">
                                <thead>
                                    <tr>
                                        <th><?= $language::translate('Package') ?></th>
                                        <th><?= $language::translate('Price') ?></th>
                                        <th><?= $language::translate('Add to Cart') ?></th>
                                     </tr>
                                </thead>
                                <tbody>
                                <?php
                                use Pemm\Model\Product;

                                $products = (new Product())->findBy(['filter' => ['status' => 1], 'order' => ['field' => 'sort_order', 'sort' => 'asc']]);

                                if (!empty($products)) {
                                    /* @var Product $product */
                                    foreach ($products as $product) {?>
                                        <tr>
                                            <td><a href=""><span class="inv-number"><?= $product->getCredit() ?> <?= $language::translate('Credit') ?>  </span></a></td>
                                            <td> <?php
                                                if ($customer->hasCustomerGroupPromotion()) {?>
                                                    <del class="text-danger"><?= $product->getPrice(
                                                            true,
                                                            false,
                                                            true,
                                                            true
                                                        ) ?></del><br>
                                                    <span class="text-success"><?= $product->getDiscountedPriceByCustomerGroupPromotion(
                                                            $customer,
                                                            true,
                                                            false,
                                                            true,
                                                            true
                                                        ) ?></span>
                                                <?php } else  {?>
                                                    <?= $product->getPrice(true, false, true, true) ?>
                                                <?php } ?></td>
                                            <td>
                                                <div class="">
                                                    <input id="charge-credit" type="text" value="" data-product="<?= $product->getId() ?>" name="charge-credit" class="input-sm">
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
                <div class="row layout-top-spacing">

                    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                        <div class="widget-content widget-content-area br-6" style="overflow-x: scroll">
                            <table id="cart-list" class="table table-striped" style="width:100%">
                                <thead>
                                <tr>
                                    <th><?= $language::translate('Credit') ?></th>
                                    <th><?= $language::translate('Date') ?></th>
                                    <th><?= $language::translate('Price') ?></th>
                                    <th><?= $language::translate('Quantity') ?></th>
                                    <th><?= $language::translate('Total') ?></th>
                                    <th><?= $language::translate('Operation') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                use Pemm\Model\Customer;
                                use Pemm\Model\Cart;

                                /* @var Customer $customer */
                                $customer = $container->get('customer');

                                if (!$customer->cart->isEmpty()) {
                                    $customer->cart->calculator();
                                    /* @var Cart $cart */
                                    foreach ($customer->cart->getList() as $cart) {
                                        ?>
                                        <tr>
                                            <td><span class="inv-number"><?= $cart->getProduct()->getCredit() ?> <?= $language::translate('Credit') ?>  </span></td>
                                            <td><span class="inv-date"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> <?= $cart->getCreatedAt() ?></span></td>
                                            <td> <?php
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
                                                <?php } ?></td>
                                            <td><span class="inv-amount"><?= $cart->getQuantity() ?>x</span></td>
                                            <td><span class="inv-amount"><?= $cart->getCartTotalPrice(true, true) ?></span></td>
                                            <td><span class="badge outline-badge-danger" style="cursor: pointer" onclick="deleteCart(<?= $cart->getId() ?>)"><?= $language::translate('Delete') ?></span></td>
                                        </tr>
                                    <?php }
                                }
                                ?>
                                <tr>
                                    <td colspan="4"></td>
                                    <td><span class="inv-number"><?= $language::translate('Total') ?> : </span></td>
                                    <td><span class="inv-amount">
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
                                        </span></td>
                                </tr>
                                <tr>
                                    <td colspan="4"></td>
                                    <td colspan="2"><a href="/panel/my-cart" class="btn btn-primary" style="width: 100%"><?= $language::translate('Pay') ?></a></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
			 <?php include("alt.php")?>
            </div>
			<?php include("js.php")?>
	     <script src="\plugins\bootstrap-select\bootstrap-select.min.js"></script>
     <!-- BEGIN PAGE LEVEL SCRIPTS -->
       <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="\plugins\table\datatable\datatables.js"></script>
    <script src="\plugins\table\datatable\button-ext\dataTables.buttons.min.js"></script>
    <script src="\assets\js\apps\invoice-list.js"></script>
	    <script src="\plugins\bootstrap-touchspin\jquery.bootstrap-touchspin.min.js"></script>
    <script src="\plugins\bootstrap-touchspin\custom-bootstrap-touchspin.js"></script>
          <script>

              $("input[name='charge-credit']").TouchSpin({
                  postfix: "<?= $language::translate('Add to Cart') ?>",
                  initval: 1,
                  min: 1,
                  max: 1000000000,
                  postfix_extraclass: "btn btn-outline-info",
                  buttondown_class: "btn btn-classic btn-primary",
              });

              $('[class*="bootstrap-touchspin-postfix"]').click(function(event) {
                  var $this = $(this);
                  var input = $this.parent().find('input');

                  if (input.val()) {
                      $('button').prop('disabled', true);
                      $.ajax({
                          method : 'post',
                          url : '/ajax/add-to-cart',
                          data : {
                              productId : input.data('product'),
                              quantity : input.val()
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
          </script>
<style>
.table-striped tbody tr:nth-of-type(odd) {
    background-color: transparent!important;
}
</style>
	</body>
</html>
