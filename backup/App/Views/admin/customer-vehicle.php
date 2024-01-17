<?php

use Pemm\Core\Container;
use Pemm\Model\EmailNotification;
use Pemm\Model\VehicleAdditionalOption;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Customer;
use Pemm\Model\CustomerVehicle;
use Pemm\Model\Setting;
use Pemm\Controller\Sms;

global $container;

/* @var Request $request */
$request = $container->get('request');
$setting = (new Setting())->find(1);

/* @var Session $session */
$session = $container->get('session');

$language = $container->get('language');

$customerVehicle = (new CustomerVehicle())->find($container->get('detailId'));

if (empty($customerVehicle)) {
    new RedirectResponse('/admin');
}

if ($request->isMethod('post')) {

    try {

        $email = false;

        if ($customerVehicle->getStatus() != $request->request->get('status')) {
            (new Sms(false))->adminChangeFileStatus($container->get('detailId'));
            $email = true;
        }

        $customerVehicle->setStatus($request->request->get('status'));

        if (!empty($request->files->get('system_ecu_file'))) {
            $ecuFile = $customerVehicle->upload('system_ecu', $request->files->get('system_ecu_file'));
            $customerVehicle->setSystemEcuFile($ecuFile->getBasename());
        }
        if (!empty($request->files->get('system_id_file'))) {
            $idFile = $customerVehicle->upload('system_id', $request->files->get('system_id_file'));
            $customerVehicle->setSystemIdFile($idFile->getBasename());
        }

        if (!empty($request->files->get('system_dyno_file'))) {
            $dynoFile = $customerVehicle->upload('system_dyno', $request->files->get('system_dyno_file'));
            $customerVehicle->setSystemDynoFile($dynoFile->getBasename());
        }

        if (!empty($request->files->get('system_log_file'))) {
            $logFile = $customerVehicle->upload('system_log', $request->files->get('system_log_file'));
            $customerVehicle->setSystemLogFile($logFile->getBasename());
        }

        if (!empty($request->request->get('admin_note'))) {
            $customerVehicle->setAdminNote($request->request->get('admin_note'));
        }

        $customerVehicle->store();

        if ($email) {
            (new EmailNotification())->send('customerVehicle', 'statusChange', $customerVehicle);
        }

        $session->getFlashBag()->add('success', 'Success');

    } catch (\Exception $exception) {
        $session->getFlashBag()->add('danger', $exception->getMessage());
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('Customer Vehicle') ?> - <?= SITE_NAME ?> </title>
    <?php include("css.php")?>
    <link href="<?= SITE_URL ?>\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\bootstrap-select\bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\bootstrap-toggle\bootstrap-toggle.min.css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\table\datatable\datatables.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\assets\css\forms\theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\table\datatable\dt-global_style.css">
    <link rel="stylesheet" href="\assets/css/themify-icons.css">
 <link rel="stylesheet" href="\assets/css/ie7/ie7.css">
</head>
<body>
    <?php include("header.php")?>
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
    <?php include("sidebar.php")?>
         <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <form action="" method="post" style="width: 100%" enctype="multipart/form-data">
                <div class="row layout-top-spacing">
                    <div class="col-xl-6 col-lg-6 col-sm-12 ">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $language::translate('Vehicle Detail') ?></h5>
                                    <?php
                                    foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                                        <div class="alert alert-<?= $type ?>">
                                            <?php foreach ($messages as $message) { echo $message;} ?>
                                        </div>
                                    <?php }
                                    ?>
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Status') ?>
                                            <select name="status" id="status" class="form-control" style="width: 50%">
                                            <?php foreach ($customerVehicle::situations() as $status => $label) {?>
                                                    <option value="<?= $status ?>"<?= ($customerVehicle->getStatus() == $status ? 'selected' : '') ?>> <?= $language::translate($label) ?></option>
                                            <?php } ?>
                                            </select>
                                        </li>

                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('File Time') ?>
                                            <span style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-danger badge-pill"><i class="ti-time"> </i><?= $language::translate($customerVehicle->getFileTime()) ?></span>
                                        </li>

                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Clients') ?>
                                            <span style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"><?= $customerVehicle->getCustomer()->getFirstName().' '. $customerVehicle->getCustomer()->getLastName() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Credit') ?>
                                            <span href="#" data-toggle="modal" data-target="#editCredit" style="  padding: 5px 23px;  line-height: 36px; border-radius: 0;cursor: pointer"  class="badge badge-primary badge-pill"><i class="ti-pencil-alt"></i> <?= $customerVehicle->getTotalCredit() ?>                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Vehicle') ?>
                                            <span  style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"><?= ($customerVehicle->getWMVdata('wmv_vehicle_name') != NULL ? $customerVehicle->getWMVdata('wmv_vehicle_name') : $customerVehicle->vehicle->getFullName()) ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Model') ?>
                                            <span  style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"><?= $customerVehicle->getModel() ?></span>
                                        </li>

                                        <? if (!empty($customerVehicle->getWMVdata('wmv_brand_name'))) : ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <?= $language::translate('Brand') ?>
                                                <span  style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"><?= $customerVehicle->getWMVdata('wmv_brand_name') ?></span>
                                            </li>
                                        <? endif; ?>
                                        <? if (!empty($customerVehicle->getWMVdata('wmv_generation_name'))) : ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <?= $language::translate('Generation') ?>
                                                <span  style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"><?= $customerVehicle->getWMVdata('wmv_generation_name') ?></span>
                                            </li>
                                        <? endif; ?>
                                        <? if (!empty($customerVehicle->getWMVdata('wmv_engine_name'))) : ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <?= $language::translate('Engine') ?>
                                                <span  style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"><?= $customerVehicle->getWMVdata('wmv_engine_name') ?></span>
                                            </li>
                                        <? endif; ?>

                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Manufacturer') ?>
                                            <span  style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"><?= $customerVehicle->getManufacturer() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Kilometer') ?>
                                            <span style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"><?= $customerVehicle->getKilometer() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Gear') ?>
                                            <span style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"><?= $customerVehicle->getGear() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Power') ?>
                                            <span style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"> <?= $customerVehicle->getPower() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Torque') ?>
                                            <span style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"><?= $customerVehicle->getTorque() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Vehicle Registiration') ?>
                                            <span style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"> <?= $customerVehicle->getVehicleRegistration() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Reading Device') ?>
                                            <span style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"><?= $customerVehicle->getReadingDevice() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Master / Slave') ?>
                                            <span style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"><?= $customerVehicle->getMasterSlave() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Reading Type') ?>
                                            <span style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"><?= $customerVehicle->getReadingType() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Ecu') ?>
                                            <span  style="  padding: 0 15px;  line-height: 25px; border-radius: 0;" class="badge badge-primary badge-pill"> <?= $customerVehicle->getEcu() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Equipment') ?>
                                            <span style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"> <?= $customerVehicle->getEquipment() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Software') ?>
                                            <span style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"> <?= $customerVehicle->getSoftware() ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $language::translate('Note') ?>
                                            <span style="  padding: 0 15px;  line-height: 25px; border-radius: 0;"  class="badge badge-primary badge-pill"> <?= $customerVehicle->getNote() ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <div class="col-xl-6 col-lg-6 col-sm-12">
                      <div class="card mb-3">
                          <div class="card-body">
                              <h5 class="card-title"><?= $language::translate('Date') ?></h5>
                              <ul class="list-group">
                                  <li class="list-group-item d-flex justify-content-between align-items-center">
                                      <?= $language::translate('Creation Date') ?>
                                      <span class="badge badge-warning badge-pill"><?= $customerVehicle->getCreatedAt() ?></span>
                                  </li>

                                  <li class="list-group-item d-flex justify-content-between align-items-center">
                                      <?= $language::translate('Modified Date') ?>
                                      <span class="badge badge-warning badge-pill"><?= $customerVehicle->getChangedAt() ?></span>
                                  </li>


                              </ul>
                          </div>
                      </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><?= $language::translate('Tuning Detail') ?></h5>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= $language::translate('Tuning') ?>
                                        <span class="badge badge-success badge-pill"><?= $customerVehicle->vehicleTuning->getName() ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= $language::translate('Tuning Options') ?>
                                        <?php
                                        $options = [];
                                        if (!empty($customerVehicle->vehicleAdditionalOptions)) {
                                            /* @var VehicleAdditionalOption $vehicleTuningAdditionalOption */
                                            foreach ($customerVehicle->vehicleAdditionalOptions as $_key => $vehicleTuningAdditionalOption) {
                                                $options[$_key] = $vehicleTuningAdditionalOption->additionalOption->getName();
                                            }
                                        }
                                        ?>
                                        <span class="badge badge-success badge-pill"><?= implode(', ', $options) ?></span>
                                    </li>

                                </ul>
                            </div>
                        </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $language::translate('Customer Files') ?></h5>
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Ecu <?= $language::translate('File') ?>
                                            <span class="badge badge-info  badge-pill">
                                                <?php
                                                if (!empty($customerVehicle->getEcuFile())) {?>
                                                    <?= substr($customerVehicle->getEcuFile(),0,25) ?>...
                                                    <a href="/admin/file/download?file=<?= $customerVehicle->getEcuFile() ?>" target="_blank" class="btn download-btn"><i class="ti-download"></i></a>
                                                <?php }
                                                ?>
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            ID  <?= $language::translate('File') ?>
                                            <span class="badge badge-danger  badge-pill">
                                                <?php
                                                if (!empty($customerVehicle->getIdFile())) {?>
                                                  <?= $customerVehicle->getIdFile() ?>
                                                    <a href="/admin/file/download?file=<?= $customerVehicle->getIdFile() ?>" target="_blank" class="btn download-btn"><i class="ti-download"></i></a>
                                                <?php }
                                                ?>
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Log <?= $language::translate('File') ?>
                                            <span class="badge badge-warning  badge-pill">
                                                <?php
                                                if (!empty($customerVehicle->getLogFile())) {?>
                                                    <?= $customerVehicle->getLogFile() ?>
                                                    <a href="/admin/file/download?file=<?= $customerVehicle->getLogFile() ?>" target="_blank" class="btn download-btn"><i class="ti-download"></i></a>
                                                <?php }
                                                ?>
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Dyno  <?= $language::translate('File') ?>
                                            <span class="badge badge-success  badge-pill">
                                                <?php
                                                if (!empty($customerVehicle->getDynoFile())) {?>
                                                    <?= $customerVehicle->getDynoFile() ?>
                                                    <a href="/admin/file/download?file=<?= $customerVehicle->getDynoFile() ?>" target="_blank" class="btn download-btn"><i class="ti-download"></i></a>
                                                <?php }
                                                ?>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= $language::translate('System Files') ?></h5>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                      Ecu  <?= $language::translate('File') ?>
                                        <span class="badge badge-info badge-pill">
                                                 <input id="system_ecu_file" name="system_ecu_file" type="file" class="file">
                                                <?php
                                                if (!empty($customerVehicle->getSystemEcuFile())) {?>
                                                    <a href="/admin/file/download?file=<?= $customerVehicle->getSystemEcuFile() ?>" target="_blank" class="btn download-btn"><i class="ti-download"></i></a>
                                                <?php } ?>
                                            </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                      ID <?= $language::translate('File') ?>
                                        <span class="badge badge-danger  badge-pill">
                                                <input id="system_id_file" name="system_id_file" type="file" class="file">
                                                <?php
                                                if (!empty($customerVehicle->getSystemIdFile())) {?>
                                                    <a href="/admin/file/download?file=<?= $customerVehicle->getSystemIdFile() ?>" target="_blank" class="btn download-btn"><i class="ti-download"></i></a>
                                                <?php } ?>
                                            </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                      Log <?= $language::translate('File') ?>
                                        <span class="badge badge-warning  badge-pill">
                                                <input id="system_log_file" name="system_log_file" type="file" class="file">
                                                <?php
                                                if (!empty($customerVehicle->getSystemLogFile())) {?>
                                                    <a href="/admin/file/download?file=<?= $customerVehicle->getSystemLogFile() ?>" target="_blank" class="btn download-btn"><i class="ti-download"></i></a>
                                                <?php } ?>
                                            </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                      Dyno  <?= $language::translate('File') ?>
                                        <span class="badge badge-success  badge-pill">
                                               <input id="system_dyno_file" name="system_dyno_file" type="file" class="file">
                                                <?php
                                                if (!empty($customerVehicle->getSystemDynoFile())) {?>
                                                    <a href="/admin/file/download?file=<?= $customerVehicle->getSystemDynoFile() ?>" target="_blank" class="btn download-btn"><i class="ti-download"></i></a>
                                                <?php } ?>
                                            </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= $language::translate('Admin Note') ?>
                                            <textarea style="    height: 120px;" name="admin_note" id="admin_note" class="form-control"><?= $customerVehicle->getAdminNote() ?></textarea>
                                    </li>

                                </ul>
                            </div>
                        </div>
                        </div>
                    <div class="col-xl-12 col-lg-12 col-sm-12">
                        <button type="submit" class="btn btn-primary" style="width: 100%"><?= $language::translate('Save') ?></button>
                    </div>
                </div>
                </form>
                <div class="modal fade" id="editCredit" tabindex="-1" role="dialog"
                     aria-labelledby="editCreditlLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="/admin/customer/vehicle/<?= $customerVehicle->getId() ?>/change-credit"
                                  method="post">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"> <?= $language::translate(
                                            'Change Vehicle Total Credit'
                                        ) ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <i class="text-danger"> <?= $language::translate('Customer Total Credit') . ' : ' . $customerVehicle->getCustomer()->getCredit() ?></i>
                                    <div class="form-group">
                                        <label> <?= $language::translate('New Vehicle Credit') ?> </label>
                                        <input id="new-credit" type="number" class="form-control" min="<?= $customerVehicle->getTotalCredit() ?>"
                                               name="new-credit" placeholder="<?= $language::translate('New Credit') ?>"
                                               required="required">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal"><?= $language::translate('Close') ?>
                                    </button>
                                    <button type="submit" class="btn btn-primary"><?= $language::translate(
                                            'Update'
                                        ) ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
              <?php include("alt.php")?>
    </div>
     <?php include("js.php")?>
         <script src="<?= SITE_URL ?>\plugins\bootstrap-select\bootstrap-select.min.js"></script>
        <script src="<?= SITE_URL ?>\plugins\bootstrap-toggle\bootstrap-toggle.min.js"></script>
     <!-- BEGIN PAGE LEVEL SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?= SITE_URL ?>\plugins\table\datatable\datatables.js"></script>
    </body>
<style>
    .badge-pill {
        border-radius: .25rem !important;
    }

    .download-btn:hover {
        box-shadow: none;
    }
    .list-group-item {
    background-color: #0e1726 !important;
    border: 1px solid #162338 !important;
}
</style>
</html>
