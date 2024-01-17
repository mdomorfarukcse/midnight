<?php

use Pemm\Core\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Setting;
use Pemm\Model\SmsProvider;

global $container;

/* @var Request $request */
$request = $container->get('request');

/* @var Session $session */
$session = $container->get('session');

$setting = (new Setting())->find(1);

$language = $container->get('language');

$smsProvider = (new SmsProvider())->find(1);

if ($request->isMethod('post')) {

    try {

        $new = empty($smsProvider->getId());

        $smsProvider
            ->setName($request->request->get('name'))
            ->setHeader($request->request->get('header'))
            ->setToken($request->request->get('token'))
            ->setToken2($request->request->get('token2'))
            ->setNumber($request->request->get('number'))
            ->setStatus($request->request->getInt('status'));

        $smsProvider->store();

        $session->getFlashBag()->add('success', 'Success');

        if ($new) (new RedirectResponse('/admin/settings/sms?confirm_message=Success'))->send();

    } catch (\Exception $exception) {
        $session->getFlashBag()->add('danger', $exception->getMessage());
    }
}


if ($request->isMethod('post')) {

    try {

        $setting
            ->setId(1)

            ->setClientStatus(($request->request->has('client_status') && $request->request->get('client_status') == 'on') ? 1 : 0)
            ->setAdminStatus(($request->request->has('admin_status') && $request->request->get('admin_status') == 'on') ? 1 : 0)
            ->setSmsProvider($request->request->get('sms_provider_id'));

        $setting->store();

        $session->getFlashBag()->add('success', 'Success');

        //echo '<script>window.location.reload()</script>';

    } catch (\Exception $exception) {
        $session->getFlashBag()->add('danger', $exception->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $language::translate('Sms Settings') ?> - <?= SITE_NAME ?> </title>
    <?php include("css.php")?>
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
<?php include("header.php")?>
<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">
    <?php include("sidebar.php")?>
    <div id="content" class="main-content">
        <div class="layout-px-spacing">
            <div class="row layout-top-spacing">

<div class="col-xl-12 col-lg-12 col-sm-12">
  <?php
  foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
      <div class="alert alert-<?= $type ?>">
          <?php foreach ($messages as $message) { echo $message;} ?>
      </div>
  <?php }
  ?>
</div>
                              <div class="col-xl-12 col-lg-12 col-sm-12 ">
                                <form action="" method="post" style="width: 100%" enctype="multipart/form-data">


                                <div class="widget-content widget-content-area animated-underline-content">
                                     <ul class="nav nav-tabs  mb-3" id="animateLine" role="tablist">
                                         <li class="nav-item col-md-2">
                                             <a class="nav-link active " id="animated-underline-profile-tab" data-toggle="tab" href="#animated-underline-profile" role="tab" aria-controls="animated-underline-profile" aria-selected="false">

<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg>
                                               <?= $language::translate('Sms Settings') ?></a>
                                         </li>
                                     </ul>

                                     <div class="tab-content" id="animateLineContent-4">
                                         <div class="tab-pane fade show active" id="animated-underline-profile" role="tabpanel" aria-labelledby="animated-underline-profile-tab">
                                           <div class="row mb-4 mt-3">
                                          <div class="col-sm-3 col-12">
                                          <div class="nav flex-column nav-pills mb-sm-0 mb-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                          <a class="nav-link active mb-2" id="twilio-tab" data-toggle="pill" href="#twilio" role="tab" aria-controls="twilio" aria-selected="true">Twilio</a>
                                          <a class="nav-link mb-2" id="stripe-tab" data-toggle="pill" href="#stripe" role="tab" aria-controls="stripe" aria-selected="false"> <i class="ti-plus"></i> <?= $language::translate('Add') ?></a>
                                          </div>
                                          </div>

                                          <div class="col-sm-9 col-12">
                                          <div class="tab-content" id="v-pills-tabContent">

                                          <div class="tab-pane fade show active" id="twilio" role="tabpanel" aria-labelledby="twilio-tab">
                                          <div class="form-row mb-4">
                                            <div style="margin-bottom: 0;" class="form-group col-md-2 col-6 ">
                                              <label for="inputEmail4"><?= $language::translate('Status') ?></label>
                                            </div>
                                            <div style="margin-bottom: 0;" class="form-group col-md-10  col-6 ">
                                              <label class="switch s-icons s-outline  s-outline-primary">
                                                <input type="checkbox" name="status" value="1" <?= $smsProvider->getStatus()==1?'checked':'' ?>>
                                                <span class="slider"></span>
                                            </label></div>
                                          </div>

                                          <div class="form-group">
                                          <label for="paypal_username"><?= $language::translate('Token') ?></label>
                                          <input type="text" class="form-control" id="paypal_username"  name="token" value="<?= $smsProvider->getToken() ?>">
                                          </div>

                                          <div class="form-group">
                                          <label for="paypal_password"><?= $language::translate('Token') ?> 2</label>
                                          <input type="password" class="form-control" id="paypal_password"  name="token2" value="<?= $smsProvider->getToken2() ?>">
                                          </div>

                                          <div class="form-group">
                                          <label for="paypal_signature"><?= $language::translate('Header Text') ?></label>
                                          <input type="text" class="form-control" id="paypal_signature"  name="header" value="<?= $smsProvider->getHeader() ?>">
                                          </div>

                                              <div class="form-group">
                                                  <label for="paypal_signature"><?= $language::translate('Number') ?></label>
                                                  <input type="text" class="form-control" id="paypal_signature"  name="number" value="<?= $smsProvider->getNumber() ?>">
                                              </div>







                                          </div>


                                          </div>
                                          </div>
                                          </div>

                                           <button type="submit" class="btn btn-primary" style="width: 100%"><?= $language::translate('Save') ?></button>

                                         </div>
                                      </div>
                                    </div>
                                 </form>
                              </div>
                           </div>
                          </div>
                      </div>
        <?php include("alt.php")?>
    </div>
    <?php include("js.php")?>
    <script src="<?= SITE_URL ?>\plugins\bootstrap-select\bootstrap-select.min.js"></script>
    <script src="<?= SITE_URL ?>\plugins\bootstrap-toggle\bootstrap-toggle.min.js"></script>
    <!-- BEGIN PAGE LEVEL SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?= SITE_URL ?>\plugins\table\datatable\datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <style media="screen">
    .animated-underline-content .tab-content>.tab-pane {
padding: 10px 0 6px 30px;
}
.animated-underline-content .nav-tabs .nav-link.active, .animated-underline-content .nav-tabs .show>.nav-link {
    border-color: transparent;
    color: #4361ee;
}
    </style>
</body>
<script>
    $(document).ready( function() {

      $('#mustinote').summernote({
        height: 150,   //set editable area's height
        airMode: false
      });

      $('#mustinote2').summernote({
        height: 150,   //set editable area's height
        airMode: false
      });
        $('#mustinote3').summernote({
          height: 150,   //set editable area's height
            airMode: false
        });
        $('#mustinote4').summernote({
          height: 150,   //set editable area's height
            airMode: false
        });
        $('#mustinote5').summernote({
          height: 150,   //set editable area's height
            airMode: false
        });
        $('#mustinote6').summernote({
          height: 150,   //set editable area's height
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

            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }

        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#' + $(input).attr('name') + '-img-upload').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $(".imgInp").change(function(){
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

    #img-upload{
        width: 100%;
    }
</style>
</html>
