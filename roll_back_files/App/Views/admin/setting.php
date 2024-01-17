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


        /* @var UploadedFile $logo2 */
        if (!empty($register = $request->files->get('register'))) {
            $_register = $register->move($_SERVER['DOCUMENT_ROOT'] . '/assets/img/', 'register.' . $register->getClientOriginalExtension());
            $setting->setRegister($_register->getFilename());

        }

        /* @var UploadedFile $logo2 */
        if (!empty($login = $request->files->get('login'))) {
            $_login = $login->move($_SERVER['DOCUMENT_ROOT'] . '/assets/img/', 'login.' . $login->getClientOriginalExtension());
            $setting->setLogin($_login->getFilename());

        }


        $setting
            ->setId(1)
            ->setSiteUrl($request->request->get('site_url'))
            ->setSiteName($request->request->get('site_name'))
            ->setPhone($request->request->get('phone'))
            ->setEmail($request->request->get('email'))
            ->setAddress($request->request->get('address'))
            ->setDefaultPaymentMethod($request->request->get('default_payment_method'))
             ->setIsMaintenance(@$request->request->getInt('is_maintenance'))
             ->setDefaultLanguage($request->request->get('default_language'))
             ->setInvoiceprefix($request->request->get('invoice_prefix'))
            ->setDefault_currency_method($request->request->get('default_currency_method'))
            ->setLicense_key($request->request->get('license_key'))
             ->setMailAfterRegister(($request->request->has('mail_after_register') && $request->request->get('mail_after_register') == 'on') ? 1 : 0)
             ->setDefaultTimeZone($request->request->get('default_time_zone'));

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
    <title><?= $language::translate('General Settings') ?> - <?= SITE_NAME ?> </title>
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
                                             <a class="nav-link active" id="animated-underline-home-tab" data-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                               <?= $language::translate('General Settings') ?></a>
                                         </li>

                                     </ul>

                                     <div class="tab-content" id="animateLineContent-4">
                                         <div class="tab-pane fade show active" id="animated-underline-home" role="tabpanel" aria-labelledby="animated-underline-home-tab">
                                           <div class="form-group row">
                                               <label for="site_name" class="col-sm-2 col-form-label"> <?= $language::translate('Site Name') ?></label>
                                               <div class="col-sm-10">
                                                   <input type="text" name="site_name" class="form-control" id="site_name" value="<?= $setting->getSiteName() ?>" required>
                                               </div>
                                           </div>
                                          <div class="form-group row">
                                              <label for="site_url" class="col-sm-2 col-form-label"> <?= $language::translate('Site Url') ?></label>
                                              <div class="col-sm-10">
                                                  <input type="text" name="site_url" class="form-control" id="site_url" value="<?= $setting->getSiteUrl() ?>" required>
                                              </div>
                                          </div>
                                          <div class="form-group row">
                                              <label for="license_key" class="col-sm-2 col-form-label"> <?= $language::translate('License Key') ?></label>
                                              <div class="col-sm-10">
                                                  <input type="text" name="license_key" class="form-control" id="license_key" value="PRO-MOXND-AGLZN-GPLHW" disabled>
                                              </div>
                                          </div>
                                          <div class="form-group row">
                                              <label for="invoice_prefix" class="col-sm-2 col-form-label"> <?= $language::translate('Invoice Prefix') ?></label>
                                              <div class="col-sm-10">
                                                  <input type="text" name="invoice_prefix" class="form-control" id="invoice_prefix" value="<?= $setting->getInvoiceprefix() ?>" required>
                                              </div>
                                          </div>

                                          <hr class="col-12">
                                          <div class="row">


                                          <div class="form-group  col-6">
                                              <label style="    text-align: center;     margin-bottom: 18px;" for="login" class="col-sm-12 col-form-label"><?= $language::translate('Login') ?> </label>
                                              <div class="col-sm-12">
                                                  <div class="input-group">
                                                      <span class="input-group-btn">
                                                          <span class="btn btn-default btn-file">
                                                              <?= $language::translate('Browse') ?>… <input type="file" class="imgInp" name="login">
                                                          </span>
                                                      </span>
                                                      <input type="text" class="form-control" readonly>
                                                  </div>

                                                <div style="text-align: center;display: block;" class="input-group">
                                                  <img id="login-img-upload" src="<?= $setting->getLogin(true) ?>" style="text-align: center; max-width: 200px;max-height: 120px;padding: 13px;margin-top: 10px;"/>
                                                </div>

                                              </div>
                                          </div>
                                          <div class="form-group  col-6">
                                              <label style="    text-align: center;     margin-bottom: 18px;"  for="register" class="col-sm-12 col-form-label"><?= $language::translate('Register') ?> </label>
                                              <div class="col-sm-12">
                                                  <div class="input-group">
                                                      <span class="input-group-btn">
                                                          <span class="btn btn-default btn-file">
                                                              <?= $language::translate('Browse') ?>… <input type="file" class="imgInp" name="register">
                                                          </span>
                                                      </span>
                                                      <input type="text" class="form-control" readonly>
                                                  </div>
                                                  <div style="text-align: center;display: block;" class="input-group">
                                                    <img id="register-img-upload" src="<?= $setting->getRegister(true) ?>" style="text-align: center; max-width: 200px;max-height: 120px;padding: 13px;margin-top: 10px;"/>
                                                  </div>
                                               </div>
                                          </div>
                                            </div>
                                          <hr class="col-12">
                                           <div class="form-group row">
                                               <label for="default_payment_method" class="col-sm-2 col-form-label"> <?= $language::translate('Default Payment Method') ?></label>
                                               <div class="col-sm-10">
                                                   <select name="default_payment_method" class="form-control" id="default_payment_method" required>
                                                        <?php
                                                       foreach ($setting::paymentMethods() as $code => $name) {?>
                                                           <option value="<?= $code ?>" <?= $code == $setting->getDefaultPaymentMethod() ? 'selected' : '' ?>>
                                                               <?= $name?></option>
                                                       <?php } ?>
                                                   </select>
                                               </div>
                                           </div>

                                           <div class="form-group row">
                                               <label for="default_payment_method" class="col-sm-2 col-form-label"> <?= $language::translate('Default Currency Method') ?></label>
                                               <div class="col-sm-10">
                                                   <?php $currencies = $container->get('currency')->getAll(); ?>
                                                   <select name="default_currency_method" class="form-control" id="default_currency_method" required>
                                                       <?php
                                                       foreach ($currencies as $currencyCode => $_currency) {?>
                                                           <option value="<?= $currencyCode ?>" <?= $currencyCode == $setting->getDefault_currency_method() ? 'selected' : '' ?>>
                                                               <?= $_currency->getSymbol() ?> <?= $language::translate($_currency->getName()) ?></option>
                                                       <?php } ?>
                                                   </select>
                                               </div>
                                           </div>
                                             <div class="form-group row">
                                                 <label for="default_payment_method" class="col-sm-2 col-form-label"> <?= $language::translate('Default Time Zone') ?></label>
                                                 <div class="col-sm-10">
                                                     <select name="default_time_zone" class="form-control" id="default_time_zone" required>
                                                       <?php
                                                       $timezones = array(
                                                           'Pacific/Midway'       => "(GMT-11:00) Midway Island",
                                                           'US/Samoa'             => "(GMT-11:00) Samoa",
                                                           'US/Hawaii'            => "(GMT-10:00) Hawaii",
                                                           'US/Alaska'            => "(GMT-09:00) Alaska",
                                                           'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
                                                           'America/Tijuana'      => "(GMT-08:00) Tijuana",
                                                           'US/Arizona'           => "(GMT-07:00) Arizona",
                                                           'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
                                                           'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
                                                           'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
                                                           'America/Mexico_City'  => "(GMT-06:00) Mexico City",
                                                           'America/Monterrey'    => "(GMT-06:00) Monterrey",
                                                           'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
                                                           'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
                                                           'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
                                                           'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
                                                           'America/Bogota'       => "(GMT-05:00) Bogota",
                                                           'America/Lima'         => "(GMT-05:00) Lima",
                                                           'America/Caracas'      => "(GMT-04:30) Caracas",
                                                           'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
                                                           'America/La_Paz'       => "(GMT-04:00) La Paz",
                                                           'America/Santiago'     => "(GMT-04:00) Santiago",
                                                           'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
                                                           'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
                                                           'Greenland'            => "(GMT-03:00) Greenland",
                                                           'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
                                                           'Atlantic/Azores'      => "(GMT-01:00) Azores",
                                                           'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
                                                           'Africa/Casablanca'    => "(GMT) Casablanca",
                                                           'Europe/Dublin'        => "(GMT) Dublin",
                                                           'Europe/Lisbon'        => "(GMT) Lisbon",
                                                           'Europe/London'        => "(GMT) London",
                                                           'Africa/Monrovia'      => "(GMT) Monrovia",
                                                           'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
                                                           'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
                                                           'Europe/Berlin'        => "(GMT+01:00) Berlin",
                                                           'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
                                                           'Europe/Brussels'      => "(GMT+01:00) Brussels",
                                                           'Europe/Budapest'      => "(GMT+01:00) Budapest",
                                                           'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
                                                           'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
                                                           'Europe/Madrid'        => "(GMT+01:00) Madrid",
                                                           'Europe/Paris'         => "(GMT+01:00) Paris",
                                                           'Europe/Prague'        => "(GMT+01:00) Prague",
                                                           'Europe/Rome'          => "(GMT+01:00) Rome",
                                                           'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
                                                           'Europe/Skopje'        => "(GMT+01:00) Skopje",
                                                           'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
                                                           'Europe/Vienna'        => "(GMT+01:00) Vienna",
                                                           'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
                                                           'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
                                                           'Europe/Athens'        => "(GMT+02:00) Athens",
                                                           'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
                                                           'Africa/Cairo'         => "(GMT+02:00) Cairo",
                                                           'Africa/Harare'        => "(GMT+02:00) Harare",
                                                           'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
                                                           'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
                                                           'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
                                                           'Europe/Kiev'          => "(GMT+02:00) Kyiv",
                                                           'Europe/Minsk'         => "(GMT+02:00) Minsk",
                                                           'Europe/Riga'          => "(GMT+02:00) Riga",
                                                           'Europe/Sofia'         => "(GMT+02:00) Sofia",
                                                           'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
                                                           'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
                                                           'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
                                                           'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
                                                           'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
                                                           'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
                                                           'Europe/Moscow'        => "(GMT+03:00) Moscow",
                                                           'Asia/Tehran'          => "(GMT+03:30) Tehran",
                                                           'Asia/Baku'            => "(GMT+04:00) Baku",
                                                           'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
                                                           'Asia/Muscat'          => "(GMT+04:00) Muscat",
                                                           'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
                                                           'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
                                                           'Asia/Kabul'           => "(GMT+04:30) Kabul",
                                                           'Asia/Karachi'         => "(GMT+05:00) Karachi",
                                                           'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
                                                           'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
                                                           'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
                                                           'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
                                                           'Asia/Almaty'          => "(GMT+06:00) Almaty",
                                                           'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
                                                           'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
                                                           'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
                                                           'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
                                                           'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
                                                           'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
                                                           'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
                                                           'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
                                                           'Australia/Perth'      => "(GMT+08:00) Perth",
                                                           'Asia/Singapore'       => "(GMT+08:00) Singapore",
                                                           'Asia/Taipei'          => "(GMT+08:00) Taipei",
                                                           'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
                                                           'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
                                                           'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
                                                           'Asia/Seoul'           => "(GMT+09:00) Seoul",
                                                           'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
                                                           'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
                                                           'Australia/Darwin'     => "(GMT+09:30) Darwin",
                                                           'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
                                                           'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
                                                           'Australia/Canberra'   => "(GMT+10:00) Canberra",
                                                           'Pacific/Guam'         => "(GMT+10:00) Guam",
                                                           'Australia/Hobart'     => "(GMT+10:00) Hobart",
                                                           'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
                                                           'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
                                                           'Australia/Sydney'     => "(GMT+10:00) Sydney",
                                                           'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
                                                           'Asia/Magadan'         => "(GMT+12:00) Magadan",
                                                           'Pacific/Auckland'     => "(GMT+12:00) Auckland",
                                                           'Pacific/Fiji'         => "(GMT+12:00) Fiji",
                                                       );
                                                       foreach($timezones as $key => $timezone){ ?>
                                                         <option value="<?= $key ?>" <?= $key == $setting->getDefaultTimeZone() ? 'selected' : '' ?>>
                                                             <?= $key.' '. $timezone?></option>
                                                       <?php } ?>
                                                     </select>
                                                 </div>
                                             </div>
                                           <div class="form-group row">
                                               <label for="default_language" class="col-sm-2 col-form-label"> <?= $language::translate('Default Language') ?></label>
                                               <div class="col-sm-10">
                                                   <select name="default_language" class="form-control" id="default_language" required>
                                                        <?php
                                                       foreach ($setting::languages() as $code => $name) {?>
                                                           <option value="<?= $code ?>" <?= $code == $setting->getDefaultLanguage() ? 'selected' : '' ?>>
                                                               <?= $name?></option>
                                                       <?php } ?>
                                                   </select>
                                               </div>
                                           </div>
                                           <hr class="col-12">
                                           <div class="form-group row">
                                               <label for="phone" class="col-sm-2 col-form-label"> <?= $language::translate('Phone') ?></label>
                                               <div class="col-sm-10">
                                                   <input type="text" name="phone" class="form-control" id="phone" value="<?= $setting->getPhone() ?>" required>
                                               </div>
                                           </div>
                                           <div class="form-group row">
                                               <label for="email" class="col-sm-2 col-form-label"> <?= $language::translate('E-Mail') ?></label>
                                               <div class="col-sm-10">
                                                   <input type="email" name="email" class="form-control" id="email" value="<?= $setting->getEmail() ?>" required>
                                               </div>
                                           </div>
                                           <div class="form-group row">
                                               <label for="address" class="col-sm-2 col-form-label"> <?= $language::translate('Address') ?></label>
                                               <div class="col-sm-10">
                                                   <textarea name="address" class="form-control"><?= $setting->getAddress() ?></textarea>
                                               </div>
                                           </div>
                                             <div  style="    margin-bottom: 4px;" class="form-row">
                                                 <div style="    margin-bottom: 0;" class="form-group col-md-2 col-6 ">
                                                     <label for="inputEmail4"><?= $language::translate('Customers to Confirm Mail') ?></label>
                                                 </div>
                                                 <div  style="    margin-bottom: 0;" class="form-group col-md-10  col-6 ">
                                                     <label class="switch s-icons s-outline  s-outline-primary">
                                                         <input type="checkbox" name="mail_after_register" <?= ($setting->getMailAfterRegister()) ? 'checked' : '' ?>>
                                                         <span class="slider"></span>

                                                 </div>
                                             </div>
<button type="submit" class="btn btn-primary" style="width: 100%;margin-top: 13px;    margin-bottom: 23px;"><?= $language::translate('Save') ?></button>
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
