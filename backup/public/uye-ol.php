<?php

use Pemm\Core\Container;
use Pemm\Core\Language;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Setting;

global $container;

/* @var Language $language*/
$language = $container->get('language');

/* @var Session $session*/
$session = $container->get('session');

$language = $container->get('language');

$setting = (new Setting())->find(1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('Register') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
    <link href="\assets\css\authentication\form-1.css" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
    <link rel="stylesheet" href="/assets/js/telinput/css/intlTelInput.css">
</head>
<body class="form">

    <div class="form-container">
        <div class="form-form">
            <div class="form-form-wrap">
                <div  style="    margin-top: 36px;" class="form-container">
                    <div class="form-content">
                    <a href="../">  <img style="width: 150px; margin-bottom: 25px; " src="<?=$setting->getsiteUrl();?>/assets/img/<?=$setting->getLogo2();?>">

                      <div style="    float: right;" >
                      <?php
                      $dill = [
                      "tr" => "Türkçe",
                      "en" => "English",
                      "es" => "Español",
                      "de" => "Deutsch",
                      "nl" => "Nederlands",
                      "ru" => "Русский",
                      "ar" => "العربية",
                      "pt" => "Português",
                      "fr" => "Français",
                      "it" => "Italiano",
                      'gr' =>  'Ελληνικά',
                      "sk" => "Slovenský",
                      "hu" => "Magyar",
                      "cz" => "Česky",
                      "po" => "Polski",
					     "no" => "Norsk",
                      "he" => "Ελληνικά"
                      ];
                      ?> </a>
                                                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="language-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <?php echo $language::LANGUAGES[$session->get('language')]; ?>
                                                    </a>



                                                    <div class="dropdown-menu position-absolute" aria-labelledby="language-dropdown">
                                                        <?php
                                                        foreach ($language::LANGUAGES as $langCode => $flag) {?>
                                                            <a class="dropdown-item d-flex change" href="javascript:void(0);" onclick="selectLanguage('<?=$langCode?>')">
                                                                <span class="align-self-center"><?= $flag ?> <?= $dill[$langCode.""] ?></span>
                                                            </a>
                                                        <?php }
                                                        ?>
                                                    </div>
                      </div>
                        <h1 class=""><?= $language::translate('Sing in') ?></h1>
                        <p class="signup-link"><?= $language::translate('Already Registered? <a href=":url">Sign In</a>', ['url' => '/panel/login']) ?></p>
                        <form action="" method="post" class="text-left">

                            <?php
                            foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                                <div class="alert alert-<?= $type ?>">
                                    <?php foreach ($messages as $message) { echo $message;} ?>
                                </div>
                            <?php }
                            ?>
                            <input type="hidden" name="csrf_token" value="<?= $container->get('csrf_token') ?>">
                            <div class="form">
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

                                <div id="email-field" class="field-wrapper input">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
 <input id="text" name="firstName" type="text" value="" placeholder="<?= $language::translate('First name') ?>" required>
                                </div>

                                <div id="email-field" class="field-wrapper input">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
<input id="text" name="lastName" type="text" value="" placeholder="<?= $language::translate('Last name') ?>" required>
                                </div>
                                <div id="email-field" class="field-wrapper input">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                                    <input id="email" name="email" type="email" value="" placeholder="<?= $language::translate('E-Mail') ?>" required>
                                </div>

                                <div style="display:none;" id="forCompany">
                                <div id="email-field" class="field-wrapper input">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="#4361ee" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                    <input id="text" name="companyName" type="text" value="" placeholder="<?= $language::translate('Company Name') ?>" >
                                </div>

                                <div id="email-field" class="field-wrapper input">
                                  <svg viewBox="0 0 24 24" width="24" height="24" stroke="#4361ee" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="9"></rect><rect x="14" y="7" width="3" height="5"></rect></svg>
                                    <input id="text" name="vatNumber" type="text" value="" placeholder="<?= $language::translate('VAT Number') ?>" >
                                </div>
                              </div>


                                <div id="email-field" class="field-wrapper input">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
               <input id="phone" type="tel"  style="    width: 144%;
"  name="contactNumber" type="text" value="" placeholder="<?= $language::translate('Contact Number') ?>" required>
                                               </div>
                                               <input type="hidden" id="full_phone" name="full_phone" value="">

                                               <div id="email-field" class="field-wrapper input">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
               <input id="text" name="evcnumber" type="text" value="" placeholder="<?= $language::translate('Evc Number') ?>">
                                               </div>


                                <div id="email-field" class="field-wrapper input">
                                  <svg viewBox="0 0 24 24" width="24" height="24" stroke="#4361ee" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                <select name="country" required>
                                    <option value="" ><?= $language::translate('Select Country') ?></option>

                                    <?php
                                        foreach ((new Pemm\Model\Country())->findAll() as $country) {
                                          echo '<option value="'.$country->getId().'">'.$country->getName().'</option>';
                                        }
                                     ?>

                                  </select>
                                </div>
                                <div id="email-field" class="field-wrapper input">
                                  <svg viewBox="0 0 24 24" width="24" height="24" stroke="#4361ee" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>
 <input name="city" type="text" value="" placeholder="<?= $language::translate('Select City') ?>" required>
                                </div>
                                <div id="email-field" class="field-wrapper input">
                                  <svg viewBox="0 0 24 24" width="24" height="24" stroke="#4361ee" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path></svg>
 <textarea name="address" placeholder="<?= $language::translate('Address') ?>" required></textarea>
                                </div>


                                <div id="password-field" class="field-wrapper input mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    <input id="password" name="password" type="password" value="" placeholder="<?= $language::translate('Password') ?>" required>
                                </div>
                                 <div id="password-field" class="field-wrapper input mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    <input id="password" name="password" type="password" value="" placeholder="<?= $language::translate('Repeat Password') ?>" required>
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
                                <div style="    margin-bottom: 23px;" class="field-wrapper">
                                 <div  style="text-align: center;"  class="h-captcha" data-sitekey="211485ba-526c-4113-b692-1668875d35af"></div>
                               </div>
                                <div class="d-sm-flex justify-content-between">
                                    <div class="field-wrapper toggle-pass">
                                         <label class="switch s-primary">
                                            <input type="checkbox" id="toggle-password" class="d-none">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="field-wrapper">
                                        <button type="submit" class="btn btn-primary" value=""><?= $language::translate('Sing in') ?></button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

                        </form>
                        <p class="terms-conditions">© <?php echo date("Y"); ?> <?= $language::translate('All rights reserved') ?>. <a href="#"><?= SITE_NAME ?> </a></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-image">
            <div style="background-image: url(<?=$setting->getsiteUrl();?>/assets/img/<?=$setting->getRegister();?>);" class="l-image">
            </div>
        </div>
    </div>


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
