<?php

use Pemm\Core\Container;
use Pemm\Model\Customer;

global $container;

/* @var Customer $customer */
$customer = $container->get('customer');

$language = $container->get('language');

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('About Us') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
	<link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="\plugins\bootstrap-select\bootstrap-select.min.css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\datatables.css">
    <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\dt-global_style.css">
	<link rel="stylesheet" href="\assets/css/themify-icons.css">
 <link rel="stylesheet" href="\assets/css/ie7/ie7.css">
 <link href="\assets/css/users/user-profile.css" rel="stylesheet" type="text/css" />



  </head>
<body>
   	<?php include("ust2.php")?>
	 <div class="sub-header-container">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>

            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">

                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);"><?= $language::translate('Dashboard') ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><span><?= $language::translate('About Us') ?></span></li>
                            </ol>
                        </nav>

                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
	<?php include("ust.php")?>
         <div id="content" class="main-content">
            <div class="layout-px-spacing">
              <div class="row layout-spacing">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 layout-top-spacing">
                        <div class="user-profile layout-spacing">
                            <div class="widget-content widget-content-area">
                                <div class="user-info-list">
                                    <div class="">
                                    <img style="    width: 100%;" src="\assets/img/hakkimizda.jpeg">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 layout-top-spacing">
                        <div class="skills layout-spacing ">
                            <div class="widget-content widget-content-area">
                              <h3 style="    font-size: 40px;    margin: 24px 0px 22px 0;    text-align: center;" class=""><?= $language::translate('About Us') ?></h3>
                              <p style=" padding-top: 8px !important; padding: 43px;">
                                Thanks to the new tuning file exchange methods it has become much easier to provide tuning companies with tuned files for their vehicles, where many new tuning companies require them to continue advancing and improving the motor world, as it is a growing business.
<br><br>
    In Sertronic Solution we are a specialized team of engineers and automotive technicians some of third generation dedicated to the engine that serve to provide the necessary files for new companies dedicated to the world of the engine.
<br><br>
    Sertronic Solution was born in 2007 from a strong passion for the world of engines and electronic management systems. We stand out for having knowledge of almost all brands of cars on the market. Moreover, thanks to our fast and reliable delivery, we are one of the top companies dedicated to the tuned files in our sector.
                            </p>
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

	</body>
</html>
