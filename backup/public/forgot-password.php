<?php

use Pemm\Core\Container;
use Pemm\Core\Language;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

/* @var Language $language*/
$language = $container->get('language');

/* @var Session $session*/
$session = $container->get('session');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $language::translate('Forgot Password') ?> - <?= SITE_NAME ?> </title>
    <?php include("css.php")?>
    <link href="\assets\css\authentication\form-1.css" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
</head>
<body class="form">
<div class="form-container">
    <div class="form-form">
        <div class="form-form-wrap">
            <div class="form-container">
                <div class="form-content">
                    <h1 class=""><?= $language::translate('Forgot Password?') ?></h1>
                    <p class="signup-link"><?= $language::translate('Not a member?') ?>
                        <a href="/panel/register"><?= $language::translate('Sing in') ?></a></p>
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
                                <input id="email" name="email" type="email" value="" placeholder="<?= $language::translate('E-mail') ?>" required>
                            </div>
                            <div class="d-sm-flex justify-content-between">
                                <div class="field-wrapper">
                                    <button type="submit" class="btn btn-primary" value=""><?= $language::translate('Send') ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <p class="terms-conditions">© <?php echo date("Y"); ?> All Rights Reserved. <a href="index.html"><?= SITE_NAME ?></a>      </p>
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
<script src="\assets\js\libs\jquery-3.1.1.min.js"></script>
<script src="\bootstrap\js\popper.min.js"></script>
<script src="\bootstrap\js\bootstrap.min.js"></script>
<script src="\assets\js\authentication\form-1.js"></script>

</body>
</html>
