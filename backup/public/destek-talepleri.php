<?php

use Pemm\Core\Container;
use Pemm\Model\Customer;
use Pemm\Model\Support;
use Pemm\Model\Setting;
use Symfony\Component\HttpFoundation\Request;

global $container;
$setting = (new Setting())->find(1);

/** @var Request $request */
$request = $container->get('request');

$type = $request->query->has('type') ? $request->query->get('type') : 'inbox';
$read = $request->query->getInt('read');
$page = $request->query->getInt('page');

if ($page == 0) {
    $page = 1;
}

/* @var Customer $customer */
$customer = $container->get('customer');

$language = $container->get('language');

$supportModel = Support::counter($customer->getId(), $type);

$filter['customer_id'] = $customer->getId();

switch ($type) {
    case 'inbox':
        $filter['customer_read'] = $read;
        $filter['type'] = 'admin';
        break;
    case 'open':
        $filter['first_question'] = 1;
        $filter['status'] = ['pending', 'answered'];
        break;
    case 'closed':
        $filter['first_question'] = 1;
        $filter['status'] = 'closed';
        break;
    default:
        break;
}

$filterModel = new Support();

$supports = $filterModel->findBy([
    'filter' => $filter,
    'order' => ['field' => 'created_at', 'sort' => 'DESC'],
    'pagination' => ['limit' => 4, 'page' => $page]
]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('Supports') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
	<link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="\plugins/font-icons/fontawesome/css/regular.css">
<link rel="stylesheet" href="\plugins/font-icons/fontawesome/css/fontawesome.css">
<link rel="stylesheet" type="text/css" href="\assets/css/destek.css">

  </head>
<body>
   	<?php include("ust2.php")?>

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
	<?php include("ust.php")?>
         <div id="content" class="main-content">
            <div class="layout-px-spacing">
              <div class="chat-section layout-top-spacing">
                    <div class="row">
                      <!-- BEGIN NAV TICKET -->
                  		<div class="col-md-3">
                  			<div class="grid support">
                  				<div class="grid-body">
                  					<h2 style="text-align:center;">  <img style="    width: 180px; " src="<?=$setting->getsiteUrl();?>/assets/img/<?=$setting->getLogo2();?>"></h2>
                  					<hr>
                  					<ul>
                  						<li class="<?= empty($type) ? 'active' : ''?>"><a href="?<?= http_build_query(['type' => 'inbox']) ?>"><?= $language::translate('Inbox') ?><span class="float-right"><?= $supportModel->getInboxSupportCount() ?></span></a></li>
                  						<li class="<?= $type == 'open' ? 'active' : '' ?>"><a href="?<?= http_build_query(['type' => 'open']) ?>"><?= $language::translate('Open Support Requests') ?>   <span class="float-right"><?= $supportModel->getOpenSupportCount() ?></span></a></li>
                  						<li class="<?= $type == 'closed' ? 'active' : '' ?>"><a href="?<?= http_build_query(['type' => 'closed']) ?>"><?= $language::translate('Closed Support Requests') ?>  <span class="float-right"><?= $supportModel->getClosedSupportCount() ?></span></a></li>
                  					</ul>
                  					<hr>
                  				</div>
                  			</div>
                  		</div>
                   		<div class="col-md-9">
                  			<div class="grid support-content">
                  				 <div class="grid-body">
                                     <div class="row">
                                         <h2 class="col-6"><?= $language::translate('Support Requests') ?></h2>
                                         <div class="col-6">
                                             <button type="button" class="btn btn-primary float-right"
                                                     data-toggle="modal"
                                                     data-target="#composeMailModal"><?= $language::translate(
                                                     'Create support request'
                                                 ) ?></button>
                                         </div>
                                     </div>
                                     <hr>
                                     <?php
                                        if ($type == 'inbox') {?>
                                            <div class="btn-group">
                                                <a href="?<?= http_build_query(['type' => $type, 'read' => 1]) ?>"
                                                   type="button"
                                                   class="btn <?= $read == 1 ? 'btn-primary active' : 'btn-default' ?>"><?= $supportModel->getInboxReadSupportMessageCount() ?> <?= $language::translate('Read Messages') ?></a>
                                                <a style="    color: #0e1726;" href="?<?= http_build_query(['type' => $type, 'read' => 0]) ?>"
                                                   type="button"
                                                   class="btn <?= $read == 0 ? 'btn-primary active' : 'btn-default' ?>"><?= $supportModel->getInboxUnReadSupportMessageCount() ?> <?= $language::translate('Unread Messages') ?></a>
                                            </div>
                                        <?php }
                                     ?>
                  					<div class="padding"></div>

                  					<div class="row">

                   						<div class="col-md-12">
                  							<ul class="list-group fa-padding">
                                  <?php

                                  if (!empty($supports)) {
                                    /* @var Support $support */
                                    foreach ($supports as $support) {?>
  	                                <li style="    background: <?=$support->getCustomerRead() ==  1 ?' #00ff6626' : '#ff000026'?>;" class="list-group-item" >
                  									<div class="media">
                  										<img style="width: 42px;" class="rounded-circle" src="<?= $support->getAvatar() ?>" />
                  										<div style="margin-left: 13px;" class="media-body">
                  										<a href="/panel/ticket?ticket_id=<?= $support->getId()  ?>">
                                        <strong><?= $support->getSubject() ?></strong>
                                        <?php if($support->isOpen() && $type != 'inbox') { ?>
                                            <?php
                                                if ($support->getLastMessage()->getStatus() == 'pending') {?>
                                                    <span class="badge badge-warning"><?= $language::translate(
                                                            $support->getLastMessage()->getStatus()
                                                        ) ?></span>
                                                <?php } else { ?>
                                                    <span class="badge badge-success"><?= $language::translate(
                                                            $support->getLastMessage()->getStatus()
                                                        ) ?></span>
                                                <?php } ?>
                                            <?php
                                            if ($support->isAdministratorRead()) { ?>
                                                <span class="badge badge-success"><?= $language::translate(
                                                        'Administrator Read'
                                                    ) ?></span>
                                            <?php
                                            } else { ?>
                                                <span class="badge badge-light"><?= $language::translate(
                                                        'Administrator Unread'
                                                    ) ?></span>
                                            <?php
                                            } ?>
                                        <?php } ?>
                                        <span class="number float-right"># <?= $support->getId()  ?></span>
                                                            <?php
                                                            if ($type == 'inbox') { ?>
                                                                <p class="info"><?= $support->getText() ?> </p>
                                                            <?php
                                                            } else { ?>
                                                                <p class="info"><?= $support->getLastMessage()->getText(
                                                                    ) ?> </p>
                                                            <?php
                                                            } ?>
                                                            <p class="info">

                                                            <?php if($support->getVehicle()  != ""):?>
                                                              <i class="ti-car"></i> <?= $support->getVehicle() ?> <br>
                                                           <?php endif;?>
                                                            <i class="ti-alarm-clock"></i> <?= $support->getSupportDateOrTime() ?> </p>
                                        </a>
                                    	</div>
                  									</div>
                  								</li>
                                <?php }
                                }
                                ?>
                                            </ul>
                  <?php
                      if ($filterModel->queryTotalPage > 1) { ?>
                          <nav style="margin-top: 33px;" aria-label="Page navigation example">
                              <ul class="pagination justify-content-center">
                                  <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                                      <a class="page-link" href="?<?= http_build_query(['type' => $type, 'read' => $read, 'page' => 1]) ?>"
                                         tabindex="-1"><?= $language::translate(
                                              'Previous'
                                          ) ?></a>
                                  </li>
                                  <?php
                                      for ($i = 1; $i <= $filterModel->queryTotalPage; $i++) {?>
                                          <li class="page-item <?= $page == $i ? 'active' : '' ?>"><a class="page-link" href="?<?= http_build_query(['type' => $type, 'read' => $read, 'page' => $i]) ?>"><?= $i ?></a></li>
                                      <?php }
                                  ?>
                                  <li class="page-item <?= $page == $filterModel->queryTotalPage ? 'disabled' : '' ?>">
                                      <a class="page-link" href="?<?= http_build_query(['type' => $type, 'read' => $read, 'page' => $filterModel->queryTotalPage]) ?>"><?= $language::translate('Next') ?></a>
                                  </li>
                              </ul>
                          </nav>
                      <?php }
                  ?>

                  							<!-- BEGIN DETAIL TICKET -->
                  							<div class="modal fade" id="issue" tabindex="-1" role="dialog" aria-labelledby="issue" aria-hidden="true">
                  								<div class="modal-wrapper">
                  									<div class="modal-dialog">
                  										<div class="modal-content">
                  											<div class="modal-header bg-blue">
                  												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  												<h4 class="modal-title"><i class="fa fa-cog"></i> Add drag and drop config import closes</h4>
                  											</div>
                  											<form action="#" method="post">
                  												<div class="modal-body">

                                        		<div class="row">
                  														<div class="col-md-2">
                  															<img src="assets/img/user/avatar01.png" class="img-circle" alt="" width="50">
                  														</div>
                  														<div class="col-md-10">
                  															<p>Issue <strong>#13698</strong> opened by <a href="#">jqilliams</a> 5 hours ago</p>
                  															<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                  															<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                  														</div>
                  													</div>


                  													<div class="row support-content-comment">
                  														<div class="col-md-2">
                  															<img src="assets/img/user/avatar02.png" class="img-circle" alt="" width="50">
                  														</div>
                  														<div class="col-md-10">
                  															<p>Posted by <a href="#">ehernandez</a> on 16/06/2014 at 14:12</p>
                  															<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                  															<a href="#"><span class="fa fa-reply"></span> &nbsp;Post a reply</a>
                  														</div>
                  													</div>
                  												</div>
                  												<div class="modal-footer">
                  													<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                  												</div>
                  											</form>
                  										</div>
                  									</div>
                  								</div>
                  							</div>
                  							<!-- END DETAIL TICKET -->
                  						</div>
                  						<!-- END TICKET CONTENT -->
                  					</div>
                  				</div>
                  			</div>
                  		</div>

                      <div class="modal fade" id="composeMailModal" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                  <div class="modal-body">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel"><?= $language::translate('Create support request') ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label> <?= $language::translate('Support subject') ?> </label>
                                                <input id="support-title" type="text" class="form-control" name="subject" placeholder="<?= $language::translate('Support subject') ?>" required="">
                                            </div>
                    <div class="form-group">
                                                <label> <?= $language::translate('Select Vehicle') ?> </label>
                                                <select name="vehicle" class="form-control">
                                                <option value=""><?= $language::translate('-- None --') ?></option>
                                                <?php
                                                use Pemm\Model\CustomerVehicle;
                                                $newCustomerVehicles = (new CustomerVehicle())->findBy(['filter' => ['customer_id' => $customer->getId() ]]);
                                                foreach ($newCustomerVehicles as $_customerVehicle) {
                                                ?>
                                                <option value="<?= ($_customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $_customerVehicle->getWMVdata('vehicle_full_name') : $_customerVehicle->vehicle->getFullName())?>"><?= ($_customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $_customerVehicle->getWMVdata('vehicle_full_name') : $_customerVehicle->vehicle->getFullName())?></option>
                                                <?php
                                                }
                                                ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label> <?= $language::translate('Message') ?> </label>
                                                <textarea id="support-text" placeholder="<?= $language::translate('Message') ?>" class="form-control" name="text" required></textarea>
                                            </div>
                                            <div style="margin-bottom:15px;" class="form-group">
                                            <div class="d-flex">
                                                <input type="file" class="form-control-file" name="file">
                                            </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button  type="button" class="btn btn-secondary" data-dismiss="modal"><?= $language::translate('Cancel') ?></button>
                                            <button  onclick="this.disabled=true;  this.value='Gönderiliyor...';  this.form.submit();"   type="submit" class="btn btn-primary"><?= $language::translate('Create') ?></button>
                                        </div>
                                    </form>
                                  </div>

                              </div>
                          </div>
                      </div>


                    </div>
                </div>
				</div>
			  <?php include("alt.php")?>
    </div>
     <?php include("js.php")?>
<style media="screen">

</style>

    <style media="screen">

    </style>
 	</body>
</html>
