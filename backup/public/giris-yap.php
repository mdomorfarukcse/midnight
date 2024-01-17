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
     <title><?= $language::translate('Login') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
    <link href="\assets\css\authentication\form-1.css" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
    <link rel="stylesheet" href="/assets/demo.css">
    <link rel="stylesheet" href="/assets/cookieconsent.css" media="print" onload="this.media='all'">

</head>
<body class="form">

    <div class="form-container">

        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">
                      <img style="width: 150px; margin-bottom: 25px; " src="<?=$setting->getsiteUrl();?>/assets/img/<?=$setting->getLogo2();?>">
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
                      "sk" => "Slovenský",
                      'gr' =>  'Ελληνικά',
                      "hu" => "Magyar",
                      "cz" => "Česky",
                      "he" => "עִבְרִית‎",
                      "no" => "Norsk‎",
                      "po" => "Polski"
                      ];
                      ?>
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
                        <h1 class=""><?= $language::translate('<a href="#"><span class="brand-name">Login</span></a> to the system') ?></h1>
                        <p class="signup-link"><?= $language::translate('Not a member?') ?>
                            <a href="/panel/register"><?= $language::translate('Sign up') ?></a></p>


                        <form action="" id="login" method="post" class="text-left">
                            <?php
                            foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                                    <div class="alert alert-<?= $type ?>">
                                        <?php foreach ($messages as $message) { echo $message;} ?>
                                    </div>
                            <?php }
                            ?>
                            <input type="hidden" name="csrf_token" value="<?= $container->get('csrf_token') ?>">
                            <div class="form">
                                <div id="username-field" class="field-wrapper input">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                                    <input id="email" name="email" type="email" value="" placeholder="<?= $language::translate('E-Mail') ?>" required>
                                </div>

                                <div id="password-field" class="field-wrapper input mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    <input id="password" name="password" type="password" class="form-control" placeholder="<?= $language::translate('Password') ?>" required>
                                </div>

                                <div class="d-sm-flex justify-content-center">
                                    <div class="field-wrapper toggle-pass">
                                         <label class="switch s-primary">
                                            <input type="checkbox" id="toggle-password" class="d-none">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="field-wrapper">
                                        <button class="btn btn-primary"


         type="submit" ><?= $language::translate('Login') ?></button>
                                    </div>

                                </div>

                                <div class="field-wrapper text-center keep-logged-in">
                                    <div class="n-chk new-checkbox checkbox-outline-primary">
                                        <label class="new-control new-checkbox checkbox-outline-primary">
                                          <input type="checkbox" name="remember_me" class="new-control-input">
                                          <span class="new-control-indicator"></span><?= $language::translate('Remember Me') ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="field-wrapper">
                                    <a href="/panel/forgot-password" class="forgot-pass-link"><?= $language::translate('Forgot Password?') ?></a>
                                </div>

                            </div>
                            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

                        </form>
                        <p class="terms-conditions">© <?php echo date("Y"); ?> <?= $language::translate('All rights reserved') ?>. <a href="#"><?= SITE_NAME ?> </a>      </p>

                    </div>
                </div>
            </div>
        </div>
        <div class="form-image">
          <div style="background-image: url(<?=$setting->getsiteUrl();?>/assets/img/<?=$setting->getLogin();?>);" class="l-image">
            </div>
        </div>
    </div>


    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="\assets\js\libs\jquery-3.1.1.min.js"></script>
    <script src="\bootstrap\js\popper.min.js"></script>
    <script src="\bootstrap\js\bootstrap.min.js"></script>
     <script src="\assets\js\authentication\form-1.js"></script>
<script>
function selectLanguage(languageCode) {
    document.body.innerHTML += '<form id="langForm" action="' + window.location.href + '" method="post">' +
        '<input type="hidden" name="lang" value="' + languageCode + '"></form>';
    document.getElementById("langForm").submit();
}
</script>
<script defer src="/assets/cookieconsent.js"></script>
<script src="/assets/demo.js" defer></script>
<script defer src="/assets/cookieconsent-init.js"></script>
<script src="https://www.google.com/recaptcha/api.js?render=<?=$setting->getGoogleKey();?>"></script>
   <script>
       grecaptcha.ready(() => {
           grecaptcha.execute('<?=$setting->getGoogleKey();?>', { action: 'login' }).then(token => {
             document.querySelector('#recaptchaResponse').value = token;
           });
       });
   </script>

</body>
</html>
