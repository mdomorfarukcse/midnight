<?php

use Pemm\Core\Container;
use Pemm\Core\Language;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Currency;
use Pemm\Model\User;
use Pemm\Model\Support;
use Pemm\Model\Setting;
use Pemm\Model\CustomerVehicle;

global $container;
$setting = (new Setting())->find(1);

/* @var User $user */
$user = $container->get('user');

/* @var Session $session */
$session = $container->get('session');

/* @var Language $language */
$language = $container->get('language');

$newSupports = (new Support())->findBy(['filter' => ['type' => 'customer', 'administrator_read' => 0]]);


$newCustomerVehicles = (new CustomerVehicle())->findBy(['filter' => ['status' => 'pending', 'deleted' => 0]]);
$customerVehicleCount = 0;
foreach ($newCustomerVehicles as $_customerVehicle) {
	$customerVehicleCount++;
}
 
?>
     <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">
          <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>

          <ul class="navbar-item flex-row">
              <li class="nav-item align-self-center page-heading">
                  <div class="page-header">
                      <div class="page-title">
                          <h3><?= $language::translate('System Time') ?>: </h3>  <?php $date = new DateTime('now');
       echo $date->format("H:i:s"); ?>
                      </div>
                  </div>
              </li>
          </ul>
          <ul class="navbar-item flex-row search-ul">

          </ul>

            <ul class="navbar-item flex-row ml-md-auto">

                <li class="nav-item dropdown language-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="language-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $language::LANGUAGES[$session->get('language')] ?>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="language-dropdown">
                        <?php
                        foreach ($language::LANGUAGES as $langCode => $flag) {?>
                            <a class="dropdown-item d-flex change" href="javascript:void(0);" onclick="selectLanguage('<?=$langCode?>')">
                                <span class="align-self-center"><?= $flag ?> <?= $language::translate($langCode) ?></span>
                            </a>
                        <?php }
                        ?>
                    </div>
                </li>

                <li class="nav-item dropdown message-dropdown">
                  <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="messageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg><span class="badge badge-primary"></span>
                  </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="messageDropdown">
                        <div class="">
                            <?php
                            if (!empty($newSupports)) {
                                /* @var Support $_support */
                                $limit = 0;
                                foreach ($newSupports as $_support) {
                                    $limit++;
                                    if($limit==6) break;
                                    ?>
                                    <a href="/admin/support" class="dropdown-item">
                                        <div class="">
                                            <div class="media">
                                                <div class="user-img">
                                                    <div class="avatar avatar-xl">
                                                        <span class="rounded-circle">
                                                            <img src="<?= $_support->customer->getAvatar(true) ?>" alt="">
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <div class="">
                                                        <h5 class="usr-name"><?= $_support->customer->getFullName() ?></h5>
                                                        <p class="msg-title"><?= substr($_support->getText(), 0, 30) ?>...</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php }
                            }
                            ?>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown notification-dropdown">
                  <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class="badge badge-success" style="width: fit-content;
    height: auto;
    padding: 2px;
    top: 5px;
    right: 0px;"><?=$customerVehicleCount?></span>
                  </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                        <div class="notification-scroll">








<?php
$say = 0;
foreach ($newCustomerVehicles as $_customerVehicle) {
	$say++;
	if ($say <= 5) {
	?>
<div class="dropdown-item">
                                <div class="media server-log">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-server"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6" y2="6"></line><line x1="6" y1="18" x2="6" y2="18"></line></svg>
                                    <div class="media-body">
									<a href="/admin/customer/vehicle/detail/<?=$_customerVehicle->getId()?>">
                                        <div class="data-info">
                                            <h6 class=""><?=(!empty($customerDetail = $_customerVehicle->getCustomer()) ? $customerDetail->getFullName() : '')?></h6>
                                            <p class=""><?=$_customerVehicle->vehicle->getFullName()?></p>
                                        </div>
									</a>
                                        <div class="icon-status">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
	<?php
	}
}

?>

						</div>
                    </div>
                </li>

                <li class="nav-item dropdown user-profile-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <img src="<?= $user->getAvatar(true) ?>" alt="avatar">
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="">
                               <div class="dropdown-item">
                                <a class="" href="/admin/logout"> <i class="ti-close digerust"></i> <?= $language::translate('Logout') ?> </a>
                            </div>
                        </div>
                    </div>
                </li>

            </ul>
        </header>
    </div>
