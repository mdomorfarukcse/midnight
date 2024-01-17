<?php

use Pemm\Core\Container;
use Pemm\Core\Language;
use Pemm\Model\Customer;
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
     <title><?= $language::translate('Price List') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
	<link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link href="\assets\css\pages\faq\faq.css" rel="stylesheet" type="text/css">
  </head>
<body>
   	<?php include("ust2.php")?>

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
	<?php include("ust.php")?>
         <div style="background: #0e1726;" id="content" class="main-content">
             <div class="layout-px-spacing">

                <div class="fq-header-wrapper">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 align-self-center order-md-0 order-1">
                                <h1 class=""><?= $language::translate('Price List') ?></h1>
                                <p class=""><?= $language::translate('Price List and Credit Amounts are listed below.') ?></p>
                                <a href="/panel/buy-credit" class="btn"><?= $language::translate('Buy Credit') ?></a>
                            </div>
                            <div class="col-md-6 order-md-0 order-0">
                                <div class="banner-img">
                                    <img src="\assets\img\faq.svg" class="d-block" alt="header-image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="faq container">

                    <div class="faq-layouting layout-spacing">
                        <div  style="    margin-bottom: 0px;" class="fq-comman-question-wrapper">

                         <div class="row">
                              <div class="col-md-12">
                                  <h3 style="    margin-bottom: 11px;"><?= $language::translate('Price List') ?></h3>
                                  <?php
                                  /** @var Customer $customer */
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
                                  <ul class="row">
                                      <?php
                                      use Pemm\Model\Product;
                                      $products = (new Product())->findBy(["order"=>["field"=>"sort_order","sort"=>"asc"]]);
                                      /* @var Product $product */
                                      foreach ($products as $product) {
                  if($product->getStatus()==0) continue;
                  ?>
                                          <li class="list-unstyled col-md-3">
                                              <?php
                                              if(!$product->getDiscountStatus()){
                                              ?>
                                              <div class="icon-svg">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                              </div>

                                                  <?php
                                                  if ($customer->hasCustomerGroupBonusCredit()) {?>
                                                      <del class="text-danger"><?= $product->getCredit() . ' ' .  $language::translate('Credit') ?></del>
                                                      <span class="text-success"><?= $product->getCredit(
                                                      ) + $customer->calculateBonusCreditForCustomerGroup(
                                                          $product->getCredit()
                                                      ) . ' ' . $language::translate('Credit') ?></span>
                                                  <?php } else { ?>
                                                      <?= $product->getCredit() . ' ' . $language::translate('Credit') ?>
                                                  <?php } ?>
                                                  -  <?php
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
                                                  <?php } ?>
                                              <?php }else { ?>

                                                  <div class="icon-svg">
                                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                  </div>
                                                  <?php $currencies = $container->get('currency')->getAll(); ?>
                                                  <?= $product->getName() ?>   -  <span style="text-decoration:line-through;"><?= $product->getPrice(true, true, true,true)?></span>  <?= $currencies[$session->get('currency')]->getSymbol() . ($product->getPrice(true, true, false) - $product->getDiscountedPrice()) ?>
                                              <?php } ?>

                                          </li>
                                      <?php }
                                      ?>
                                  </ul>
                              </div>
                          </div>
                        </div>

                        <div class="fq-tab-section">
                            <div class="row">
                                <div class="col-md-12 mb-5 mt-5">
                                    <h2><?= $language::translate('Tuning Prices') ?></h2>

                                    <div class="accordion" id="faq">

                                        <?php
                                        use Pemm\Model\Tuning;
                                        use Pemm\Model\TuningAdditionalOption;
                                        $tunings = (new Tuning())->findBy(['filter' => ['is_active' => 1]]);
                                        /* @var Tuning $tuning */
                                        foreach ($tunings as $tuning) {?>
                                            <div class="card">
                                                <div class="card-header" id="fqheadingFour">
                                                    <div class="mb-0" data-toggle="collapse" role="navigation" data-target="#tuning-<?= $tuning->getId() ?>" aria-expanded="false" aria-controls="fqcollapseFour">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-code">
                                                            <polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline>
                                                        </svg>
                                                        <span class="faq-q-title"><?= $tuning->getName() ?> - <?= $tuning->getCredit() ?> <?= $language::translate('Credit') ?></span>
                                                    </div>
                                                </div>
                                                <div id="tuning-<?= $tuning->getId() ?>" class="collapse" aria-labelledby="fqheadingFour" data-parent="#faq">
                                                    <div class="card-body">
                                                        <ul class="row">
                                                            <?php
                                                            /* @var TuningAdditionalOption $tuningAdditionalOption */
                                                            foreach ($tuning->getOptions() as $tuningAdditionalOption) {
                                                                if (!$tuningAdditionalOption->isActive()) continue;
                                                                ?>
                                                                <li class="list-unstyled  col-md-4">
                                                                    <div class="row">
                                                                        <div style="margin-bottom:5px; color:#fff; background: #0e1726" class="col-md-6 badge badge-dark  "><?= $tuningAdditionalOption->additionalOption->getName() ?></div>
                                                                        <div style="margin-bottom:5px;     color: #ffffff;   background-color: #060818; "class="col-md-6 badge badge-light">+<?= $tuningAdditionalOption->getCredit() ?></div>
                                                                    </div>
                                                                </li>
                                                            <?php }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                        ?>
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
     <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="\plugins\table\datatable\datatables.js"></script>
    <script>
        $('#multi-column-ordering').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<'table-responsive'tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "  ",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Arama...",
               "sLengthMenu": "Gösterge :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [10, 20, 40, 50],
            "pageLength": 10,
	        columnDefs: [ {
	            targets: [ 0 ],
	            orderData: [ 0, 1 ]
	        }, {
	            targets: [ 1 ],
	            orderData: [ 1, 0 ]
	        }, {
	            targets: [ 4 ],
	            orderData: [ 4, 0 ]
	        } ]
	    });
    </script>
	<style>
	div.dataTables_wrapper div.dataTables_info {
    display: none;
}
.badge-dark {
    color: #fff;
    background-color: #3b3f5c;
    border-radius: 0;
}

.table > thead > tr > th {
    color: #4361ee;
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-align: center;
}
.table > tbody > tr > td {
    vertical-align: middle;
    color: #515365;
    font-size: 13px;
    letter-spacing: 1px;
    text-align: center;
}

.badge {
    font-weight: 600;
    line-height: 1.4;
    padding: 13px 6px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.3s ease-out;
    -webkit-transition: all 0.3s ease-out;
}

.price_plan_area {
    position: relative;
    z-index: 1;
 }

.single_price_plan {
    position: relative;
    z-index: 1;
    border-radius: 0.5rem 0.5rem 0 0;
    -webkit-transition-duration: 500ms;
    transition-duration: 500ms;
    margin-bottom: 50px;
    background-color: #ffffff;
    padding: 2rem 2rem;
}
@media only screen and (min-width: 992px) and (max-width: 1199px) {
    .single_price_plan {
        padding: 3rem;
    }
}
@media only screen and (max-width: 575px) {
    .single_price_plan {
        padding: 3rem;
    }
}
.single_price_plan::after {
    position: absolute;
    content: "";
    background-image: url("https://bootdey.com/img/half-circle-pricing.png");
    background-repeat: repeat;
    width: 100%;
    height: 17px;
    bottom: -17px;
    z-index: 1;
    left: 0;
}
.single_price_plan .title {
    text-transform: capitalize;
    -webkit-transition-duration: 500ms;
    transition-duration: 500ms;
    margin-bottom: 0.2rem;
}
.single_price_plan .title span {
    color: #ffffff;
    padding: 0.2rem 0.6rem;
    font-size: 12px;
    text-transform: uppercase;
    background-color: #2ecc71;
    display: inline-block;
    margin-bottom: 0.5rem;
    border-radius: 0.25rem;
}
.single_price_plan .title h3 {
    font-size: 1.25rem;
}
.single_price_plan .title p {
    font-weight: 300;
    line-height: 1;
    font-size: 14px;
}
.single_price_plan .title .line {
    width: 80px;
    height: 4px;
    border-radius: 10px;
    background-color: #3f43fd;
}
.single_price_plan .price {
    margin-bottom: 1.5rem;
}
.single_price_plan .price h4 {
    position: relative;
    z-index: 1;
    font-size: 2.4rem;
    line-height: 1;
    margin-bottom: 0;
    color: #3f43fd;
    display: inline-block;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-color: transparent;
    background-image: -webkit-gradient(linear, left top, right top, from(#e24997), to(#2d2ed4));
    background-image: linear-gradient(90deg, #e24997, #2d2ed4);
}
.single_price_plan .description {
    position: relative;
    margin-bottom: 1.5rem;
}
.single_price_plan .description p {
    line-height: 16px;
    margin: 0;
    padding: 10px 0;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -ms-grid-row-align: center;
    align-items: center;
}
.single_price_plan .description p i {
    color: #2ecc71;
    margin-right: 0.5rem;
}
.single_price_plan .description p .lni-close {
    color: #e74c3c;
}
.single_price_plan.active,
.single_price_plan:hover,
.single_price_plan:focus {
    -webkit-box-shadow: 0 6px 50px 8px rgba(21, 131, 233, 0.15);
    box-shadow: 0 6px 50px 8px rgba(21, 131, 233, 0.15);
}
.single_price_plan .side-shape img {
    position: absolute;
    width: auto;
    top: 0;
    right: 0;
    z-index: -2;
}

.section-heading h3 {
    margin-bottom: 1rem;
    font-size: 3.125rem;
    letter-spacing: -1px;
}

.section-heading p {
    margin-bottom: 0;
    font-size: 1.25rem;
}

.section-heading .line {
    width: 120px;
    height: 5px;
    margin: 30px auto 0;
    border-radius: 6px;
    background: #2d2ed4;
    background: -webkit-gradient(linear, left top, right top, from(#e24997), to(#2d2ed4));
    background: linear-gradient(to right, #e24997, #2d2ed4);
}


</style>
	</body>
</html>
