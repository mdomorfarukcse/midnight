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
    <script src='https://js.stripe.com/v3/' type='text/javascript'></script>
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
                        <div class="card">
                            <h4 class="card-header">Total Amount : <?php echo $order->getTotal().' '. $order->getCurrency()  ?>  </h4>
                            <div class="card-body">
                                <div class="card-text">
                                    <form action="/stripe-charge" method="post" id="payment-form">
                                        <div>
                                            <label>Card holder</label>
                                            <input id="cardholder-name" class="form-control mb-4" type="text">
                                            <!-- placeholder for Elements -->
                                            <div id="card-element" class="form-control"></div>

                                            <!-- Used to display form errors -->
                                            <div id="card-errors" role="alert"></div>
                                        </div>

                                        <div class="d-flex flex-row mt-4 justify-content-end align-items-center">
                                            <button id="card-button" class="btn btn-primary">
                                                Pay
                                            </button>
                                        </div>
                                        <input type="hidden" name="paymentMethodId" id="paymentMethodId">
                                        <input type="hidden" name="orderId" value="<?php echo $order->getId()?>">
                                    </form>
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
       <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="\plugins\table\datatable\datatables.js"></script>
    <script src="\plugins\table\datatable\button-ext\dataTables.buttons.min.js"></script>
    <script src="\assets\js\apps\invoice-list.js"></script>
	    <script src="\plugins\bootstrap-touchspin\jquery.bootstrap-touchspin.min.js"></script>
    <script src="\plugins\bootstrap-touchspin\custom-bootstrap-touchspin.js"></script>

          <script>

              var style = {
                  base: {
                      color: '#32325d',
                      lineHeight: '1.8rem'
                  }
              };

              var stripe = Stripe('<?php echo $stripe_public_key ?>');

              var elements = stripe.elements();
              var cardElement = elements.create('card', {style: style});
              cardElement.mount('#card-element');

              var cardholderName = document.getElementById('cardholder-name');
              var cardButton = document.getElementById('card-button');
              var paymentMethodIdField = document.getElementById('paymentMethodId');
              var myForm = document.getElementById('payment-form');

              cardButton.addEventListener('click', function(ev) {
                  ev.preventDefault();
                  cardButton.disabled = true;

                  stripe.createPaymentMethod('card', cardElement, {
                      billing_details: {name: cardholderName.value }
                  }).then(function(result) {

                      if (result.error) {
                          cardButton.disabled = false;
                          alert(result.error.message);
                      } else {
                          paymentMethodIdField.value = result.paymentMethod.id;
                          myForm.submit();
                      }
                  });
              });
          </script>


	</body>
</html>
