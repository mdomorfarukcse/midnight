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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $language::translate('Forgot Password') ?> - <?= SITE_NAME ?> </title>
   <?php include("css.php")?>
    <style>
    body {
            margin: 0;
            overflow: hidden;
        }
      .banner-3 {
      width: 100vw;
      height: 100vh;
      max-width: 100%;
      position: relative;
      overflow: hidden;
    }
    .banner-3 .banner-content-3 {
      width: 500px;
      padding: 50px;
      border-radius: 5px;
      background-color: #263a54c7;
      position: absolute;
      top: 50%;
      left: 50%;
      -webkit-transform: translate(-50%, -50%);
      transform: translate(-50%, -50%);
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
    </style>
</head>
<body>
    <section class="banner-3">
  <div class="banner-content-3">
    <h1><?= $language::translate('Forgot Password?') ?></h1>
   <form action="" id="login" method="post">
    <?php
	foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
	   <div class="alert alert-<?= $type ?>">
		   <?php foreach ($messages as $message) { echo $message;} ?>
	   </div>
	   <?php }
	   ?>
     <input type="hidden" name="csrf_token" value="<?= $container->get('csrf_token') ?>">
    <!-- to error: add class "has-danger" -->
    <div class="form-group">
        <label for="exampleInputEmail1">Email</label>
        <input type="email" class="form-control form-control-sm"  id="email" name="email" required/>
    </div>
    <button type="submit" class="btn btn-login btn-block"><?= $language::translate('Send') ?></button>

    <div class="sign-up">Don't have an account? <a href="/panel/register">Create One</a></div>
</form>

  </div>
  <video src="https://files.midnight-performance.com/assets/video/file.mp4" autoplay loop muted></video>
</section>
<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
  
<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="\assets\js\libs\jquery-3.1.1.min.js"></script>
<script src="\bootstrap\js\popper.min.js"></script>
<script src="\bootstrap\js\bootstrap.min.js"></script>
<script src="\assets\js\authentication\form-1.js"></script>
</body>
</html>
