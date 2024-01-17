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
            ->setDescription($request->request->get('description'))
            ->setDescription2($request->request->get('description2'))
            ->setDescription3($request->request->get('description3'))
            ->setPrivacy($request->request->get('privacy'))
            ->setImprint($request->request->get('imprint'))
            ->setDuyuruStatus(($request->request->has('duyuru_status') && $request->request->get('duyuru_status') == 'on') ? 1 : 0)
            ->setAnnouncement($request->request->get('announcement'));

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
    <title><?= $language::translate('Policies') ?> - <?= SITE_NAME ?> </title>
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
                                              <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                               <?= $language::translate('Policies') ?></a>
                                         </li>
                                     </ul>

                                     <div class="tab-content" id="animateLineContent-4">
                                         <div class="tab-pane fade show active" id="animated-underline-profile" role="tabpanel" aria-labelledby="animated-underline-profile-tab">

                                           <div style="    margin-bottom: 2rem;" class="form-group row">
                                              <label for="announcement" class="col-sm-2 col-form-label"> <?= $language::translate('Duyuru') ?></label>
                                              <div class="col-sm-10">
                                                  <textarea name="announcement" id="mustinote" class="form-control"><?= $setting->getAnnouncement() ?></textarea>
                                              </div>
                                          </div>

                                          <div class="form-row mb-4">
                                            <div class=" col-md-2 col-6 ">
                                                <label for="inputEmail4">  <?= $language::translate('Status') ?></label>
                                             </div>
                                            <div class=" col-md-10  col-6 ">
                                              <label class="switch s-icons s-outline  s-outline-primary">
                                          <input type="checkbox" name="duyuru_status" <?= ($setting->getDuyuruStatus()) ? 'checked' : '' ?>>
                                          <span class="slider"></span>
                                            </div>
                                        </div>
                                          <hr class="col-12">


                                            <div class="form-group row">
                                                <label for="description" class="col-sm-2 col-form-label"> <?= $language::translate('Imprint') ?></label>
                                                <div class="col-sm-10">
                                                    <textarea name="imprint" id="mustinote6" class="form-control"><?= $setting->getImprint() ?></textarea>
                                                </div>
                                            </div>

                                              <hr class="col-12">
                                          <div class="form-group row">
                                              <label for="description" class="col-sm-2 col-form-label"> <?= $language::translate('Privacy Policy') ?></label>
                                              <div class="col-sm-10">
                                                  <textarea name="privacy" id="mustinote5" class="form-control"><?= $setting->getPrivacy() ?></textarea>
                                              </div>
                                          </div>
                                              <hr class="col-12">
                                          <div class="form-group row">
                                              <label for="description" class="col-sm-2 col-form-label"> <?= $language::translate('Terms and conditions') ?></label>
                                              <div class="col-sm-10">
                                                  <textarea name="description" id="mustinote2" class="form-control"><?= $setting->getDescription() ?></textarea>
                                              </div>
                                          </div>
                                          <hr class="col-12">
                                          <div class="form-group row">
                                              <label for="description" class="col-sm-2 col-form-label"> <?= $language::translate('Return Policy') ?></label>
                                              <div class="col-sm-10">
                                                  <textarea name="description2" id="mustinote3" class="form-control"><?= $setting->getDescription2() ?></textarea>
                                              </div>
                                          </div>
                                          <hr class="col-12">
                                          <div class="form-group row">
                                              <label for="description" class="col-sm-2 col-form-label"> <?= $language::translate('Delivery Information') ?></label>
                                              <div class="col-sm-10">
                                                  <textarea name="description3" id="mustinote4" class="form-control"><?= $setting->getDescription3() ?></textarea>
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
