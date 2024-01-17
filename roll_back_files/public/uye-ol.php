<?php

use Pemm\Core\Container;
use Pemm\Core\Language;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Setting;

global $container;

/* @var Language $language*/
$language = $container->get('language');
$setting = (new Setting())->find(1);

/* @var Session $session*/
$session = $container->get('session');

$language = $container->get('language');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title><?= $language::translate('Register') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
	<link href="\assets\css\authentication\form-1.css" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
    <link rel="stylesheet" href="/assets/js/telinput/css/intlTelInput.css">
    <style>
    body {
            margin: 0;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }
      .banner-3 {
      width: 100vw;
      height: 100vh;
      max-width: 100%;
      position: relative;
      overflow: hidden;
    }
    .banner-3 .banner-content-3 {
      width: 600px;
      padding: 30px 50px;
      border-radius: 5px;
      background-color: #263a54c7;
      position: relative;
      top: 50%;
      left: 50%;
      -webkit-transform: translate(-50%, -50%);
      transform: translate(-50%, -50%);
            z-index: 1;
    }
    .banner-3 .banner-content-3 h1 {
      margin: 0;
      color: #FFFFFF;
      font-weight: 700;
      line-height: 1.1;
       font-size: 25px;
      text-transform: uppercase;
      text-align: center;
      padding-bottom: 25px;
    }
    .btn-login {
      color: #fff !important;
      background-color: #ce5372 !important;
      text-transform: uppercase;
    }
    .form-group label, label {
      text-transform: uppercase;
      color: #fff;
      font-size: 14px;
    }
    .banner-content-3 a {
      color: #ce5372;
    }
    .banner-3 .banner-content-3 p {
      color: #FF9800;
      font-size: 1.5em;
      line-height: 1.1;
    }
    .banner-3 video {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      -o-object-fit: cover;
       object-fit: cover;
      z-index: -1;
    }
		.iti.iti--allow-dropdown.iti--separate-dial-code {
    width: 100%;
}
		.iti__country, .iti__selected-flag {
    color: #000 !important;
}
    </style>
</head>
<body>
    <section class="banner-3">
  <div class="banner-content-3">
    <h1 class=""><?= $language::translate('Sing in') ?></h1>
	  <form action="" method="post" class="text-left">

		<?php
		foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
		  <div class="alert alert-<?= $type ?>">
			  <?php foreach ($messages as $message) { echo $message;} ?>
		  </div>
		  <?php }
		  ?>
		  <input type="hidden" name="csrf_token" value="<?= $container->get('csrf_token') ?>">
    <!-- to error: add class "has-danger" -->
	   <div style="text-align: center; margin-bottom: 23px;" class="">
                              <div class="custom-control custom-radio custom-control-inline">
       <input type="radio" onchange="checkStatusofRadio();" id="customRadioInline1" name="customRadioInline1" checked="true" value="1" class="custom-control-input">
       <label class="custom-control-label" for="customRadioInline1"><?= $language::translate('Personal') ?></label>
       </div>
   <div class="custom-control custom-radio custom-control-inline">
       <input type="radio" onchange="checkStatusofRadio();" id="customRadioInline2" name="customRadioInline1" value="2" class="custom-control-input">
       <label class="custom-control-label" for="customRadioInline2"><?= $language::translate('Business') ?></label>
   </div>
  </div>
	   <div class="row mb-1">
		  <div class="col-md-6">
			  <label for="">First name</label>
			   <input name="firstName" type="text" value="" class="form-control form-control-sm" required>
		   </div>
		   <div class="col-md-6">
			  <label for="">Last name</label>
			   <input id="text" name="lastName" type="text" value="" class="form-control form-control-sm" required>
		   </div>
		 </div>
		 <div style="display:none;" id="forCompany">
			 <div class="row mb-1">
				 <div class="col-md-6">
					  <label for="">Company Name</label>
					   <input  name="companyName" type="text" value="" class="form-control form-control-sm" >
				   </div>
				   <div class="col-md-6">
					  <label for="">VAT Number</label>
					   <input name="vatNumber" type="text" value=""  class="form-control form-control-sm" required>
				   </div>
			   </div>
		   </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Email</label>
        <input type="email" class="form-control form-control-sm"  id="email" name="email" required/>
    </div>
	<div class="form-group">
        <label for="">Contact Number</label>
        <input type="tel" value=""  class="form-control form-control-sm"  name="contactNumber"  id="phone" required/>
        <input type="hidden" id="full_phone" name="full_phone" value="">
    </div>
	<div class="row mb-1">
		<div class="col-md-6">
			<label for="">Evc Number</label>
			<input id="text" name="evcnumber" type="text" value=""  class="form-control form-control-sm" >
		</div>
		<div class="col-md-6">
			<label for="">Select Country</label>
			<select name="country" class="form-control form-control-sm" required>
				<option value="" ><?= $language::translate('Select Country') ?></option>

				<?php
	foreach ((new Pemm\Model\Country())->findAll() as $country) {
		echo '<option value="'.$country->getId().'">'.$country->getName().'</option>';
	}
				?>

			</select>
		</div>
	</div>
	<div class="form-group">
        <label for="">City</label>
        <input name="city" type="text" value="" class="form-control form-control-sm" required/>
    </div>
	<div class="form-group">
        <label for="">Address</label>
        <input type="text" name="address" class="form-control form-control-sm" required/>
    </div>
	<div class="row mb-1">
		<div class="col-md-6">
			<label for="exampleInputPassword1">Password</label>
        	<input type="password" id="password" name="password" class="form-control form-control-sm" required />
		</div>
		<div class="col-md-6">
			<label for="exampleInputPassword1">Repeat Password</label>
        	<input type="password" id="password" name="password" type="password" value=""  class="form-control form-control-sm" required />
		</div>
	</div>
	<div class="field-wrapper terms_condition">
                                    <div class="n-chk new-checkbox checkbox-outline-primary">
                                        <label class="new-control new-checkbox checkbox-outline-primary">
                                          <input id="rules_check" type="checkbox" class="new-control-input" name="terms" required>
                                          <span class="new-control-indicator"></span><span>
                                    <a data-toggle="modal" data-target="#exampleModal" href="#exampleModal" href="#exampleModal"> <?= $language::translate('Accept the terms and conditions') ?> </a> </span>
                                        </label>
                                    </div>
                                </div>
     <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
    <button type="submit" class="btn btn-login btn-block">Sign in</button>

    <div class="sign-up">Already Registered? <a href="/panel/login">Login</a></div>
</form>

  </div>
  <video src="https://files.midnight-performance.com/assets/video/file.mp4" autoplay loop muted></video>
</section>
<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="\assets\js\libs\jquery-3.1.1.min.js"></script>
    <script src="\bootstrap\js\popper.min.js"></script>
    <script src="\bootstrap\js\bootstrap.min.js"></script>

    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="\assets\js\authentication\form-1.js"></script>
     <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"
/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <script>
var input = document.querySelector("#phone");
var full_phone = document.getElementById("full_phone");

var iti = window.intlTelInput(input, {
  utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
  separateDialCode: true,
  nationalMode: false,
  formatOnDisplay: false,
  autoHideDialCode: false,
});

function handleChange() {
  full_phone.value = iti.getNumber();
}

input.addEventListener('change', handleChange);
input.addEventListener('keyup', handleChange);

</script>

<style>
textarea {
  color: #fff;
    }

.form-image .l-image {
    background-image: url(/assets/img/arkaplan2.jpg);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #060818;
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    background-position-x: center;
    background-position-y: center;
}

.form-form .form-form-wrap form .field-wrapper select,
.form-form .form-form-wrap form .field-wrapper textarea
 {
   display: inline-block;
   vertical-align: middle;
   border-radius: 0;
   min-width: 50px;
   max-width: 635px;
   width: 100%;
   min-height: 36px;
   background-color: #e8f0fe;
   border: none;
   -ms-transition: all 0.2s ease-in-out 0s;
   transition: all 0.2s ease-in-out 0s;
    font-weight: 600;
    border-bottom: 1px solid #e0e6ed;
    padding: 0px 0 7px 33px;
   font-size: 14px;
   color: #c0c2c9;
}
</style>
<script>
function selectLanguage(languageCode) {
    document.body.innerHTML += '<form id="langForm" action="' + window.location.href + '" method="post">' +
        '<input type="hidden" name="lang" value="' + languageCode + '"></form>';
    document.getElementById("langForm").submit();
}

function checkRules() {

     document.getElementById("rules_check").checked = true;

}
function checkStatusofRadio() {
 var durum =  document.querySelector('input[name="customRadioInline1"]:checked').value;
 if(durum==2) {
   $("#forCompany").show();
 }else{
   $("#forCompany").hide();
 }
}
</script>
<script src="https://www.google.com/recaptcha/api.js?render=<?=$setting->getGoogleKey();?>"></script>
   <script>
       grecaptcha.ready(() => {
           grecaptcha.execute('<?=$setting->getGoogleKey();?>', { action: 'login' }).then(token => {
             document.querySelector('#recaptchaResponse').value = token;
           });
       });
   </script>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= $language::translate('Terms and conditions') ?></h5>
            </div>
            <div class="modal-body">
                <p class="modal-text">
                <?=$setting->getDescription();?>
              </p>
            </div>
            <div class="modal-footer">
                 <button type="button" onclick="checkRules();"  data-dismiss="modal" class="btn btn-primary"><?= $language::translate('I agree.') ?></button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
