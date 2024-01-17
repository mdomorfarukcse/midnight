<?php
use Pemm\Model\Setting;

use Pemm\Core\Container;
use Pemm\Core\Language;
 
global $container;
$setting = (new Setting())->find(1);

/* @var Language $language*/
$language = $container->get('language');
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('Login') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
    <link href="<?= SITE_URL ?>\assets\css\authentication\form-1.css" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\assets\css\forms\theme-checkbox-radio.css">
</head>
<body class="form">
    <div class="form-container">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">
                        <h1 class=""><?= $language::translate('<a href="#"><span class="brand-name">Login</span></a> to the system') ?></h1>
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
                                <div id="username-field" class="field-wrapper input">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                                    <input id="email" name="email" type="text" value="" placeholder="<?= $language::translate('E-mail') ?>">
                                </div>

                                <div id="password-field" class="field-wrapper input mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    <input id="password" name="password" type="password" class="form-control" placeholder="<?= $language::translate('Password') ?>">
                                </div>
                                <div class="d-sm-flex justify-content-between">
                                    <div class="field-wrapper toggle-pass">
                                         <label class="switch s-primary">
                                            <input type="checkbox" id="toggle-password" class="d-none">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="field-wrapper">
                                        <button type="submit" class="btn btn-primary" value=""><?= $language::translate('Login') ?></button>
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
                            </div>
                        </form>
                        <p class="terms-conditions">© <?php echo date("Y"); ?> All Rights Reserved. <a href="#"><?= SITE_NAME ?> </a>      </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-image">
            <div class="l-image">
            </div>
        </div>
    </div>
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="<?= SITE_URL ?>\assets\js\libs\jquery-3.1.1.min.js"></script>
    <script src="<?= SITE_URL ?>\bootstrap\js\popper.min.js"></script>
    <script src="<?= SITE_URL ?>\bootstrap\js\bootstrap.min.js"></script>
    <script src="<?= SITE_URL ?>\assets\js\authentication\form-1.js"></script>
</body>
</html>
