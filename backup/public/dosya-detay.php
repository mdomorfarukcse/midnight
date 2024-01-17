<?php

use Pemm\Core\Container;
use Pemm\Model\Customer;
use Pemm\Model\Setting;
use Pemm\Model\CustomerVehicle;

global $container;
$setting = (new Setting())->find(1);

/* @var Customer $customer */
$customer = $container->get('customer');

$language = $container->get('language');

$today = new DateTime();
$yesterday = new DateTime('yesterday');

/* @var CustomerVehicle $customerVehicle */
$customerVehicle = $container->get('customerVehicle');
$options = [];
if (!empty($customerVehicle->vehicleAdditionalOptions)) {
  /* @var VehicleAdditionalOption $vehicleTuningAdditionalOption */
  foreach ($customerVehicle->vehicleAdditionalOptions as $_key => $vehicleTuningAdditionalOption) {
    $options[$_key] = $vehicleTuningAdditionalOption->additionalOption->getName();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title><?= $language::translate('My Files') ?> - <?= SITE_NAME ?> </title>
  <?php include("css.php") ?>
  <link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="\plugins\bootstrap-select\bootstrap-select.min.css">
  <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
  <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\datatables.css">
  <link rel="stylesheet" type="text/css" href="\assets\css\forms\theme-checkbox-radio.css">
  <link rel="stylesheet" type="text/css" href="\plugins\table\datatable\dt-global_style.css">
  <link rel="stylesheet" href="\assets/css/themify-icons.css">
  <link rel="stylesheet" href="\assets/css/ie7/ie7.css">
  <link href="\assets/css/elements/infobox.css" rel="stylesheet" type="text/css" />
  <link href="\assets/css/file-detail.css" rel="stylesheet" type="text/css" />


</head>

<body>
  <?php include("ust2.php") ?>
  <div class="main-container" id="container">
    <?php include("ust.php") ?>
    <div id="content" class="main-content">
      <div class="layout-px-spacing">
        <div class="grid">
          <div class="grid-sizer col-md-3"></div>
          <div class="row layout-top-spacing">
            <div class="grid-item col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
              <div class="widget widget-three">
                <div class="widget-content">
                  <div style="border: 0;     background-color: #ffffff !important;" class="card">
                    <div class="card-body">
                      <h5 style=" margin-bottom: 17px;" class="card-title"><img style="    width: 120px;" src="<?= $customerVehicle->getVehicle()->getBrandImage() ?>"></h5>
                      <table class="table table-bordered table-striped mb-4">
                        <tbody>
                          <tr>
                            <td class="tabloduzelt kalinlastir "><?= $language::translate('File Time') ?>: </td>
                            <td style="white-space: pre-line;" class="tabloduzelt" id="file_time"> <span  class="badge badge-info"><i class="ti-time"> </i><?= $language::translate($customerVehicle->getFileTime())  ?></span></td>
                          </tr>
                          <tr>
                            <td class="tabloduzelt kalinlastir "><?= $language::translate('Status') ?>: </td>
                            <td style="white-space: pre-line;" class="tabloduzelt" id="vehicle_name"><span class="badge badge-<?php
                          switch ($customerVehicle->getStatus()) {
                            case 'awaiting_payment':
                              echo 'danger';
                              break;
                            case 'pending':
                              echo "info";
                              break;
                            case 'process':
                              echo "warning";
                              break;
                            case 'completed':
                              echo "success";
                              break;
                            case 'cancel':
                              echo 'danger';
                              break;
                          } ?>" class=" badge btn"><?= $language::translate( $customerVehicle->getStatus() ) ?>
                              </span></td>
                          </tr>
                          <tr>
                            <td class="tabloduzelt kalinlastir "><?= $language::translate('Creation Date') ?>: </td>
                            <td style="white-space: pre-line;" class="tabloduzelt" id="vehicle_name"><?= $customerVehicle->getCreatedAt() ?></td>
                          </tr>
                          <tr>
                            <td class="tabloduzelt kalinlastir "><?= $language::translate('Modified Date') ?>: </td>
                            <td style="white-space: pre-line;" class="tabloduzelt" id="vehicle_name"><?= $customerVehicle->getChangedAt() ?></td>
                          </tr>
                          <tr>
                            <td class="tabloduzelt kalinlastir "><?= $language::translate('Vehicle') ?>: </td>
                            <td style="white-space: pre-line;" class="tabloduzelt" id="vehicle_name"><? if ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL) { echo $customerVehicle->getWMVdata('wmv_vehicle_name'); } else { echo $customerVehicle->getVehicle()->getFullName(); } ?></td>
                          </tr>
                          <tr>
                            <td class="tabloduzelt kalinlastir "><?= $language::translate('Model') ?>:</td>
                            <td class="tabloduzelt" id="vehicle_model"><?= $customerVehicle->getModel() ?></td>
                          </tr>
                          
                          <? if (!empty($customerVehicle->getWMVdata('wmv_brand_name'))) : ?>
                          <tr>
                            <td class="tabloduzelt kalinlastir "><?= $language::translate('Brand') ?>:</td>
                            <td class="tabloduzelt" id="vehicle_model"><?= $customerVehicle->getWMVdata('wmv_brand_name') ?></td>
                          </tr>
                          <? endif; ?>
                          <? if (!empty($customerVehicle->getWMVdata('wmv_generation_name'))) : ?>
                          <tr>
                            <td class="tabloduzelt kalinlastir "><?= $language::translate('Generation') ?>:</td>
                            <td class="tabloduzelt" id="vehicle_model"><?= $customerVehicle->getWMVdata('wmv_generation_name') ?></td>
                          </tr>
                          <? endif; ?>
                          <? if (!empty($customerVehicle->getWMVdata('wmv_engine_name'))) : ?>
                          <tr>
                            <td class="tabloduzelt kalinlastir "><?= $language::translate('Engine') ?>:</td>
                            <td class="tabloduzelt" id="vehicle_model"><?= $customerVehicle->getWMVdata('wmv_engine_name') ?></td>
                          </tr>
                          <? endif; ?>

                          <tr>
                            <td class="tabloduzelt kalinlastir"><?= $language::translate('Kilometer') ?>:</td>
                            <td class="tabloduzelt" id="kilometer"><?= $customerVehicle->getKilometer() ?></td>
                          </tr>
                          <tr>
                            <td class="tabloduzelt kalinlastir"><?= $language::translate('Power') ?>:</td>
                            <td class="tabloduzelt" id="power"><?= $customerVehicle->getPower() ?></td>
                          </tr>
                          <tr>
                            <td class="tabloduzelt kalinlastir"><?= $language::translate('Torque') ?>:</td>
                            <td class="tabloduzelt" id="torque"><?= $customerVehicle->getTorque() ?></td>
                          </tr>
                          <tr>
                            <td class="tabloduzelt kalinlastir"><?= $language::translate('Ecu') ?>:</td>
                            <td style="white-space: pre-line" class="tabloduzelt" id="ecu"><?= $customerVehicle->getEcu() ?></td>
                          </tr>
                          <tr>
                            <td class="tabloduzelt kalinlastir "><?= $language::translate('Master / Slave') ?>:</td>
                            <td class="tabloduzelt" id="master_slave"><?= $customerVehicle->getMasterSlave() ?></td>
                          </tr>
                          <tr>
                            <td class="tabloduzelt kalinlastir"><?= $language::translate('Vehicle Registration') ?>:</td>
                            <td class="tabloduzelt" id="plaka"><?= $customerVehicle->getVehicleRegistration() ?></td>
                          </tr>
                          <tr>
                            <td class="tabloduzelt kalinlastir"><?= $language::translate('Reading Type') ?>:</td>
                            <td class="tabloduzelt" id="reading_type"><?= $customerVehicle->getReadingType() ?></td>
                          </tr>
                          <tr>
                            <td class="tabloduzelt kalinlastir"><?= $language::translate('Reading Device') ?>:</td>
                            <td class="tabloduzelt" id="reading_device"><?= $customerVehicle->getReadingDevice() ?></td>
                          </tr>
                          <tr>
                            <td class="tabloduzelt kalinlastir"><?= $language::translate('Notes') ?>:</td>
                            <td class="tabloduzelt" id="notes"><?= $customerVehicle->getNote() ?></td>
                          </tr>
                          <tr>
                            <td class="tabloduzelt kalinlastir"><?= $language::translate('Admin Note') ?>:</td>
                            <td class="tabloduzelt" id="admin_note"><?= $customerVehicle->getAdminNote() ?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>


                </div>
              </div>
            </div>
            <div class="grid-item col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">

              <div class="widget-content">
                <div class="page-content page-container" id="page-content">
                  <div class="padding">
                    <div class="row">
                      <div class="col-md-12">
                        <div style="    background-color: #0f1726 !important;" class="card card-bordered">
                          <div style=" padding-bottom: 0;" class="card-header">
                            <h5 class="card-title"><strong> <i style="font-size: 18px;margin-right: 8px;" class="ti-write"></i> <?= $language::translate('Support Tickets') ?> </strong></h5>
                          </div>
                          <div class="ps-container ps-theme-default ps-active-y" id="chat-content" style="overflow-y: scroll !important; height:400px !important;">
                            <div class="media media-chat">
                              <img class="avatar" src="/assets/img/<?=$setting->getsiteFavicon();?>" alt="...">
                              <div class="media-body">
                                <p><?= $language::translate('Hello') ?> </p>
                                <p><?= ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('vehicle_full_name') : $customerVehicle->vehicle->getFullName()) ?></p>
                                <p><?= $language::translate('Is there a problem with the vehicle?') ?></p>
                                <p class="meta"><time datetime="2018"><?php $date = new DateTime('now');
             echo $date->format("H:i"); ?></time></p>
                              </div>
                            </div>
                              <?
                                $wmvcid = $customerVehicle->getCustomerId();
                                $wmvid = $customerVehicle->getId();
                                $wmvcfname = ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('wmv_vehicle_name') : $customerVehicle->vehicle->getFullName());

                                $sql = "SELECT * FROM `support` WHERE `customer_id` = $wmvcid AND `c_v_id` = '{$wmvid}' ORDER BY id DESC";
                                $prepare = $customerVehicle->database->prepare($sql);
                                $prepare->execute([]);
                                $wmvdata = $prepare->fetch(PDO::FETCH_OBJ);
                                if (!empty($wmvdata)) {
                                  $wmvsid = $wmvdata->id;
                                  $wmvstatus = $wmvdata->status;
                                  $wmvreference = $wmvdata->reference;
                                }else {
                                  $wmvsid = $wmvstatus = $wmvreference = NULL;
                                }

                                if ($wmvstatus != 'closed' || !empty($wmvreference) || !empty($wmvdata)) {
                                  // start chat messages
                                  $sql = "SELECT * FROM `support` WHERE `reference` = '$wmvreference' ORDER BY id ASC";
                                  $prepare = $customerVehicle->database->prepare($sql);
                                  $prepare->execute([]);
                                  $wmvfind = $prepare->fetchAll(PDO::FETCH_OBJ);
                                  foreach ($wmvfind as $key => $message) {
                                    $supportDate = DateTime::createFromFormat('Y-m-d H:i:s', $message->created_at);
                                    $date = $supportDate->format('d M Y');
                                    if ($supportDate->format('Y-m-d') == $today->format('Y-m-d')) {
                                      $date = $language::translate('Today');
                                    } elseif ($supportDate->format('Y-m-d') == $yesterday->format('Y-m-d')){
                                      $date = $language::translate('Yesterday');
                                    }

                                    if($message->type == 'customer'){ ?>
                                      <div class="media media-chat media-chat-reverse">
                                        <div class="media-body">
                                            <?php
                                                if (!empty($message->text)) { ?>
                                                    <p><?= $message->text ?></p>
                                                <?php } ?>
                                            <?php if($message->file != null){ ?>
                                                <br><a onclick="supportFileDownload('/panel/file/support/<?= $message->id ?>/download')"
                                                    class="btn btn-sq btn-success"><i
                                                            class="ti-download"></i><br> <?= $message->file ?> </a><br>
                                                <?php
                                            } ?>
                                          <p class="meta"><time style="color: #9b9b9b;" datetime="2018"><?= $date ?>, <?= $supportDate->format('H:i') ?></time></p>
                                        </div>
                                      </div>

                                    <? } else { ?>
                                      <div class="media media-chat"> <img class="avatar" src="/assets/img/<?=$setting->getsiteFavicon();?>" alt="...">
                                          <div class="media-body">

                                              <p><?= $message->text ?></p>

                                              <? if($message->file != null){ ?>
                                                  <br><a onclick="supportFileDownload('/panel/file/support/<?= $message->id ?>/download')" class="btn btn-sq btn-success"><i class="ti-download"></i><br> <?= $message->file ?> </a><br>
                                              <? } ?>

                                              <p class="meta"><time datetime="2018"><?= $date ?>, <?= $supportDate->format('H:i') ?></time></p>

                                          </div>
                                      </div>
                                    <? }
                                  }// end chat messages
                                }
                              ?>

                          <!--  <div class="media media-meta-day">Today</div>
                            <div class="media media-chat media-chat-reverse">
                              <div class="media-body">
                                 <p>Hiii, I'm good.</p>
                                <p class="meta"><time datetime="2018">00:06</time></p>
                              </div>
                            </div> -->



                            <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;">
                              <div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                            </div>
                            <div class="ps-scrollbar-y-rail" style="top: 0px; height: 0px; right: 2px;">
                              <div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 2px;"></div>
                            </div>
                          </div>

                          <? if ($wmvstatus == 'closed' OR empty($wmvreference)) : ?>
                          <form action="<?=$setting->getSiteUrl()?>/panel/ticket" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="subject" value="<?= ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('wmv_vehicle_name') : $customerVehicle->vehicle->getFullName()) ?>" required>
                            <input type="hidden" name="vehicle" value="<?= ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('wmv_vehicle_name') : $customerVehicle->vehicle->getFullName()) ?>" required>
                            <input type="hidden" name="cvehicle_id" value="<?=$customerVehicle->getId()?>" required>
                            <input type="file" name="file" id="wmvfile" class="d-none">

                            <div class="publisher bt-1 border-light">
                              <img class="avatar avatar-xs" src="<?= $customer->getAvatar(true) ?>" alt="...">
                              <input class="publisher-input" type="text" name="text" placeholder="<?= $language::translate('Write something') ?> " required>
                              <span class="publisher-btn file-group">
                                <i class="ti-files file-browser" onclick="$('#wmvfile').click();"></i>
                                <input type="file" id="upload">
                              </span>
                              <button class="publisher-btn text-info" type="submit" data-abc="true"><i class="ti-arrow-circle-right"></i></button>
                            </div>
                          </form>
                        <? else : ?>
                            <input type="hidden" name="subject" value="<?= ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('wmv_vehicle_name') : $customerVehicle->vehicle->getFullName()) ?>" required>
                            <input type="hidden" name="vehicle" value="<?= ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('wmv_vehicle_name') : $customerVehicle->vehicle->getFullName()) ?>" required>
                            <input type="file" name="file" id="wmvfile" class="d-none">

                            <div class="publisher bt-1 border-light">
                              <img class="avatar avatar-xs" src="<?= $customer->getAvatar(true) ?>" alt="...">
                              <input class="publisher-input" type="text" name="text" placeholder="<?= $language::translate('Write something') ?> ">
                              <span class="publisher-btn file-group">
                                <i class="ti-files file-browser" onclick="$('#wmvfile').click();"></i>
                                <input type="file" id="upload">
                              </span>
                              <button class="publisher-btn text-info" type="submit" onclick="return sendMessage('customer', <?=$wmvsid?>);" data-abc="true"><i class="ti-arrow-circle-right"></i></button>
                            </div>
                        <? endif; ?>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="grid-item col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
              <div class="widget widget-three">
                <div class="widget-content">
                  <div style="border: 0;    background-color: #0f1726 !important;" class="card">
                    <div class="card-body">
                      <h5 style=" margin-bottom: 17px;" class="card-title">
                        <i style="font-size: 18px;margin-right: 8px;" class="ti-dashboard"></i> <?= $language::translate('Modification Requests') ?>
                      </h5>
                      <table class="table table-striped mb-4 contextual-table">
                        <tbody>
                          <tr>
                            <td class="tabloduzelt" id="tuning_name"><?= $customerVehicle->getVehicleTuning()->getName(); ?></td>
                            <td class="tabloduzelt" id="tuning_credit"><span class="badge badge-info"><?= $customerVehicle->getVehicleTuning()->getCredit(); ?> CRD</span></td>
                          </tr>
                          <tr>
                            <td style="white-space: pre-line" class="tabloduzelt" id="tuning_options">
                              <?php $uzunluk = count($options);
                              $i = 0;
                              foreach ($options as $key) {
                                $i++;
                                if ($uzunluk == $i) {
                                  echo $key;
                                } else {
                                  echo $key . " - ";
                                }
                              }
                              ?>
                            </td>
                            <td class="tabloduzelt" id="tuning_options_credit"><span class="badge badge-info"><?= ($customerVehicle->getTotalCredit() - $customerVehicle->getVehicleTuning()->getCredit()); ?> CRD</span></td>
                          </tr>
                          <tr style="    background-color: #4361ee;">
                            <td class="tabloduzelt" ><?= $language::translate('Total Credit') ?>: </td>
                            <td class="tabloduzelt " id="total_credit"><span class="badge badge-success"> <?= $customerVehicle->getTotalCredit(); ?> CRD </span></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>


                </div>
              </div>
            </div>
            <div class="grid-item col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
              <div class="widget widget-three">
                <div class="widget-content">
                  <h5 style=" margin-bottom: 37px;" class="card-title"><i style="font-size: 18px;margin-right: 8px;" class="ti-files"></i> <?= $language::translate('Original Files') ?></h5>
                  <div class="row">

                    <?php
                    if (strlen($customerVehicle->getEcuFile())  > 5) {
                    ?>
                      <div class="col-12">
                        <div class="infobox-1">
                          <div class="info-icon">
                            <i class="ti-zip ikonduzenle1"></i>
                          </div>
                          <h5 class="info-heading">Ecu <?= $language::translate('File') ?></h5>
                          <p class="info-text"><?= $customerVehicle->getEcuFile() ?></p>
                          <a class="info-link" href="/panel/file/download?file=<?= $customerVehicle->getEcuFile() ?>" download><?= $language::translate('Download') ?> <svg> ... </svg></a>
                        </div>
                      </div>
                    <?php } ?>


                    <?php
                    if (strlen($customerVehicle->getIdFile())  > 5) {
                    ?>
                      <div class="col-12">
                        <div class="infobox-2">
                          <div  class="info-icon">
                            <i class="ti-clipboard ikonduzenle2"></i>
                          </div>
                          <h5 class="info-heading">ID <?= $language::translate('File') ?></h5>
                          <p class="info-text"><?= $customerVehicle->getIdFile() ?></p>
                          <a class="info-link" href="/panel/file/download?file=<?= $customerVehicle->getIdFile() ?>" download><?= $language::translate('Download') ?> <svg> ... </svg></a>
                        </div>
                      </div>
                    <?php } ?>



                    <?php
                    if (strlen($customerVehicle->getLogFile())  > 5) {
                    ?>
                      <div class="col-12">
                        <div class="infobox-3">
                          <div  class="info-icon">
                            <i class="ti-clipboard ikonduzenle3"></i>
                          </div>
                          <h5 class="info-heading">Log <?= $language::translate('File') ?></h5>
                          <p class="info-text"><?= $customerVehicle->getLogFile() ?></p>
                          <a class="info-link" href="/panel/file/download?file=<?= $customerVehicle->getLogFile() ?>" download><?= $language::translate('Download') ?> <svg> ... </svg></a>
                        </div>
                      </div>
                    <?php } ?>


                  <?php
                  if (strlen($customerVehicle->getDynoFile())  > 5) {
                  ?>
                    <div class="col-12">
                      <div class="infobox-4">
                        <div  class="info-icon">
                          <i class="ti-file ikonduzenle4"></i>
                        </div>
                        <h5 class="info-heading">Dyno <?= $language::translate('File') ?></h5>
                        <p class="info-text"><?= $customerVehicle->getDynoFile() ?></p>
                        <a class="info-link" href="/panel/file/download?file=<?= $customerVehicle->getDynoFile() ?>" download><?= $language::translate('Download') ?> <svg> ... </svg></a>
                      </div>
                    </div>
                  <?php } ?>


                  </div>
                </div>
              </div>
            </div>

            <div class="grid-item col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
              <div class="widget widget-three">
                <div class="widget-content">
                  <h5 style=" margin-bottom: 37px;" class="card-title"><i style="font-size: 18px;margin-right: 8px;" class="ti-file"></i><?= $language::translate('Tuned Files') ?></h5>
                  <div class="row">

                    <?php
                    if (strlen($customerVehicle->getSystemEcuFile())  > 5) {
                    ?>
                      <div class="col-12">
                        <div class="infobox-1">
                          <div class="info-icon">
                            <i class="ti-zip ikonduzenle1"></i>
                          </div>
                          <h5 class="info-heading">Ecu <?= $language::translate('File') ?></h5>
                          <p class="info-text"><?= $customerVehicle->getSystemEcuFile() ?></p>
                          <a class="info-link" href="/panel/file/download?file=<?= $customerVehicle->getSystemEcuFile() ?>" download><?= $language::translate('Download') ?> <svg> ... </svg></a>
                        </div>
                      </div>
                    <?php } ?>

                    <?php
                    if (strlen($customerVehicle->getSystemIdFile())  > 5) {
                    ?>
                      <div class="col-12">
                        <div class="infobox-2">
                          <div class="info-icon">
                            <i class="ti-zip ikonduzenle2"></i>
                          </div>
                          <h5 class="info-heading">ID <?= $language::translate('File') ?></h5>
                          <p class="info-text"><?= $customerVehicle->getSystemIdFile() ?></p>
                          <a class="info-link" href="/panel/file/download?file=<?= $customerVehicle->getSystemIdFile() ?>" download><?= $language::translate('Download') ?> <svg> ... </svg></a>
                        </div>
                      </div>
                    <?php } ?>


                <?php
                if (strlen($customerVehicle->getSystemLogFile())  > 5) {
                ?>
                  <div class="col-12">
                    <div class="infobox-4">
                      <div  class="info-icon badge-warning">
                        <i class="ti-clipboard ikonduzenle4"></i>
                      </div>
                      <h5 class="info-heading">Log <?= $language::translate('File') ?></h5>
                      <p class="info-text"><?= $customerVehicle->getSystemLogFile() ?></p>
                      <a class="info-link" href="/panel/file/download?file=<?= $customerVehicle->getSystemLogFile() ?>" download><?= $language::translate('Download') ?> <svg> ... </svg></a>
                    </div>
                  </div>
                <?php } ?>


                    <?php
                    if (strlen($customerVehicle->getSystemDynoFile())  > 5) {
                    ?>
                      <div class="col-12">
                        <div class="infobox-3">
                          <div  class="info-icon">
                            <i class="ti-file ikonduzenle3"></i>
                          </div>
                          <h5 class="info-heading">Dyno <?= $language::translate('File') ?></h5>
                          <p class="info-text"><?= $customerVehicle->getSystemDynoFile() ?></p>
                          <a class="info-link" href="/panel/file/download?file=<?= $customerVehicle->getSystemDynoFile() ?>" download><?= $language::translate('Download') ?> <svg> ... </svg></a>
                        </div>
                      </div>
                    <?php } ?>

                  </div>
                </div>
              </div>
            </div>


          </div>
        </div>

     </div>
    <?php include("alt.php") ?>
  </div>
  <?php include("js.php") ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js" charset="utf-8"></script>
  <script type="text/javascript">
    function sendMessage(who, id)
    {
        var message = $('.publisher-input').val();
        var file = $('input[id=wmvfile]')[0].files[0];
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

            window.location.reload();
        }
    }

    $(window).on('load', function() {
      chatScrollBottom();

      function gridMasonry() {
        var grid = $(".grid")
        if (grid.length) {

          grid.isotope({
            itemSelector: '.grid-item',
            percentPosition: true,
            layoutMode: 'masonry',
            masonry: {
              columnWidth: '.grid-sizer',
            },
          });

        }
      }
      gridMasonry();
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

<style>

  .table-striped tbody tr:nth-of-type(odd) {
   background-color: #060818 !important;
   border: none !important;
 }
 .table td, .table th {
      border-top: none !important;
 }
 </style>
</body>

</html>
