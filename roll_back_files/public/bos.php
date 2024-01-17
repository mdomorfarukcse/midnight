<?php

use Pemm\Core\Container;
use Pemm\Core\Language;
use Symfony\Component\HttpFoundation\Session\Session;

global $container;

/* @var Language $language*/
$language = $container->get('language');

/* @var Session $session*/
$session = $container->get('session');

?>
<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('Home') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
	<link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="\plugins\bootstrap-select\bootstrap-select.min.css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
    <link href="\assets\css\tables\table-basic.css" rel="stylesheet" type="text/css">
    <link href="\assets\css\components\custom-list-group.css" rel="stylesheet" type="text/css">

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
                                <li class="breadcrumb-item active" aria-current="page"><span><?= $language::translate('Home') ?></span></li>
                            </ol>
                        </nav>

                    </div>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                         <li class="nav-item">
                             <a class="nav-link" href="#"><?= $language::translate('System Time') ?>:  <?php $date = new DateTime('now');
          echo $date->format("H:i:s"); ?></a>
                         </li>
                     </ul>
        </header>
    </div>
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
	<?php include("ust.php")?>
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">

				<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-table-one">
                            <div class="widget-heading">
                                <h5 class="">Transactions</h5>
                                <div class="task-action">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle" href="#" role="button" id="pendingTask" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="pendingTask" style="will-change: transform;">
                                            <a class="dropdown-item" href="javascript:void(0);">View Report</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Edit Report</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Mark as Done</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="widget-content">

                                <div class="transactions-list t-info">
                                    <div class="t-item">
                                        <div class="t-company-name">
                                            <div class="t-icon">
                                                <div class="avatar avatar-xl">
                                                    <span class="avatar-title">SP</span>
                                                </div>
                                            </div>
                                            <div class="t-name">
                                                <h4>Shaun Park</h4>
                                                <p class="meta-date">10 Jan 1:00PM</p>
                                            </div>
                                        </div>
                                        <div class="t-rate rate-inc">
                                            <p><span>+$36.11</span></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="transactions-list">
                                    <div class="t-item">
                                        <div class="t-company-name">
                                            <div class="t-icon">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                                </div>
                                            </div>
                                            <div class="t-name">
                                                <h4>Electricity Bill</h4>
                                                <p class="meta-date">04 Jan 1:00PM</p>
                                            </div>

                                        </div>
                                        <div class="t-rate rate-dec">
                                            <p><span>-$16.44</span></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="transactions-list">
                                    <div class="t-item">
                                        <div class="t-company-name">
                                            <div class="t-icon">
                                                <div class="avatar avatar-xl">
                                                    <span class="avatar-title">AD</span>
                                                </div>
                                            </div>
                                            <div class="t-name">
                                                <h4>Amy Diaz</h4>
                                                <p class="meta-date">31 Jan 1:00PM</p>
                                            </div>

                                        </div>
                                        <div class="t-rate rate-inc">
                                            <p><span>+$66.44</span></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="transactions-list t-secondary">
                                    <div class="t-item">
                                        <div class="t-company-name">
                                            <div class="t-icon">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                                </div>
                                            </div>
                                            <div class="t-name">
                                                <h4>Netflix</h4>
                                                <p class="meta-date">02 Feb 1:00PM</p>
                                            </div>

                                        </div>
                                        <div class="t-rate rate-dec">
                                            <p><span>-$32.00</span></p>
                                        </div>
                                    </div>
                                </div>


                                <div class="transactions-list t-info">
                                    <div class="t-item">
                                        <div class="t-company-name">
                                            <div class="t-icon">
                                                <div class="avatar avatar-xl">
                                                    <span class="avatar-title">DA</span>
                                                </div>
                                            </div>
                                            <div class="t-name">
                                                <h4>Daisy Anderson</h4>
                                                <p class="meta-date">15 Feb 1:00PM</p>
                                            </div>
                                        </div>
                                        <div class="t-rate rate-inc">
                                            <p><span>+$10.08</span></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="transactions-list">
                                    <div class="t-item">
                                        <div class="t-company-name">
                                            <div class="t-icon">
                                                <div class="avatar avatar-xl">
                                                    <span class="avatar-title">OG</span>
                                                </div>
                                            </div>
                                            <div class="t-name">
                                                <h4>Oscar Garner</h4>
                                                <p class="meta-date">20 Feb 1:00PM</p>
                                            </div>

                                        </div>
                                        <div class="t-rate rate-dec">
                                            <p><span>-$22.00</span></p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">

                        <div class="widget widget-activity-four">

                            <div class="widget-heading">
                                <h5 class="">Recent Activities</h5>
                            </div>

                            <div class="widget-content">

                                <div class="mt-container mx-auto ps ps--active-y">
                                    <div class="timeline-line">

                                        <div class="item-timeline timeline-primary">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p><span>Updated</span> Server Logs</p>
                                                <span class="badge">Pending</span>
                                                <p class="t-time">Just Now</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline timeline-success">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p>Send Mail to <a href="javascript:void(0);">HR</a> and <a href="javascript:void(0);">Admin</a></p>
                                                <span class="badge">Completed</span>
                                                <p class="t-time">2 min ago</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline  timeline-danger">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p>Backup <span>Files EOD</span></p>
                                                <span class="badge">Pending</span>
                                                <p class="t-time">14:00</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline  timeline-dark">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p>Collect documents from <a href="javascript:void(0);">Sara</a></p>
                                                <span class="badge">Completed</span>
                                                <p class="t-time">16:00</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline  timeline-warning">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p>Conference call with <a href="javascript:void(0);">Marketing Manager</a>.</p>
                                                <span class="badge">In progress</span>
                                                <p class="t-time">17:00</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline  timeline-secondary">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p>Rebooted Server</p>
                                                <span class="badge">Completed</span>
                                                <p class="t-time">17:00</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline  timeline-warning">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p>Send contract details to Freelancer</p>
                                                <span class="badge">Pending</span>
                                                <p class="t-time">18:00</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline  timeline-dark">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p>Kelly want to increase the time of the project.</p>
                                                <span class="badge">In Progress</span>
                                                <p class="t-time">19:00</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline  timeline-success">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p>Server down for maintanence</p>
                                                <span class="badge">Completed</span>
                                                <p class="t-time">19:00</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline  timeline-secondary">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p>Malicious link detected</p>
                                                <span class="badge">Block</span>
                                                <p class="t-time">20:00</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline  timeline-warning">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p>Rebooted Server</p>
                                                <span class="badge">Completed</span>
                                                <p class="t-time">23:00</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline timeline-primary">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p><span>Updated</span> Server Logs</p>
                                                <span class="badge">Pending</span>
                                                <p class="t-time">Just Now</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline timeline-success">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p>Send Mail to <a href="javascript:void(0);">HR</a> and <a href="javascript:void(0);">Admin</a></p>
                                                <span class="badge">Completed</span>
                                                <p class="t-time">2 min ago</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline  timeline-danger">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p>Backup <span>Files EOD</span></p>
                                                <span class="badge">Pending</span>
                                                <p class="t-time">14:00</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline  timeline-dark">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p>Collect documents from <a href="javascript:void(0);">Sara</a></p>
                                                <span class="badge">Completed</span>
                                                <p class="t-time">16:00</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline  timeline-warning">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p>Conference call with <a href="javascript:void(0);">Marketing Manager</a>.</p>
                                                <span class="badge">In progress</span>
                                                <p class="t-time">17:00</p>
                                            </div>
                                        </div>

                                    </div>
                                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 326px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 182px;"></div></div><div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 326px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 182px;"></div></div></div>

                                <div class="tm-action-btn">
                                    <button class="btn"><span>View All</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></button>
                                </div>
                            </div>
                        </div>
                    </div>
				<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">

                        <div class="widget widget-account-invoice-three">

                            <div class="widget-heading">
                                <div class="wallet-usr-info">
                                    <div class="usr-name">
                                        <span><img src="\assets\img\profile-32.jpeg" alt="admin-profile" class="img-fluid"> Midnight Performance</span>
                                    </div>
                                    <div class="add">
                                        <span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></span>
                                    </div>
                                </div>
                                <div class="wallet-balance">
                                    <p>Bakiye</p>
                                    <h5 class=""><span class="w-currency">200</span> Kredi</h5>
                                </div>
                            </div>

                            <div class="widget-amount">

                                <div class="w-a-info funds-received">
                                    <span>Received <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-up"><polyline points="18 15 12 9 6 15"></polyline></svg></span>
                                    <p>$97.99</p>
                                </div>

                                <div class="w-a-info funds-spent">
                                    <span>Spent <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></span>
                                    <p>$53.00</p>
                                </div>
                            </div>

                            <div class="widget-content">

                                <div class="bills-stats">
                                    <span>Pending</span>
                                </div>

                                <div class="invoice-list">

                                    <div class="inv-detail">
                                        <div class="info-detail-1">
                                            <p>Netflix</p>
                                            <p><span class="w-currency">$</span> <span class="bill-amount">13.85</span></p>
                                        </div>
                                        <div class="info-detail-2">
                                            <p>BlueHost VPN</p>
                                            <p><span class="w-currency">$</span> <span class="bill-amount">15.66</span></p>
                                        </div>
                                    </div>

                                    <div class="inv-action">
                                        <a href="javascript:void(0);" class="btn btn-outline-primary view-details">View Details</a>
                                        <a href="javascript:void(0);" class="btn btn-outline-primary pay-now">Pay Now $29.51</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-table-two">
                            <div class="widget-content">
                               <ul class="nav nav-tabs  mb-3" id="lineTab" role="tablist">
    <li class="nav-item">
        <a style="text-align:center;"  class="nav-link active" id="underline-home-tab" data-toggle="tab" href="#underline-home" role="tab" aria-controls="underline-home" aria-selected="true"><img style="    width: 70px;" src="\assets/img/ikonlar/1.png"> <br> Araba</a>
    </li>
    <li class="nav-item">
        <a style="text-align:center;"  class="nav-link" id="underline-profile-tab" data-toggle="tab" href="#underline-profile" role="tab" aria-controls="underline-profile" aria-selected="false"><img  style="    width: 70px;" src="\assets/img/ikonlar/3.png"><br>  Minibüs</a>
    </li>
    <li class="nav-item">
        <a style="text-align:center;"  class="nav-link" id="underline-contact-tab" data-toggle="tab" href="#underline-contact" role="tab" aria-controls="underline-contact" aria-selected="false"><img style="    width: 70px;" src="\assets/img/ikonlar/2.png"> <br> Pickup</a>
    </li>
    <li class="nav-item">
        <a style="text-align:center;"  class="nav-link" id="underline-contact-tab" data-toggle="tab" href="#underline-contact" role="tab" aria-controls="underline-contact" aria-selected="false"><img style="    width: 70px;" src="\assets/img/ikonlar/4.png"><br>  Kamyon</a>
    </li>
    <li class="nav-item">
        <a style="text-align:center;"  class="nav-link" id="underline-contact-tab" data-toggle="tab" href="#underline-contact" role="tab" aria-controls="underline-contact" aria-selected="false"><img style="    width: 70px;" src="\assets/img/ikonlar/6.png"><br>  Karavan</a>
    </li>
    <li class="nav-item">
        <a style="text-align:center;"  class="nav-link" id="underline-contact-tab" data-toggle="tab" href="#underline-contact" role="tab" aria-controls="underline-contact" aria-selected="false"><img style="    width: 70px;" src="\assets/img/ikonlar/5.png"> <br> Traktör</a>
    </li>
</ul>

<div class="tab-content" id="lineTabContent-3">
    <div class="tab-pane fade show active" id="underline-home" role="tabpanel" aria-labelledby="underline-home-tab">

<div class="row">

 <div class="col-sm-4">
          <select class="selectpicker">
    <option>Marka Seçiniz</option>
    <option>Ketchup</option>
    <option>Relish</option>
</select>
    </div>

 <div class="col-sm-4">
          <select class="selectpicker">
    <option>Model Seçiniz</option>
    <option>Ketchup</option>
    <option>Relish</option>
</select>
    </div>

 <div class="col-sm-4">
          <select class="selectpicker">
    <option>Yıl Seçiniz</option>
    <option>Ketchup</option>
    <option>Relish</option>
</select>
    </div>

 <div class="col-sm-4">
          <select class="selectpicker">
    <option>Motor Seçiniz</option>
    <option>Ketchup</option>
    <option>Relish</option>
</select>
    </div>

 <div class="col-sm-4">
          <select class="selectpicker">
    <option>Ecu Seçiniz</option>
    <option>Ketchup</option>
    <option>Relish</option>
</select>
    </div>

 <div class="col-sm-4">
         <button style="width: 100%;    height: 49px;" class="btn btn-primary mb-2">Gönder</button>
    </div>


 <div  style="margin-top:20px;"  class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">

								<div class="row">

                                    <div   class="col-md-12 col-sm-12 col-12 glance-col">
                                         <div style="    border: none;" class="card component-card_1">
            <div class="card-body">
                <img style="width: 70px;    margin-bottom: 13px;" src="\assets/img/bmw.png">
                <h5 style="    margin-bottom: 5px;"  class="card-title">BMW 5 Series - 535d - 3.0 - 285-BHP - 213-KW</h5>
                <p class="card-text">Estimated 31% more power and 29% more torque</p>
            </div>
        </div>
                                    </div>

                                    <div style=" background: #F6F6F6; color: #333333;" class="col-md-3 col-sm-4 col-3 glance-col duzeltmusti">
                                        <span style="    border: none; " class="glance">
                                             <span class="label">Parametreler </span>
                                        </span>
                                    </div>
                                    <div style=" background: #E8E8E8; color: #fff; " class="col-md-3 col-sm-4 col-3 glance-col duzeltmusti">
                                        <span style="    border: none; " class="glance">
                                             <span style="    color: #232323;" class="label">Orjinal </span>
                                        </span>
                                    </div>
                                    <div style=" background: #4361ee; color: #fff; " class="col-md-3 col-sm-4 col-3 glance-col duzeltmusti">
                                        <span style="    border: none;    " class="glance">
                                             <span class="label">Stage 1</span>
                                        </span>
                                    </div>
                                    <div style=" background: #333333; color: #fff; " class="col-md-3 col-sm-4 col-3 glance-col duzeltmusti">
                                        <span style="    border: none;  " class="glance">
                                             <span class="label">Stage 2 </span>
                                        </span>
                                    </div>
                                    <div style="  background: #F6F6F6;    color: #333333; " class="col-md-3 col-sm-4 col-3 glance-col duzeltmusti">
                                        <span class="glance ayarla">
                                             <span class="value">Güç (Bhp)</span>
                                        </span>
                                    </div>
                                    <div style=" background: #E8E8E8;    color: #fff; " class="col-md-3 col-sm-4 col-3 glance-col duzeltmusti">
                                        <span style="margin-top: 22px;     text-align: center; color: #232323;" class="glance">
                                             <span style="font-size: 39px;">285 <small style="    font-size: 12px;"> bhp </small></span>
                                         </span>
                                    </div>
                                    <div style="   background: #4361ee;    color: #fff; " class="col-md-3 col-sm-4 col-3 glance-col duzeltmusti">
                                          <span style="margin-top: 22px;     text-align: center; color: #232323;" class="glance">
                                             <span style="color:#fff;  font-size: 39px;">340 <small style="  color:#fff;   font-size: 12px;"> bhp </small></span>
                                         </span>
                                    </div>
                                    <div style="   background: #333333;    color: #fff; " class="col-md-3 col-sm-4 col-3 glance-col duzeltmusti">
                                         <span style="margin-top: 22px;     text-align: center; color: #df3733;" class="glance">
                                             <span style="color:#fff; font-size: 39px;">374 <small style=" color:#fff;    font-size: 12px;"> bhp </small></span>
                                         </span>
                                    </div>
                                    <div style="     background: #F6F6F6;    color: #333333; " class="col-md-3 col-sm-4 col-3 glance-col duzeltmusti">
                                        <span class="glance ayarla">
                                             <span class="value">Tork (Nm)</span>
                                         </span>
                                    </div>
                                    <div style="     background: #E8E8E8;    color: #fff; " class="col-md-3 col-sm-4 col-3 glance-col duzeltmusti">
                                        <span style="margin-top: 22px;     text-align: center; color: #232323;" class="glance">
                                             <span style="font-size: 39px;">580 <small style="    font-size: 12px;"> Nm </small></span>
                                         </span>
                                    </div>
                                    <div style="     background: #4361ee;    color: #fff; " class="col-md-3 col-sm-4 col-3 glance-col duzeltmusti">
                                          <span style="margin-top: 22px;     text-align: center; color: #232323;" class="glance">
                                             <span class="kucuk" style="font-size: 39px;color:#fff; ">680 <small style="color:#fff; font-size: 12px;"> Nm </small></span>
                                         </span>
                                    </div>
                                    <div style="background: #333333; color: #fff; " class="col-md-3 col-sm-4 col-3 glance-col duzeltmusti">
										<span style="margin-top: 22px; text-align: center; color: #df3733;" class="glance">
                                             <span style="color:#fff;  font-size: 39px;">748 <small style="color:#fff; font-size: 12px;"> Nm </small></span>
                                         </span>
                                    </div>
                                    </div>
                                    </div>


 <div  style="margin-top:20px;"  class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                         <div class="widget widget-chart-one" style="    border: none;" >
                            <div class="widget-content">
                                <div id="revenueMonthly"></div>
                            </div>
                        </div>

 	</div>

 <div class="col-xl-12 col-md-12 col-sm-12 col-12">
     <h4>Motor Özellikleri</h4>  </div>

 <div class="col-xl-12 col-md-12 col-sm-12 col-12 ">
 <div class="row">

 	  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                      <div class="widget-content widget-content-area ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-4">

                                            <tbody>

                                                <tr>
                                                    <td>Yakıt türü</td>
                                                    <td style="font-weight:bold;">Dizel</td>
                                                   </tr>
                                                  <tr>
                                                    <td>Yöntem</td>
                                                    <td style="font-weight:bold;">Chiptuning</td>
                                                   </tr>
                                                  <tr>
                                                    <td>Tuning Tipi</td>
                                                    <td style="font-weight:bold;">Stage 1</td>
                                                   </tr>
                                                  <tr>
                                                    <td>Silindir İçeriği</td>
                                                    <td style="font-weight:bold;">2970 CC</td>
                                                   </tr>
                                                  <tr>
                                                    <td>Motor Ecu</td>
                                                    <td style="font-weight:bold;">Bosch EDC17CP54</td>
                                                   </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
								</div>
  	  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                      <div class="widget-content widget-content-area">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-4">

                                            <tbody>

                                                  <tr>
                                                    <td>Şanzıman ECU</td>
                                                    <td style="font-weight:bold;">ZF AL450</td>
                                                   </tr>
                                                <tr>
                                                    <td>Sıkıştırma Oranı</td>
                                                    <td style="font-weight:bold;">16,8 : 1</td>
                                                   </tr>
                                                  <tr>
                                                    <td>Bore X Stroke</td>
                                                    <td style="font-weight:bold;">83,0 X 91,4 mm</td>
                                                   </tr>
                                                  <tr>
                                                    <td>Motor Numarası</td>
                                                    <td style="font-weight:bold;">DDXB</td>
                                                   </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
								</div>
								</div>
								</div>

			<div id="card_4" class="col-lg-12 layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Okuma Araçları</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">
                                     <div class="row">

<div class="col-xl-3 col-md-3 col-sm-3 col-12 mb-2">
<ul class="list-group list-group-media">
    <li class="list-group-item list-group-item-action">
        <div class="media">
            <div class="mr-3">
                <img alt="avatar" src="\assets/img/aliens.jpg" class="img-fluid">
            </div>
            <div class="media-body">
                <h6 class="tx-inverse">Alientech</h6>
                <p class="mg-b-0">Kess</p>
            </div>
        </div>
    </li>
</ul>
</div>


<div class="col-xl-3 col-md-3 col-sm-3 col-12 mb-2">
<ul class="list-group list-group-media">
    <li class="list-group-item list-group-item-action">
        <div class="media">
            <div class="mr-3">
                <img alt="avatar" src="\assets/img/auto.jpg" class="img-fluid">
            </div>
            <div class="media-body">
                <h6 class="tx-inverse">Autotuner</h6>
                <p class="mg-b-0">Bench</p>
            </div>
        </div>
    </li>
</ul>
 </div>


<div class="col-xl-3 col-md-3 col-sm-3 col-12 mb-2">
<ul class="list-group list-group-media">
    <li class="list-group-item list-group-item-action">
        <div class="media">
            <div class="mr-3">
                <img alt="avatar" src="\assets/img/cmd.jpg" class="img-fluid">
            </div>
            <div class="media-body">
                <h6 class="tx-inverse">CMD</h6>
                <p class="mg-b-0">CMD Bench</p>
            </div>
        </div>
    </li>
</ul>
</div>

<div class="col-xl-3 col-md-3 col-sm-3 col-12 mb-2">
<ul class="list-group list-group-media">
    <li class="list-group-item list-group-item-action">
        <div class="media">
            <div class="mr-3">
                <img alt="avatar" src="\assets/img/magic.jpg" class="img-fluid">
            </div>
            <div class="media-body">
                <h6 class="tx-inverse">Magic Motorsport</h6>
                <p class="mg-b-0">Bench / Flex</p>
            </div>
        </div>
    </li>
</ul>
</div>

                                    </div>
                                </div>
                            </div>
                        </div>

    </div>
    </div>
    <!--
	<div class="tab-pane fade" id="underline-profile" role="tabpanel" aria-labelledby="underline-profile-tab">
       <div class="media">
            <img class="mr-3" src="\assets/img/profile-32.jpeg" alt="Generic placeholder image">
            <div class="media-body">
                Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
            </div>
          </div>
    </div>
   -->

</div></div>
                        </div>
                    </div>

					 <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-table-three">
                            <div class="widget-heading">
                                <h5 class="">Son Düzenlenen Araçlar</h5>
                            </div>
                            <div class="widget-content">
                                <div class="table-responsive">
                                    <table class="table table-scroll">
                                        <tbody>
                                            <tr>
                                                <td><div class="td-content product-name"><img src="\assets\img\bmw.png" alt="product"><div class="align-self-center"><p class="prd-name">BMW 1 Series Coupe - 12d - 2.0 177-BHP 132-KW</p><p class="prd-category text-primary">120d - 2.0 - 177-BHP 132-KW</p> <span class="badge badge-primary mr-2 mt-1"> Stage 1 </span><span class="badge badge-dark mr-2 mt-1"> +33 Bhp </span><span class="badge badge-dark mr-2 mt-1"> +70 Nm </span> </div></div></td>
                                              </tr>
                                           <tr>
                                                <td><div class="td-content product-name"><img src="\assets\img\vw.png" alt="product"><div class="align-self-center"><p class="prd-name"> Volkswagen Golf- 12d - 2.0 177-BHP 132-KW</p><p class="prd-category text-primary">120d - 2.0 - 177-BHP 132-KW</p> <span class="badge badge-primary mr-2 mt-1"> Stage 1 </span><span class="badge badge-dark mr-2 mt-1"> +33 Bhp </span><span class="badge badge-dark mr-2 mt-1"> +70 Nm </span> </div></div></td>
                                              </tr>
                                           <tr>
                                                <td><div class="td-content product-name"><img src="\assets\img\bmw.png" alt="product"><div class="align-self-center"><p class="prd-name">BMW 1 Series Coupe - 12d - 2.0 177-BHP 132-KW</p><p class="prd-category text-primary">120d - 2.0 - 177-BHP 132-KW</p> <span class="badge badge-primary mr-2 mt-1"> Stage 1 </span><span class="badge badge-dark mr-2 mt-1"> +33 Bhp </span><span class="badge badge-dark mr-2 mt-1"> +70 Nm </span> </div></div></td>
                                              </tr>
                                           <tr>
                                                <td><div class="td-content product-name"><img src="\assets\img\bmw.png" alt="product"><div class="align-self-center"><p class="prd-name">BMW 1 Series Coupe - 12d - 2.0 177-BHP 132-KW</p><p class="prd-category text-primary">120d - 2.0 - 177-BHP 132-KW</p> <span class="badge badge-primary mr-2 mt-1"> Stage 1 </span><span class="badge badge-dark mr-2 mt-1"> +33 Bhp </span><span class="badge badge-dark mr-2 mt-1"> +70 Nm </span> </div></div></td>
                                              </tr>
                                           <tr>
                                                <td><div class="td-content product-name"><img src="\assets\img\bmw.png" alt="product"><div class="align-self-center"><p class="prd-name">BMW 1 Series Coupe - 12d - 2.0 177-BHP 132-KW</p><p class="prd-category text-primary">120d - 2.0 - 177-BHP 132-KW</p> <span class="badge badge-primary mr-2 mt-1"> Stage 1 </span><span class="badge badge-dark mr-2 mt-1"> +33 Bhp </span><span class="badge badge-dark mr-2 mt-1"> +70 Nm </span> </div></div></td>
                                              </tr>
                                          </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-table-three">
                            <div class="widget-heading">
                                <h5 class="">Son Eklenen Araçlar</h5>
                            </div>
                            <div class="widget-content">
                                <div class="table-responsive">
                                    <table class="table table-scroll">
                                        <tbody>
                                            <tr>
                                                <td><div class="td-content product-name"><img src="\assets\img\vw.png" alt="product"><div class="align-self-center"><p class="prd-name">BMW 1 Series Coupe - 12d - 2.0 177-BHP 132-KW</p><p class="prd-category text-primary">120d - 2.0 - 177-BHP 132-KW</p> <span class="badge badge-primary mr-2 mt-1"> 2021-06-09 - 10:15:05 </span> </div></div></td>
                                              </tr>
                                           <tr>
                                                <td><div class="td-content product-name"><img src="\assets\img\bmw.png" alt="product"><div class="align-self-center"><p class="prd-name"> Volkswagen Golf- 12d - 2.0 177-BHP 132-KW</p><p class="prd-category text-primary">120d - 2.0 - 177-BHP 132-KW</p> <span class="badge badge-primary mr-2 mt-1"> 2021-06-09 - 10:15:05 </span></div></div></td>
                                              </tr>
                                           <tr>
                                                <td><div class="td-content product-name"><img src="\assets\img\bmw.png" alt="product"><div class="align-self-center"><p class="prd-name">BMW 1 Series Coupe - 12d - 2.0 177-BHP 132-KW</p><p class="prd-category text-primary">120d - 2.0 - 177-BHP 132-KW</p> <span class="badge badge-primary mr-2 mt-1"> Stage 1 </span><span class="badge badge-dark mr-2 mt-1"> +33 Bhp </span><span class="badge badge-dark mr-2 mt-1"> +70 Nm </span> </div></div></td>
                                              </tr>
                                           <tr>
                                                <td><div class="td-content product-name"><img src="\assets\img\bmw.png" alt="product"><div class="align-self-center"><p class="prd-name">BMW 1 Series Coupe - 12d - 2.0 177-BHP 132-KW</p><p class="prd-category text-primary">120d - 2.0 - 177-BHP 132-KW</p> <span class="badge badge-primary mr-2 mt-1"> Stage 1 </span><span class="badge badge-dark mr-2 mt-1"> +33 Bhp </span><span class="badge badge-dark mr-2 mt-1"> +70 Nm </span> </div></div></td>
                                              </tr>
                                           <tr>
                                                <td><div class="td-content product-name"><img src="\assets\img\bmw.png" alt="product"><div class="align-self-center"><p class="prd-name">BMW 1 Series Coupe - 12d - 2.0 177-BHP 132-KW</p><p class="prd-category text-primary">120d - 2.0 - 177-BHP 132-KW</p> <span class="badge badge-primary mr-2 mt-1"> Stage 1 </span><span class="badge badge-dark mr-2 mt-1"> +33 Bhp </span><span class="badge badge-dark mr-2 mt-1"> +70 Nm </span> </div></div></td>
                                              </tr>
                                          </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		 <?php include("alt.php")?>
		 </div>
        <!--  END CONTENT AREA  -->
    </div>
     <?php include("js.php")?>
	     <script src="\plugins\bootstrap-select\bootstrap-select.min.js"></script>
  <style>
		 .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
    width: 100%;
}
.widget-table-three .table > tbody > tr > td:last-child .td-content {
    padding: 0;
    width: 100%;
    margin: 0 auto;
}
</style>
 <script src="\assets/js/alt.js"></script>
 </body>
</html>
