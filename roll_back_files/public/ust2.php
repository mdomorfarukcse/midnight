<?php

use Pemm\Core\Container;
use Pemm\Model\Customer;
use Pemm\Core\Language;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Currency;
use Pemm\Model\Support;
use Pemm\Model\Setting;
use Pemm\Model\CustomerVehicle;
$setting = (new Setting())->find(1);

global $container;

/* @var Customer $customer */
$customer = $container->get('customer');

/* @var Setting $setting */
$setting = $container->get('setting');

/* @var Session $session */
$session = $container->get('session');

/* @var Language $language */
$language = $container->get('language');

$newSupports = (new Support())->findBy(['filter' => ['customer_id' => $customer->getId(),'type' => 'admin']]);

$notifications = $customer->notifications();

$supportCounts = (new Support())->findBy(['filter' => ['customer_id' => $customer->getId(),'type' => 'admin', 'customer_read' => 0]]);
$ClientVehicleCounts = (new CustomerVehicle())->findBy(['filter' => ['customer_id' => $customer->getId(), 'status' => 'pending','deleted' => 0]]);
$newCustomerVehiclesPending = (new CustomerVehicle())->findBy(['filter' => ['status' => 'pending', 'deleted' => 0]]);
$newCustomerVehiclesProcessing = (new CustomerVehicle())->findBy(['filter' => ['status' => 'process', 'deleted' => 0]]);
$customerVehicleCount = $VehiclePendingCount = $VehicleProcessingCount = 0;
foreach ($newCustomerVehiclesPending as $_customerVehicle) {
	$VehiclePendingCount++;
}
foreach ($newCustomerVehiclesProcessing as $_customerVehicle) {
	$VehicleProcessingCount++;
} 

?>

<div class="header-container fixed-top">
      <header class="header navbar navbar-expand-sm">
          <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>
<style>
	.topbtn{
		padding: 5px 15px !important;
		font-size: 13px !important;
		margin-left: 20px;
		cursor: pointer;
    	background-color: transparent;
	}
</style>
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
          <ul class="navbar-item flex-row navbar-dropdown">

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
   "hu" => "Magyar",
   "gr" => "Ελληνικά",
   "cz" => "Česky",
   "he" => "עִבְרִית‎",
   "no" => "Norsk",
   "po" => "Polski"
					];
					?>
                <li class="nav-item dropdown language-dropdown">
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
                </li>

                <li class="nav-item dropdown language-dropdown">
                                  <?php $currencies = new Currency(); $currencies = $currencies->getActives(); ?>
                                  <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="currency-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <span style="font-size: 26px;margin-left: 4px;" ><?= $currencies[$session->get('currency')]->getSymbol() ?></span>
                                  </a>
                                  <div class="dropdown-menu position-absolute" aria-labelledby="currency-dropdown">
                                      <?php
                                      /* @var Currency $_currency */
                                      foreach ($currencies as $currencyCode => $_currency) {?>
                                          <a class="dropdown-item d-flex change" href="javascript:void(0);" onclick="selectCurrency('<?= $_currency->getCode() ?>')">
                                              <span class="align-self-center"><?= $_currency->getSymbol() ?> <?= $language::translate($_currency->getName()) ?></span>
                                          </a>
                                      <?php }
                                      ?>
                                  </div>
                              </li>

              <li style="margin-left: 13px;" class="nav-item dropdown message-dropdown">
                  <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="messageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg><span class="badge badge-primary"></span>
                  </a>
                  <div class="dropdown-menu p-0 position-absolute" aria-labelledby="messageDropdown">
                      <div class="">

                        <?php
                        if (!empty($newSupports)) {
                            /* @var Support $_support */
                            $say = 0;
                            foreach ($newSupports as $_support) {
                                $say++;
                                if ($say>5) continue;
                               ?>
                                <a href="/panel/support" class="dropdown-item">
                                    <div class="">
                                        <div class="media">
                                            <div class="user-img">
                                                <div class="avatar avatar-xl">
                                                    <span class="rounded-circle">
                                                        <img style="border-radius: 25px;" src="<?= $_support->customer->getAvatar(true) ?>" alt="">
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <div class="">
                                                    <h5 class="usr-name"><?= $_support->getSubject() ?></h5>
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
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class="badge badge-success"></span>
                  </a>
                  <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                      <div class="notification-scroll">

                        <?php
                        if (!empty($notifications)) {
                            /* @var Support $_support */
                            $say = 0;
                            foreach ($notifications as $notification) {
                                $say++;
                                if ($say>5) continue;
                               ?>

                                  <div class="dropdown-item">
                                      <div class="media server-log">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-server"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6" y2="6"></line><line x1="6" y1="18" x2="6" y2="18"></line></svg>
                                          <div class="media-body">
                                              <div class="data-info">
                                                  <h6 class=""><?=$language::translate($notification['title']) ?></h6>
                                                  <p class=""><?= $notification['datetime'] ?></p>
                                              </div>

                                              <button class="close icon-status" data-dismiss="alert" aria-label="Close" >
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                              </button>
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

              <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                  <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="<?= $customer->getAvatar(true) ?>" alt="avatar">
                  </a>
                  <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                      <div class="user-profile-section">
                          <div class="media mx-auto">
                              <img src="<?= $customer->getAvatar(true) ?>" class="img-fluid mr-2" alt="avatar">
                              <div class="media-body">
                                  <h5><?= $customer->getFirstName() ?> <?= $customer->getLastName() ?></h5>
                               </div>
                          </div>
                      </div>
                      <div class="dropdown-item">
                               <a class="" href="/panel/my-profile"> <i class="ti-user digerust"></i> <?= $language::translate('My Profile') ?> </a>
                           </div>
                              <div class="dropdown-item">
                               <a class="" href="/panel/credit-reports"> <i class="ti-archive digerust"></i> <?= $language::translate('Credit history') ?> </a>
                           </div>
                              <div class="dropdown-item">
                               <a class="" href="/panel/my-files"> <i class="ti-folder digerust"></i> <?= $language::translate('My Files') ?> </a>
                           </div>
                              <div class="dropdown-item">
                               <a class="" href="/panel/support"> <i class="ti-ticket digerust"></i> <?= $language::translate('Support') ?> </a>
                           </div>
                              <div class="dropdown-item">
                               <a href="" data-toggle="modal" data-target="#cikis"> <i class="ti-close digerust"></i> <?= $language::translate('Logout') ?> </a>
                           </div>
                  </div>
              </li>
          </ul>
      </header>
  </div>

  <div class="modal fade" id="cikis" tabindex="-1" role="dialog" aria-labelledby="cikisLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cikisLabel"><?= $language::translate('Logout') ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <?= $language::translate('Are you sure you want to sign out?') ?>
        </div>
        <div class="modal-footer">
          <a class="btn btn-primary" data-dismiss="modal" aria-label="Close"><?= $language::translate('Cancel') ?></a>
          <a href="/panel/logout" class="btn btn-danger"><?= $language::translate('Logout') ?></a>
        </div>
      </div>
    </div>
  </div>
