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
     <title><?= $language::translate('Contact Us') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
	<link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="\plugins\bootstrap-select\bootstrap-select.min.css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\datatables.css">
    <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\dt-global_style.css">
	<link rel="stylesheet" href="\assets/css/themify-icons.css">
 <link rel="stylesheet" href="\assets/css/ie7/ie7.css">
 <link href="\assets/css/pages/contact_us.css" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" type="text/css" href="\assets/css/forms/theme-checkbox-radio.css">
 <link rel="stylesheet" type="text/css" href="\assets/css/elements/alert.css">



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
                                <li class="breadcrumb-item active" aria-current="page"><span><?= $language::translate('Contact Us') ?></span></li>
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
              <div class="contact-us layout-top-spacing">
                  <div class="cu-contact-section">
                      <iframe src="https://yandex.com/map-widget/v1/?um=constructor%3A201ba6b6cad36d28932673c7d318617a7fb6583d92d486281b764392d7631322&amp;source=constructor" width="100%" height="655" frameborder="0"></iframe>
                      <div class="contact-form">
                          <form class="">

                              <div  style="margin-bottom: 0px;" class="cu-section-header">
                                  <h4><?= $language::translate('Contact Us') ?></h4>
                                  <p><b>Whatsapp: </b> <a href="https://wa.me/message/HEN4U3ZXBFPTJ1"> https://wa.me/message/HEN4U3ZXBFPTJ1 </a><br>
<b>Email: </b> <a href="mailto:sertronicsolution@gmail.com"> sertronicsolution@gmail.com </a><br>
<b>Facebook:</b><a href="https://www.facebook.com/Sertronic-Solution-113584377075439/">  https://www.facebook.com/Sertronic-Solution-113584377075439/</a></p>
                              </div>

                              <hr style="border-top: 1px solid #bacdcd;">
                              <div class="row mb-4">
                                  <div class="col-sm-12 col-12 input-fields">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                      <input type="text" class="form-control" placeholder="<?= $language::translate('Name') ?>">
                                  </div>
                              </div>
                              <div class="row mb-4">
                                  <div class="col-sm-12 col-12 input-fields">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                                      <input type="text" class="form-control" placeholder="<?= $language::translate('Email') ?>">
                                  </div>
                              </div>
                              <div class="row mb-4">
                                  <div class="col-sm-12 col-12 input-fields">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                      <input type="text" class="form-control" placeholder="<?= $language::translate('Phone') ?>">
                                  </div>
                              </div>

                              <div class="row">
                                  <div class="col">
                                      <div class="form-group input-fields">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                          <textarea class="form-control" id="exampleFormControlTextarea1" rows="4" placeholder="<?= $language::translate('Message') ?>"></textarea>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col text-sm-left text-center">
                                      <button class="btn btn-primary mt-4"><?= $language::translate('Send') ?></button>
                                  </div>
                              </div>
                          </form>

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
