<?php

use Pemm\Core\Container;
use Pemm\Model\Customer;
use Pemm\Model\Support;
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

/* @var Customer $customer */
$customer = $container->get('customer');

$language = $container->get('language');

/** @var Support $support */
$support  = $container->get('support');
$today = new DateTime();
$yesterday = new DateTime('yesterday');

$type = $support->getStatus() == 'closed' ? 'closed' : 'open';

$supportModel = Support::counter($customer->getId(), $type);

?>
<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('Supports') ?> - <?= SITE_NAME ?> </title>
	<?php include("css.php")?>
	<link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
     <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link href="\assets\css\apps\mailing-chat.css" rel="stylesheet" type="text/css">
    <script src="\plugins/sweetalerts/promise-polyfill.js"></script>
<link href="\plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<link href="\plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
<link href="\plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="\plugins/editors/quill/quill.snow.css">
<link rel="stylesheet" type="text/css" href="\assets/css/forms/theme-checkbox-radio.css">
<link rel="stylesheet" type="text/css" href="\assets/css/destek.css">
<link href="\assets/css/apps/mailbox.css" rel="stylesheet" type="text/css" />


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
                        <div class="col-xl-12 col-lg-12 col-md-12">

                          <div class="page-content page-container" id="page-content">
                                   <div class="row d-flex justify-content-center">
                                    <div class="col-md-4">
                                      <div class="grid support">
                                        <div class="grid-body">
                                          <h2 style="text-align:center;">  <img style="    width: 180px; " src="<?=$setting->getsiteUrl();?>/assets/img/<?=$setting->getLogo2();?>"></h2>
                                          <hr>
                                          <ul>
                                              <li class="<?= empty($type) ? 'active' : '' ?>"><a href="/panel/support"><?= $language::translate('Inbox') ?><span class="float-right"><?= $supportModel->getInboxSupportCount() ?></span></a></li>
                                              <li class="<?= $type == 'open' ? 'active' : '' ?>"><a href="/panel/support?<?= http_build_query(['type' => 'open']) ?>"><?= $language::translate('Open Support Requests') ?>   <span class="float-right"><?= $supportModel->getOpenSupportCount() ?></span></a></li>
                                              <li class="<?= $type == 'closed' ? 'active' : '' ?>"><a href="/panel/support?<?= http_build_query(['type' => 'closed']) ?>"><?= $language::translate('Close') ?>  <span class="float-right"><?= $supportModel->getClosedSupportCount() ?></span></a></li>
                                          </ul>
                                          <hr>
                                        </div>
                                      </div>
                                    </div>
                                      <div class="col-md-8">
                                          <div class="card card-bordered">
                                              <div class="card-header">
                                                  <h4 class="card-title">
                                                      <strong><?= $language::translate('Supports') ?> <?= ' - ' . $support->getSubject() ?> <?= ' - ' . $support->getVehicleName() ?> </strong>
                                                  </h4>
                                                  <?php
                                                  if ($support->getStatus() != 'closed') { ?>
                                                      <a href="/panel/ticket/<?= $support->getId() ?>/close"
                                                         class="btn btn-xs btn-success"><?= $language::translate('Close Ticket') ?></a>
                                                  <?php } ?>
                                                  <a class="btn btn-xs btn-secondary"  data-toggle="modal" data-target="#composeMailModal"><?= $language::translate('Create support request') ?></a>
                                               </div>
                                              <div class="ps-container ps-theme-default ps-active-y" id="chat-content" style="overflow-y: scroll !important; height:400px !important;">

                                                  <?php
                                                  if (!empty($supports = $support->getSubMessages())) {
                                                      /* @var Support $_support */
                                                      foreach ($supports as $_support) {
                                                          $supportDate = DateTime::createFromFormat('Y-m-d H:i:s', $_support->getCreatedAt());
                                                          $date = $supportDate->format('d M Y');
                                                          if ($supportDate->format('Y-m-d') == $today->format('Y-m-d')) {
                                                              $date = 'Today';
                                                          } elseif ($supportDate->format('Y-m-d') == $yesterday->format('Y-m-d')){
                                                              $date = 'Yesterday';
                                                          }

                                                          if($_support->getType() == 'customer'){ ?>

                                                   <div class="media media-chat media-chat-reverse"><img class="avatar" src="<?= $_support->getAvatar() ?>" alt="...">
                                                      <div class="media-body">
                                                              <?php
                                                              if (!empty($_support->getText())) { ?>
                                                                  <p><?= $_support->getText() ?></p>
                                                              <?php } ?>
                                                          <?php if($_support->getFile() != null){ ?>
                                                              <br><a onclick="supportFileDownload('/panel/file/support/<?= $_support->getId() ?>/download')"
                                                                 class="btn btn-sq btn-success"><i
                                                                          class="ti-download"></i><br> <?= $_support->getFile(
                                                                  ) ?> </a><br>
                                                              <?php
                                                          } ?>
                                                           <p class="meta"><time style="    color: #9b9b9b;" datetime="2018"><?= $date ?>, <?= $supportDate->format('H:i') ?></time></p>
                                                      </div>
                                                  </div>

                                                  <?php
                                                  }else { ?>
                                                  <div class="media media-chat"> <img class="avatar" src="<?= $_support->getAvatar() ?>" alt="...">
                                                      <div class="media-body">

                                                          <p><?= $_support->getText() ?></p>

                                                          <?php if($_support->getFile() != null){ ?>
                                                              <br><a onclick="supportFileDownload('/panel/file/support/<?= $_support->getId() ?>/download')"
                                                                     class="btn btn-sq btn-success"><i
                                                                          class="ti-download"></i><br> <?= $_support->getFile(
                                                                  ) ?> </a><br>
                                                              <?php
                                                          } ?>

                                                          <p class="meta"><time datetime="2018"><?= $date ?>, <?= $supportDate->format('H:i') ?></time></p>

                                                      </div>
                                                  </div>
                                                  <?php } ?>
                                                    <?php
                                              }
                                              }
                                              ?>

                                                 <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;">
                                                      <div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                                                  </div>
                                                  <div class="ps-scrollbar-y-rail" style="top: 0px; height: 0px; right: 2px;">
                                                      <div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 2px;"></div>
                                                  </div>
                                              </div>
                                              <?php
                                                if ($support->getStatus() != 'closed') {?>
                                                    <div class="publisher bt-1 border-light"><img
                                                                class="avatar avatar-xs"
                                                                src="<?= $customer->getAvatar(true) ?>" alt="...">
                                                        <input id="message_text_input" class="publisher-input" type="text"
                                                               placeholder=" <?= $language::translate('Write something') ?>">
                                                        <input id="upload" type="file" style="display:none"
                                                               data-id="<?= $support->getId() ?>"/>
                                                        <span class="file-name"></span>
                                                        <a class="publisher-btn text-info" id="upload-button" href="#"
                                                           data-abc="true"><i class="ti-file"></i></a>
                                                        <button onclick="sendMessage('customer', <?= $support->getId(
                                                        ) ?>)" class="publisher-btn text-info" data-abc="true"><i
                                                                    class="ti-angle-right"></i></button>
                                                    </div>
                                                <?php }
                                              ?>
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
   $newCustomerVehicles = (new CustomerVehicle())->findBy(['filter' => ['customer_id' => $customer->getId(),'status' => 'pending', 'deleted' => 0]]);
   foreach ($newCustomerVehicles as $_customerVehicle) {
   ?>
   <option value="<?=$_customerVehicle->vehicle->getFullName()?>"><?=$_customerVehicle->vehicle->getFullName()?></option>
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
                               <input type="file" name="file"  class="form-control-file">
                           </div>
                           </div>
                       </div>
                       <div class="modal-footer">
                           <button  type="button" class="btn btn-secondary" data-dismiss="modal"><?= $language::translate('Cancel') ?></button>
                           <button   type="submit" class="btn btn-primary"><?= $language::translate('Create') ?></button>
                       </div>
                   </form>
                 </div>

             </div>
         </div>
     </div>
    <script src="/assets/js/ie11fix/fn.fix-padStart.js"></script>
    <script src="/plugins/editors/quill/quill.js"></script>
    <script src="/plugins/sweetalerts/sweetalert2.min.js"></script>
    <script src="/plugins/notification/snackbar/snackbar.min.js"></script>

 	</body>
<script>

    $(document).ready(function () {
        chatScrollBottom();


        // Get the input field
        var input = document.getElementById("message_text_input");

        input.addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                if(input.value) {
                    event.preventDefault();
                    sendMessage('customer', <?= $support->getId() ?>);
                }
            }
        });
    });

    function sendMessage(who, id)
    {
        var message = $('.publisher-input').val();
        var file = $('input[id=upload]')[0].files[0];

        if (message || file) {

            var formData = new FormData();
            formData.append('message', message);
            formData.append('file', file);

            var result = $.ajax({
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                url: '/ajax/' + who + '/support/send-message/' + id,
                data: formData,
                async: false,
            }).responseText;

            $(result).insertAfter($('div.media').last());

            $('.publisher-input').val('');

            chatScrollBottom();
        }
    }

    $("#upload-button").on('click', function (e) {
        e.preventDefault();
        $("#upload:hidden").trigger('click');
    });

    $("#upload:hidden").change(function () {
        var file = $('input[id=upload]')[0].files[0];
        $('span.file-name').text(file.name);
    });

    function supportFileDownload(url)
    {
        $(location).attr('href', url);
    }

    function chatScrollBottom()
    {
        $("#chat-content").animate({
            scrollTop: $('#chat-content').get(0).scrollHeight
        }, 1000);
    }

</script>
</html>
