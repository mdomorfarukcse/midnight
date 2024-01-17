<?php
use Pemm\Model\Support;
use Pemm\Model\CustomerVehicle;

$newSupports = (new Support())->findBy(['filter' => ['type' => 'customer', 'administrator_read' => 0]]);
$supportCount = 0;
foreach ($newSupports as $_support) {
	$supportCount++;
}

$newCustomerVehicles = (new CustomerVehicle())->findBy(['filter' => ['status' => 'pending', 'deleted' => 0]]);
$customerVehicleCount = 0;
foreach ($newCustomerVehicles as $_customerVehicle) {
	$customerVehicleCount++;
}
?>

<div class="overlay"></div>
<div class="search-overlay"></div>

<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">

<nav id="sidebar">

	<ul class="navbar-nav theme-brand flex-row  text-center">
 			 <li class="nav-item theme-text">
 					 <a href="/admin" class="nav-link">   <img style="height: 38px;" src="<?=$setting->getsiteUrl();?>/assets/img/<?=$setting->getsiteLogo();?>" class="navbar-logo" alt="logo"> </a>
 			 </li>
 			 <li class="nav-item toggle-sidebar">
 					 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather sidebarCollapse feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
 			 </li>
 	 </ul>

	 <div class="shadow-bottom"></div>
	 <div class="profile-info"><a href="/admin/user/detail/<?= $user->getId() ?>">
											 <div class="user-info">
													 <div style="margin-right: 13px;" class="profile-img">
														<img src="<?= $user->getAvatar(true) ?>" alt="avatar">
													 </div>
													 <div class="profile-content">
															 <h6 class=""><?= $language::translate('Welcome') ?></h6>
															 <p class=""><?= $user->getFirstName() ?> <?= $user->getLastName() ?></p>
													 </div>
											 </div>
								 </a>  </div>

	 <ul class="list-unstyled menu-categories ps ps--active-y" id="accordionExample">

<li class="menu">
<a href="/admin" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-home fontkismi"></i>
<span><?= $language::translate('Dashboard') ?></span>
</div>
</a>
</li>

<li class="menu menu-heading">
	<div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span><?= $language::translate('Customers') ?></span></div>
	</li>

<li class="menu">
<a href="/admin/customer/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
 <i class="ti-user fontkismi"></i>
<span><?= $language::translate('Customers') ?></span>
</div>
</a>
</li>

         <li class="menu">
             <a href="/admin/customer-group/list" aria-expanded="false" class="dropdown-toggle">
                 <div class="icon-container">
                     <i class="ti-bookmark fontkismi"></i>
                     <span><?= $language::translate('Customer Group') ?></span>
                 </div>
             </a>
         </li>

<li class="menu">
<a href="/admin/customer/vehicle/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-car fontkismi"></i>
<span><?= $language::translate('File Service') ?>
<?php
if ($customerVehicleCount) {
	?>
 <span style="margin-left: 6px;padding: 2px 6px 2px 6px;    vertical-align: middle; text-transform: capitalize;" class="badge badge-success"><?php echo $customerVehicleCount ?></span>
<?php } ?>
</span>
</div>
</a>
</li>

<li class="menu">
<a href="/admin/support" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-help-alt fontkismi"></i>
<span><?= $language::translate('Supports') ?>
<?php
if ($supportCount) {
	?>
 <span style="margin-left: 6px;padding: 2px 6px 2px 6px;    vertical-align: middle; text-transform: capitalize;" class="badge badge-danger"><?php echo $supportCount ?></span>
<?php } ?>
 </span>
</div>
</a>
</li>

<li class="menu">
<a href="/admin/customer/order/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-money fontkismi"></i>
<span><?= $language::translate('Purchases') ?></span>
</div>
</a>
</li>
<li class="menu">
<a href="/admin/customer/invoice/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-receipt fontkismi"></i>
<span><?= $language::translate('Invoices') ?></span>
</div>
</a>
</li>



<li class="menu menu-heading">
											<div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span><?= $language::translate('Products') ?></span></div>
</li>

<li class="menu">
								 <a href="#astarter-kit" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
										<div class="">
											<div class="icon-container">
											<i class="ti-shopping-cart fontkismi"></i>
											<span><?= $language::translate('Products') ?></span>
											</div>
										</div>
										<div>
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
										</div>
								</a>
								<ul class="collapse submenu list-unstyled" id="astarter-kit" data-parent="#accordionExample">
										<li>
												<a href="/admin/product/list"> <?= $language::translate('Products') ?> </a>
										</li>
									  <?php if($setting->getEvcStatus()) { ?>
												<li>
												<a href="/admin/product-evc/list">EVC <?= $language::translate('Products') ?> </a>
										</li>
										<?php } ?>
								</ul>
							</li>




<li class="menu menu-heading">
 <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>
	 <span><?= $language::translate('Settings') ?></span></div>
 </li>


<li class="menu">
<a href="/admin/tuning/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-stats-up fontkismi"></i>
<span><?= $language::translate('Tuning') ?></span>
</div>
</a>
</li>
<li class="menu">
<a href="/admin/additional-option/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-layers-alt fontkismi"></i>
<span><?= $language::translate('Additional Option') ?></span>
</div>
</a>
</li>
<li class="menu">
<a href="/admin/tuning/option/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-layers-alt fontkismi"></i>
<span><?= $language::translate('Tuning Options') ?></span>
</div>
</a>
</li>
<li class="menu">
<a href="/admin/read-methods/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-control-forward fontkismi"></i>
<span><?= $language::translate('Read Methods') ?></span>
</div>
</a>
</li>


<li class="menu menu-heading">
	<div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span><?= $language::translate('Vehicle') ?></span></div>
	</li>


<li class="menu">
<a href="/admin/vehicle/category/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-layout fontkismi"></i>
<span><?= $language::translate('Categories') ?></span>
</div>
</a>
</li>
<li class="menu">
<a href="/admin/vehicle/brand/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-star fontkismi"></i>
<span><?= $language::translate('Brands') ?></span>
</div>
</a>
</li>
<li class="menu">
<a href="/admin/vehicle/model/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-tag fontkismi"></i>
<span><?= $language::translate('Models') ?></span>
</div>
</a>
</li>
<li class="menu">
<a href="/admin/vehicle/years/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-medall fontkismi"></i>
<span><?= $language::translate('Years') ?></span>
</div>
</a>
</li>
         <li class="menu">
             <a href="/admin/vehicle/engine/list" aria-expanded="false" class="dropdown-toggle">
                 <div class="icon-container">
                     <i class="ti-medall fontkismi"></i>
                     <span><?= $language::translate('Engines') ?></span>
                 </div>
             </a>
         </li>

<li class="menu">
<a href="/admin/vehicle/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-car fontkismi"></i>
<span><?= $language::translate('Vehicles') ?></span>
</div>
</a>
</li>
 		<li class="menu menu-heading">
																			<div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span><?= $language::translate('Other') ?></span></div>
								</li>

								<li class="menu">
																 <a href="#starter-kit" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
																	 <div class="icon-container">
																	 <i class="ti-shortcode fontkismi"></i>																				<span><?= $language::translate('Codes') ?></span>
																		</div>
																		<div>
																				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
																		</div>
																</a>
																<ul class="collapse submenu list-unstyled" id="starter-kit" data-parent="#accordionExample">
																		<li>
																				<a href="/admin/bosch-codes/list"> <?= $language::translate('Bosch Codes') ?> </a>
																		</li>
																		<li>
																				<a href="/admin/p-codes/list"><?= $language::translate('P Codes') ?> </a>
																		</li>

																</ul>
								</li>
<li class="menu">
<a href="/admin/currency/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-money fontkismi"></i>
<span><?= $language::translate('Currency') ?></span>
</div>
</a>
</li>
<li class="menu">
<a href="/admin/currency/exchange-rate/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-control-shuffle fontkismi"></i>
<span><?= $language::translate('Exchange Rates') ?></span>
</div>
</a>
</li>
<li class="menu">
<a href="/admin/country/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-pin2 fontkismi"></i>
<span><?= $language::translate('Countries') ?></span>
</div>
</a>
</li>



<li class="menu menu-heading">
											<div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span><?= $language::translate('Settings') ?></span></div>
</li>
<li class="menu">
<a href="/admin/user/list" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-user fontkismi"></i>
<span><?= $language::translate('Administrator') ?></span>
</div>
</a>
</li>
<li class="menu">
<a href="/admin/setting/general" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-settings fontkismi"></i>
<span><?= $language::translate('General Settings') ?></span>
</div>
</a>
</li>

<li class="menu">
<a href="/admin/setting/logo" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-image fontkismi"></i>
<span><?= $language::translate('Logo Settings') ?></span>
</div>
</a>
</li>

<li class="menu">
<a href="/admin/setting/payment" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-shopping-cart fontkismi"></i>
<span><?= $language::translate('Payment Method') ?></span>
</div>
</a>
</li>

<li class="menu">
<a href="/admin/setting/evc" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-medall-alt fontkismi"></i>
<span><?= $language::translate('Evc Settings') ?></span>
</div>
</a>
</li>

<li class="menu">
<a href="/admin/setting/sms" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-mobile fontkismi"></i>
<span><?= $language::translate('Sms Settings') ?></span>
</div>
</a>
</li>
<li class="menu">
<a href="/admin/setting/google" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-google fontkismi"></i>
<span><?= $language::translate('Google reCaptcha') ?></span>
</div>
</a>
</li>

<li class="menu">
<a href="/admin/setting/working" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-time fontkismi"></i>
<span><?= $language::translate('Working Hours') ?></span>
</div>
</a>
</li>

<li class="menu">
<a href="/admin/setting/mail" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-email fontkismi"></i>
<span><?= $language::translate('Mail Settings') ?></span>
</div>
</a>
</li>

<li class="menu">
<a href="/admin/setting/policies" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-agenda fontkismi"></i>
<span><?= $language::translate('Policies') ?></span>
</div>
</a>
</li>



<li class="menu menu-heading">
											<div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span><?= $language::translate('Logout') ?></span></div>
</li>

<li class="menu">
<a href="/admin/logout" aria-expanded="false" class="dropdown-toggle">
<div class="icon-container">
<i class="ti-close fontkismi"></i>
<span><?= $language::translate('Logout') ?></span>
</div>
</a>
</li>
</ul>
</nav>
</div>
