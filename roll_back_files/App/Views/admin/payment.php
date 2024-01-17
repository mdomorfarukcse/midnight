<?php

use Pemm\Core\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Setting;

global $container;

/* @var Request $request */
$request = $container->get("request");

/* @var Session $session */
$session = $container->get("session");

$setting = (new Setting())->find(1);

$language = $container->get("language");

if ($request->isMethod("post")) {
    try {
        $setting
            ->setId(1)
            ->setPaypal_username($request->request->get("paypal_username"))
            ->setPaypal_password($request->request->get("paypal_password"))
            ->setPaypal_signature($request->request->get("paypal_signature"))
            ->setPaypal_testmode(
                $request->request->has("paypal_testmode") &&
                $request->request->get("paypal_testmode") == "on"
                    ? 1
                    : 0
            )
            ->setPaypal_status(
                $request->request->has("paypal_status") &&
                $request->request->get("paypal_status") == "on"
                    ? 1
                    : 0
            )
            ->setMollie_apikey($request->request->get("mollie_apikey"))
            ->setMollie_testmode(
                $request->request->has("mollie_testmode") &&
                $request->request->get("mollie_testmode") == "on"
                    ? 1
                    : 0
            )
            ->setMollie_status(
                $request->request->has("mollie_status") &&
                $request->request->get("mollie_status") == "on"
                    ? 1
                    : 0
            )
            ->setStripe_apikey($request->request->get("stripe_apikey"))
            ->setStripe_publickey($request->request->get("stripe_publickey"))
            ->setStripe_testmode(
                $request->request->has("stripe_testmode") &&
                $request->request->get("stripe_testmode") == "on"
                    ? 1
                    : 0
            )
            ->setStripe_status(
                $request->request->has("stripe_status") &&
                $request->request->get("stripe_status") == "on"
                    ? 1
                    : 0
            )
            ->setIyzico_apikey($request->request->get("iyzico_apikey"))
            ->setIyzico_apisecret($request->request->get("iyzico_apisecret"))
            ->setIyzico_testmode(
                $request->request->has("iyzico_testmode") &&
                $request->request->get("iyzico_testmode") == "on"
                    ? 1
                    : 0
            )
            ->setIyzico_status(
                $request->request->has("iyzico_status") &&
                $request->request->get("iyzico_status") == "on"
                    ? 1
                    : 0
            )->setbtcpayserver_apikey($request->request->get("btcpayserver_apikey")
            )->setbtcpayserver_apisecret($request->request->get("btcpayserver_apisecret")
            )->setbtcpayserver_storeid($request->request->get("btcpayserver_storeid")
            )->setbtcpayserver_host($request->request->get("btcpayserver_host"))
            ->setBtcpayserver_status(
                $request->request->has("btcpayserver_status") &&
                $request->request->get("btcpayserver_status") == "on"
                    ? 1
                    : 0
            );
        $setting->store();

        $session->getFlashBag()->add("success", "Success");

        //echo '<script>window.location.reload()</script>';
    } catch (\Exception $exception) {
        $session->getFlashBag()->add("danger", $exception->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <title><?= $language::translate("Payment Method") ?> - <?= SITE_NAME ?> </title>
    <?php include "css.php"; ?>
    <link href="<?= SITE_URL ?>\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\bootstrap-select\bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\bootstrap-toggle\bootstrap-toggle.min.css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\table\datatable\datatables.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\assets\css\forms\theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\table\datatable\dt-global_style.css">
    <link href="<?= SITE_URL ?>\assets/css/components/tabs-accordian/custom-tabs.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="\assets/css/forms/switches.css">

    <link rel="stylesheet" href="\assets/css/themify-icons.css">
    <link rel="stylesheet" href="\assets/css/ie7/ie7.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
  </head>

  <body>
    <?php include "header.php"; ?>
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
      <?php include "sidebar.php"; ?>
      <div id="content" class="main-content">
        <div class="layout-px-spacing">
          <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-sm-12">
              <?php foreach (
           $container
               ->get("session")
               ->getFlashBag()
               ->all()
           as $type => $messages
       ) { ?>
              <div class="alert alert-<?= $type ?>">
                <?php foreach ($messages as $message) {
            echo $message;
        } ?>
              </div>
              <?php } ?>
            </div>
            <div class="col-xl-12 col-lg-12 col-sm-12 ">
              <form action="" method="post" style="width: 100%" enctype="multipart/form-data">


                <div class="widget-content widget-content-area animated-underline-content">
                  <ul class="nav nav-tabs  mb-3" id="animateLine" role="tablist">
                    <li class="nav-item col-md-2">
                      <a class="nav-link active " id="animated-underline-profile-tab" data-toggle="tab" href="#animated-underline-profile" role="tab" aria-controls="animated-underline-profile" aria-selected="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
                          <circle cx="9" cy="21" r="1"></circle>
                          <circle cx="20" cy="21" r="1"></circle>
                          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <?= $language::translate("Payment Method") ?></a>
                    </li>
                  </ul>

                  <div class="tab-content" id="animateLineContent-4">
                    <div class="tab-pane fade show active" id="animated-underline-profile" role="tabpanel" aria-labelledby="animated-underline-profile-tab">
                      <div class="row mb-4 mt-3">
                        <div class="col-sm-3 col-12">
                          <div class="nav flex-column nav-pills mb-sm-0 mb-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active mb-2" id="paypal-tab" data-toggle="pill" href="#paypal" role="tab" aria-controls="paypal" aria-selected="true">Paypal</a>
                            <a class="nav-link mb-2" id="stripe-tab" data-toggle="pill" href="#stripe" role="tab" aria-controls="stripe" aria-selected="false">Stripe</a>
                            <a class="nav-link mb-2" id="mollie-tab" data-toggle="pill" href="#mollie" role="tab" aria-controls="mollie" aria-selected="false">Mollie</a>
                            <a class="nav-link" id="iyzico-tab" data-toggle="pill" href="#iyzico" role="tab" aria-controls="iyzico" aria-selected="false">Iyzico</a>
                            <a class="nav-link" id="btcpayserver-tab" data-toggle="pill" href="#btcpayserver" role="tab" aria-controls="btcpayserver" aria-selected="false">BTCPayServer</a>
                          </div>
                        </div>

                        <div class="col-sm-9 col-12">
                          <div class="tab-content" id="v-pills-tabContent">

                            <div class="tab-pane fade show active" id="paypal" role="tabpanel" aria-labelledby="paypal-tab">
                              <div class="form-group mb-4">
                                <label for="paypal_username"><?= $language::translate("Username") ?></label>
                                <input type="text" class="form-control" id="paypal_username" name="paypal_username" value="<?= $setting->getPaypal_username() ?>">
                              </div>
                              <div class="form-group mb-4">
                                <label for="paypal_password"><?= $language::translate("Password") ?></label>
                                <input type="password" class="form-control" id="paypal_password" name="paypal_password" value="<?= $setting->getPaypal_password() ?>">
                              </div>

                              <div class="form-group mb-4">
                                <label for="paypal_signature" <?= $language::translate("Signature") ?>></label>
                                <input type="password" class="form-control" id="paypal_signature" name="paypal_signature" value="<?= $setting->getPaypal_signature() ?>">
                              </div>

                              <div class="form-row mb-4">
                                <div style="    margin-bottom: 0;" class="form-group col-md-2 col-6 ">
                                  <label for="inputEmail4"><?= $language::translate("Test Mode") ?></label>
                                </div>
                                <div style="    margin-bottom: 0;" class="form-group col-md-10  col-6 ">
                                  <label class="switch s-icons s-outline  s-outline-primary">
                                    <input type="checkbox" name="paypal_testmode" <?= $setting->getPaypal_testmode()
                      ? "checked"
                      : "" ?>>
                                    <span class="slider"></span>

                                </div>
                              </div>
                              <div class="form-row mb-4">
                                <div class="form-group col-md-2 col-6 ">
                                  <label for="inputEmail4"><?= $language::translate("Status") ?></label>
                                </div>
                                <div class="form-group col-md-10  col-6 ">
                                  <label class="switch s-icons s-outline  s-outline-primary">
                                    <input type="checkbox" name="paypal_status" <?= $setting->getPaypal_status()
                      ? "checked"
                      : "" ?>>
                                    <span class="slider"></span>
                                </div>
                              </div>

                            </div>
                            <div class="tab-pane fade" id="stripe" role="tabpanel" aria-labelledby="stripe-tab">
                              <div class="form-group mb-4">
                                <label for="stripe_publickey"><?= $language::translate("Public Key") ?></label>
                                <input type="password" class="form-control" id="paypal_password" name="stripe_publickey" value="<?= $setting->getStripe_publickey() ?>">

                              </div>
                              <div class="form-group mb-4">
                                <label for="stripe_apikey"><?= $language::translate("Api Key") ?></label>
                                <input type="password" class="form-control" id="stripe_apikey" name="stripe_apikey" value="<?= $setting->getStripe_apikey() ?>">

                              </div>

                              <div class="form-row mb-4">
                                <div style="    margin-bottom: 0;" class="form-group col-md-2 col-6 ">
                                  <label for="inputEmail4"><?= $language::translate("Test Mode") ?></label>
                                </div>
                                <div style="    margin-bottom: 0;" class="form-group col-md-10  col-6 ">
                                  <label class="switch s-icons s-outline  s-outline-primary">
                                    <input type="checkbox" name="stripe_testmode" <?= $setting->getStripe_testmode()
                      ? "checked"
                      : "" ?>>
                                    <span class="slider"></span>

                                </div>
                              </div>
                              <div class="form-row mb-4">
                                <div class="form-group col-md-2 col-6 ">
                                  <label for="inputEmail4"><?= $language::translate("Status") ?></label>
                                </div>
                                <div class="form-group col-md-10  col-6 ">
                                  <label class="switch s-icons s-outline  s-outline-primary">
                                    <input type="checkbox" name="stripe_status" <?= $setting->getStripe_status()
                      ? "checked"
                      : "" ?>>
                                    <span class="slider"></span>
                                </div>
                              </div>
                            </div>
                            <div class="tab-pane fade" id="mollie" role="tabpanel" aria-labelledby="mollie-tab">
                              <div class="form-group mb-4">
                                <label for="mollie_apikey"><?= $language::translate("Api Key") ?></label>
                                <input type="password" class="form-control" id="mollie_apikey" name="mollie_apikey" value="<?= $setting->getMollie_apikey() ?>">
                              </div>

                              <div class="form-row mb-4">
                                <div style="    margin-bottom: 0;" class="form-group col-md-2 col-6 ">
                                  <label for="inputEmail4"><?= $language::translate("Test Mode") ?></label>
                                </div>
                                <div style="    margin-bottom: 0;" class="form-group col-md-10  col-6 ">
                                  <label class="switch s-icons s-outline  s-outline-primary">
                                    <input type="checkbox" name="mollie_testmode" <?= $setting->getMollie_testmode()
                      ? "checked"
                      : "" ?>>
                                    <span class="slider"></span>

                                </div>
                              </div>
                              <div class="form-row mb-4">
                                <div class="form-group col-md-2 col-6 ">
                                  <label for="inputEmail4"><?= $language::translate("Status") ?></label>
                                </div>
                                <div class="form-group col-md-10  col-6 ">
                                  <label class="switch s-icons s-outline  s-outline-primary">
                                    <input type="checkbox" name="mollie_status" <?= $setting->getMollie_status()
                      ? "checked"
                      : "" ?>>
                                    <span class="slider"></span>
                                </div>
                              </div>
                            </div>
                            <div class="tab-pane fade" id="iyzico" role="tabpanel" aria-labelledby="iyzico-tab">
                              <div class="form-group mb-4">
                                <label for="iyzico_apikey"><?= $language::translate("Api Key") ?></label>
                                <input type="password" class="form-control" id="iyzico_apikey" name="iyzico_apikey" value="<?= $setting->getIyzico_apikey() ?>">

                              </div>
                              <div class="form-group mb-4">
                                <label for="iyzico_apisecret"><?= $language::translate(
                    "Api Secret Key"
                ) ?></label>
                                <input type="password" class="form-control" id="iyzico_apisecret" name="iyzico_apisecret" value="<?= $setting->getIyzico_apisecret() ?>">

                              </div>
                              <div class="form-row mb-4">
                                <div style="    margin-bottom: 0;" class="form-group col-md-2 col-6 ">
                                  <label for="inputEmail4"><?= $language::translate("Test Mode") ?></label>
                                </div>
                                <div style="    margin-bottom: 0;" class="form-group col-md-10  col-6 ">
                                  <label class="switch s-icons s-outline  s-outline-primary">
                                    <input type="checkbox" name="iyzico_testmode" <?= $setting->getIyzico_testmode()
                      ? "checked"
                      : "" ?>>
                                    <span class="slider"></span>

                                </div>
                              </div>
                              <div class="form-row mb-4">
                                <div class="form-group col-md-2 col-6 ">
                                  <label for="inputEmail4"><?= $language::translate("Status") ?></label>
                                </div>
                                <div class="form-group col-md-10  col-6 ">
                                  <label class="switch s-icons s-outline  s-outline-primary">
                                    <input type="checkbox" name="iyzico_status" <?= $setting->getIyzico_status()
                      ? "checked"
                      : "" ?>>
                                    <span class="slider"></span>
                                </div>
                              </div>
                            </div>

                            <div class="tab-pane fade" id="btcpayserver" role="tabpanel" aria-labelledby="btcpayserver-tab">
                              <div class="form-group mb-4">
                                <label for="btcpayserver_storeid"><?= $language::translate("Store id") ?></label>
                                <input type="text" class="form-control" id="btcpayserver_storeid" name="btcpayserver_storeid" value="<?= $setting->getbtcpayserver_storeid() ?>">
                              </div>
                              <div class="form-group mb-4">
                                <label for="btcpayserver_apikey"><?= $language::translate("Api Key") ?></label>
                                <input type="password" class="form-control" id="btcpayserver_apikey" name="btcpayserver_apikey" value="<?= $setting->getbtcpayserver_apikey() ?>">
                              </div>
                              <div class="form-group mb-4">
                                <label for="btcpayserver_apisecret"><?= $language::translate("Webhook secret") ?></label>
                                <input type="text" class="form-control" id="btcpayserver_apisecret" name="btcpayserver_apisecret" value="<?= $setting->getbtcpayserver_apisecret() ?>">
                              </div>
                              <div class="form-group mb-4">
                                <label for="btcpayserver_host"><?= $language::translate("Payment host") ?></label>
                                <input type="text" class="form-control" id="btcpayserver_host" name="btcpayserver_host" value="<?= $setting->getbtcpayserver_host() ?>">
                              </div>

                              <div class="form-row mb-4">
                                <div class="form-group col-md-2 col-6 ">
                                  <label for="inputEmail4"><?= $language::translate("Status") ?></label>
                                </div>
                                <div class="form-group col-md-10  col-6 ">
                                  <label class="switch s-icons s-outline  s-outline-primary">
                                    <input type="checkbox" name="btcpayserver_status" <?= $setting->getBtcpayserver_status()
                      ? "checked"
                      : "" ?>>
                                    <span class="slider"></span>
                                </div>
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>


                      <button type="submit" class="btn btn-primary" style="width: 100%"><?= $language::translate(
               "Save"
           ) ?></button>

                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php include "alt.php"; ?>
    </div>
    <?php include "js.php"; ?>
    <script src="<?= SITE_URL ?>\plugins\bootstrap-select\bootstrap-select.min.js"></script>
    <script src="<?= SITE_URL ?>\plugins\bootstrap-toggle\bootstrap-toggle.min.js"></script>
    <!-- BEGIN PAGE LEVEL SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?= SITE_URL ?>\plugins\table\datatable\datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <style media="screen">
      .animated-underline-content .tab-content > .tab-pane {
        padding: 10px 0 6px 30px;
      }

      .animated-underline-content .nav-tabs .nav-link.active,
      .animated-underline-content .nav-tabs .show > .nav-link {
        border-color: transparent;
        color: #4361ee;
      }
    </style>
  </body>
  <script>
    $(document).ready(function() {

      $('#mustinote').summernote({
        height: 150, //set editable area's height
        airMode: false
      });

      $('#mustinote2').summernote({
        height: 150, //set editable area's height
        airMode: false
      });
      $('#mustinote3').summernote({
        height: 150, //set editable area's height
        airMode: false
      });
      $('#mustinote4').summernote({
        height: 150, //set editable area's height
        airMode: false
      });
      $('#mustinote5').summernote({
        height: 150, //set editable area's height
        airMode: false
      });
      $('#mustinote6').summernote({
        height: 150, //set editable area's height
        airMode: false
      });

      $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
          label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [label]);
      });

      $('.btn-file :file').on('fileselect', function(event, label) {

        var input = $(this).parents('.input-group').find(':text'),
          log = label;

        if (input.length) {
          input.val(log);
        } else {
          if (log) alert(log);
        }

      });

      function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function(e) {
            $('#' + $(input).attr('name') + '-img-upload').attr('src', e.target.result);
          }

          reader.readAsDataURL(input.files[0]);
        }
      }

      $(".imgInp").change(function() {
        readURL(this);
      });
    });
  </script>
  <style>
    .btn-file {
      position: relative;
      overflow: hidden;
    }

    .btn-file input[type=file] {
      position: absolute;
      top: 0;
      right: 0;
      min-width: 100%;
      min-height: 100%;
      font-size: 100px;
      text-align: right;
      filter: alpha(opacity=0);
      opacity: 0;
      outline: none;
      background: white;
      cursor: inherit;
      display: block;
    }

    #img-upload {
      width: 100%;
    }
  </style>

</html>
