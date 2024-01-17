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

        /* @var UploadedFile $siteLogo */
        if (!empty($siteLogo = $request->files->get('site_logo'))) {
            $_siteLogo = $siteLogo->move($_SERVER['DOCUMENT_ROOT'] . '/assets/img/', 'logo.' . $siteLogo->getClientOriginalExtension());
            $setting->setSiteLogo($_siteLogo->getFilename());

        }

        /* @var UploadedFile $logo2 */
        if (!empty($logo2 = $request->files->get('logo2'))) {
            $_logo2 = $logo2->move($_SERVER['DOCUMENT_ROOT'] . '/assets/img/', 'logo2.' . $logo2->getClientOriginalExtension());
            $setting->setLogo2($_logo2->getFilename());

        }


        /* @var UploadedFile $siteFavicon */
        if (!empty($siteFavicon = $request->files->get('site_favicon'))) {
            $_siteFavicon = $siteFavicon->move($_SERVER['DOCUMENT_ROOT'] . '/assets/img/', 'favicon.' . $siteFavicon->getClientOriginalExtension());
            $setting->setSiteFavicon($_siteFavicon->getFilename());
        }

        /* @var UploadedFile $siteEmailLogo */
        if (!empty($siteEmailLogo = $request->files->get('site_email_logo'))) {
            $_siteEmailLogo = $siteEmailLogo->move($_SERVER['DOCUMENT_ROOT'] . '/assets/img/', 'email-logo.' . $siteEmailLogo->getClientOriginalExtension());
            $setting->setSiteEmailLogo($_siteEmailLogo->getFilename());
        }

        $setting
            ->setId(1);

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
    <title><?= $language::translate('Logo') ?> - <?= SITE_NAME ?> </title>
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
                                             <a class="nav-link active " id="animated-underline-profile-tab" data-toggle="tab" href="#animated-underline-profile" role="tab" aria-controls="animated-underline-profile" aria-selected="false"><svg viewBox="0 0 24 24" width="24" height="24"   fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"  class="css-i6dzq1"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                               <?= $language::translate('Logo Settings') ?></a>
                                         </li>

                                     </ul>

                                     <div class="tab-content" id="animateLineContent-4">
                                         <div class="tab-pane fade show active" id="animated-underline-profile" role="tabpanel" aria-labelledby="animated-underline-profile-tab">

                                <div class="form-group row">
                                    <label for="logo2" class="col-sm-2 col-form-label"><?= $language::translate('Original') ?>  <?= $language::translate('Site Logo') ?></label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <span class="btn btn-default btn-file">
                                                    <?= $language::translate('Browse') ?>… <input type="file" class="imgInp" name="logo2">
                                                </span>
                                            </span>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                        <img id="logo2-img-upload" src="<?= $setting->getLogo2(true) ?>" style="max-width: 100px;max-height: 100px;padding: 13px;margin-top: 10px;"/>
                                    </div>
                                </div>

                                           <div class="form-group row">
                                               <label for="site_logo" class="col-sm-2 col-form-label"><?= $language::translate('White') ?> <?= $language::translate('Site Logo') ?> </label>
                                               <div class="col-sm-10">
                                                   <div class="input-group">
                                                       <span class="input-group-btn">
                                                           <span class="btn btn-default btn-file">
                                                               <?= $language::translate('Browse') ?>… <input type="file" class="imgInp" name="site_logo">
                                                           </span>
                                                       </span>
                                                       <input type="text" class="form-control" readonly>
                                                   </div>
                                                   <img id="site_logo-img-upload" src="<?= $setting->getSiteLogo(true) ?>" style="background: #4361ee;    padding: 13px;    margin-top: 22px;max-width: 100px;max-height: 100px"/>
                                               </div>
                                           </div>

                                           <div class="form-group row">
                                               <label for="site_logo" class="col-sm-2 col-form-label"> <?= $language::translate('Site Favicon') ?></label>
                                               <div class="col-sm-10">
                                                   <div class="input-group">
                                                       <span class="input-group-btn">
                                                           <span class="btn btn-default btn-file">
                                                               <?= $language::translate('Browse') ?>… <input type="file" class="imgInp" name="site_favicon">
                                                           </span>
                                                       </span>
                                                       <input type="text" class="form-control" readonly>
                                                   </div>
                                                   <img id="site_favicon-img-upload" src="<?= $setting->getSiteFavicon(true) ?>" style="max-width: 100px;max-height: 100px"/>
                                               </div>
                                           </div>
                                           <div class="form-group row">
                                               <label for="site_logo" class="col-sm-2 col-form-label"> <?= $language::translate('Site Email Logo') ?></label>
                                               <div class="col-sm-10">
                                                   <div class="input-group">
                                                       <span class="input-group-btn">
                                                           <span class="btn btn-default btn-file">
                                                               <?= $language::translate('Browse') ?>… <input type="file" class="imgInp" name="site_email_logo">
                                                           </span>
                                                       </span>
                                                       <input type="text" class="form-control" readonly>
                                                   </div>
                                                   <img id="site_email_logo-img-upload" src="<?= $setting->getSiteEmailLogo(true) ?>"  style="background: #4361ee;    padding: 13px;    margin-top: 22px;max-width: 100px;max-height: 100px"/>
                                               </div>
                                           </div>
                                           <button type="submit" class="btn btn-primary" style="width: 100%"><?= $language::translate('Save') ?></button>

                                         </div>

                                         </div>
                                     </div>
                                    </div>
                                        </form>
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
