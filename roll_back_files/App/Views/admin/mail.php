<?php

use Pemm\Core\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Setting;

global $container;

/* @var Request $request */
$request = $container->get('request');

/* @var Session $session */
$session = $container->get('session');

$setting = (new Setting())->find(1);

$language = $container->get('language');

if ($request->isMethod('post')) {

    try {



        $setting
            ->setId(1)
            ->setEmailSecureType($request->request->get('email_secure_type'))
            ->setEmailHost($request->request->get('email_host'))
            ->setEmailPort($request->request->get('email_port'))
            ->setEmailUsername($request->request->get('email_username'))
            ->setEmailPassword($request->request->get('email_password'));

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
    <title><?= $language::translate('Mail Settings') ?> - <?= SITE_NAME ?> </title>
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
<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                               <?= $language::translate('Mail Settings') ?></a>
                                         </li>
                                     </ul>

                                     <div class="tab-content" id="animateLineContent-4">
                                         <div class="tab-pane fade show active" id="animated-underline-profile" role="tabpanel" aria-labelledby="animated-underline-profile-tab">
                                           <div class="form-group row">
                                               <label for="email_secure_type" class="col-sm-2 col-form-label"> <?= $language::translate('Email Secure Type') ?></label>
                                               <div class="col-sm-10">
                                                   <input type="text" name="email_secure_type" class="form-control" id="email_secure_type" value="<?= $setting->getEmailSecureType() ?>">
                                               </div>
                                           </div>
                                           <div class="form-group row">
                                               <label for="email_host" class="col-sm-2 col-form-label"> <?= $language::translate('Email Host') ?></label>
                                               <div class="col-sm-10">
                                                   <input type="text" name="email_host" class="form-control" id="email_host" value="<?= $setting->getEmailHost() ?>">
                                               </div>
                                           </div>
                                           <div class="form-group row">
                                               <label for="email_port" class="col-sm-2 col-form-label"> <?= $language::translate('Email Port') ?></label>
                                               <div class="col-sm-10">
                                                   <input type="text" name="email_port" class="form-control" id="email_port" value="<?= $setting->getEmailPort() ?>">
                                               </div>
                                           </div>
                                           <div class="form-group row">
                                               <label for="email_username" class="col-sm-2 col-form-label"> <?= $language::translate('Email Username') ?></label>
                                               <div class="col-sm-10">
                                                   <input type="text" name="email_username" class="form-control" id="email_username" value="<?= $setting->getEmailUsername() ?>">
                                               </div>
                                           </div>
                                           <div class="form-group row">
                                               <label for="email_password" class="col-sm-2 col-form-label"> <?= $language::translate('Email Password') ?></label>
                                               <div class="col-sm-10">
                                                   <input type="password" name="email_password" class="form-control" id="email_password" value="<?= $setting->getEmailPassword() ?>">
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
                         <?php include("alt.php")?>
                      </div>
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
