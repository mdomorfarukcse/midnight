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
     <title><?= $language::translate('Privacy Policy') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
	<link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="\plugins\bootstrap-select\bootstrap-select.min.css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\datatables.css">
    <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\dt-global_style.css">
	<link rel="stylesheet" href="\assets/css/themify-icons.css">
 <link rel="stylesheet" href="\assets/css/ie7/ie7.css">
     <link rel="stylesheet" type="text/css" href="\plugins\bootstrap-touchspin\jquery.bootstrap-touchspin.min.css">
    <style>
        #demo_vertical::-ms-clear, #demo_vertical2::-ms-clear { display: none; }
        input#demo_vertical { border-top-right-radius: 5px; border-bottom-right-radius: 5px; }
        input#demo_vertical2 { border-top-right-radius: 5px; border-bottom-right-radius: 5px; }
    </style>
    <link rel="stylesheet" type="text/css" href="\assets\css\widgets\modules-widgets.css">

  </head>
<body>
   	<?php include("ust2.php")?>
	  
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
	<?php include("ust.php")?>
  <div id="content" class="main-content">
     <div class="layout-px-spacing">
       <div class="row layout-spacing">
             <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 layout-top-spacing">
                 <div class="skills layout-spacing ">
                     <div class="widget-content widget-content-area">
                       <h3 style="    font-size: 40px;    margin: 24px 0px 22px 0;    text-align: center;" class=""><?= $language::translate('Privacy Policy') ?></h3>
                       <div style=" padding-top: 8px !important; padding: 43px;">
  <?=$setting->getPrivacy();?>
</div>
                     </div>
                 </div>

             </div>

         </div>
     </div>

			  <?php include("alt.php")?>
    </div>
     <?php include("js.php")?>
	     <script src="\plugins\bootstrap-select\bootstrap-select.min.js"></script>
     <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="\plugins\table\datatable\datatables.js"></script>


	<style>
	div.dataTables_wrapper div.dataTables_info {
    display: none;
}
</style>
    <script src="\plugins\table\datatable\datatables.js"></script>
    <script src="\plugins\table\datatable\button-ext\dataTables.buttons.min.js"></script>
    <script src="\assets\js\apps\invoice-list.js"></script>
	    <script src="\plugins\bootstrap-touchspin\jquery.bootstrap-touchspin.min.js"></script>
    <script src="\plugins\bootstrap-touchspin\custom-bootstrap-touchspin.js"></script>


	</body>
</html>
