<?php

use Pemm\Core\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\CustomerGroup;
use Pemm\Model\Setting;
use Symfony\Component\HttpFoundation\RedirectResponse;

global $container;

/* @var Request $request */
$request = $container->get('request');
$setting = (new Setting())->find(1);

/* @var Session $session */
$session = $container->get('session');

$language = $container->get('language');

$customerGroup = $container->has('detailId') ? (new CustomerGroup())->find($container->get('detailId')) : new CustomerGroup();

if ($request->isMethod('post')) {

try {

$new = empty($customerGroup->getId());

$customerGroup
->setCode($request->request->get('code'))
->setName($request->request->get('name'))
->setStatus($request->request->getInt('status'))
->setType($request->request->get('type'))
->setProcessType($request->request->get('process_type'))
->setMultiplier($request->request->get('multiplier'))
->setExtra($request->request->get('extra'))
->setTaxRate($request->request->get('tax_rate'))
->setBonusCredit($request->request->get('bonus_credit'))
->setBonusCreditType($request->request->get('bonus_credit_type'));

$customerGroup->store();

$session->getFlashBag()->add('success', 'Success');

if ($new) (new RedirectResponse('/admin/customer-group/detail/' . $customerGroup->getId() . '?confirm_message=Success'))->send();

} catch (\Exception $exception) {
$session->getFlashBag()->add('danger', $exception->getMessage());
}

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title><?= $language::translate('Customer Group') ?> - <?= SITE_NAME ?> </title>
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
<link href="<?= SITE_URL ?>\assets/css/components/tabs-accordian/custom-tabs.css" rel="stylesheet" type="text/css" />

</head>
<body>
<?php include("header.php")?>
<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">
<?php include("sidebar.php")?>
<div id="content" class="main-content">
<div class="layout-px-spacing">


<div style="    margin-top: 33px;" id=" " class="col-lg-12 col-12 layout-spacing">
   <div class="statbox widget box box-shadow">
       <div class="widget-header">
           <div class="row">
               <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                   <h4><?= $language::translate('Customer Group') ?></h4>
               </div>
           </div>
       </div>
       <div style="    padding: 33px;" class="widget-content widget-content-area rounded-pills-icon">
         <form action="" method="post">
             <?php
             foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                 <div class="alert alert-<?= $type ?>">
                     <?php foreach ($messages as $message) { echo $message;} ?>
                 </div>
             <?php }
             ?>
           <ul class="nav nav-pills mb-4 mt-3  justify-content-center" id="rounded-pills-icon-tab" role="tablist">
               <li class="nav-item ml-2 mr-2">
                   <a class="nav-link mb-2 active text-center" id="rounded-pills-icon-home-tab" data-toggle="pill" href="#rounded-pills-icon-home" role="tab" aria-controls="rounded-pills-icon-home" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>  <?= $language::translate('Home') ?> </a>
               </li>
               <li class="nav-item ml-2 mr-2">
                   <a class="nav-link mb-2 text-center" id="rounded-pills-icon-profile-tab" data-toggle="pill" href="#rounded-pills-icon-profile" role="tab" aria-controls="rounded-pills-icon-profile" aria-selected="false">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>
                       <?= $language::translate('Discount') ?> </a>
               </li>
               <li class="nav-item ml-2 mr-2">
                   <a class="nav-link mb-2 text-center" id="rounded-pills-icon-contact-tab" data-toggle="pill" href="#rounded-pills-icon-contact" role="tab" aria-controls="rounded-pills-icon-contact" aria-selected="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>   <?= $language::translate('Raise') ?></a>
               </li>

               <li class="nav-item ml-2 mr-2">
                   <a class="nav-link mb-2 text-center" id="rounded-pills-icon-settings-tab" data-toggle="pill" href="#rounded-pills-icon-settings" role="tab" aria-controls="rounded-pills-icon-settings" aria-selected="false">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-smile"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                       <?= $language::translate('Bonus') ?></a>
               </li>
              <li class="nav-item ml-2 mr-2">
                  <a class="nav-link mb-2 text-center" id="rounded-pills-icon-tax-tab" data-toggle="pill" href="#rounded-pills-icon-tax" role="tab" aria-controls="rounded-pills-icon-tax" aria-selected="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                      <?= $language::translate('Tax') ?></a>
              </li>
           </ul>


           <div class="tab-content" id="rounded-pills-icon-tabContent">
               <div class="tab-pane fade show active" id="rounded-pills-icon-home" role="tabpanel" aria-labelledby="rounded-pills-icon-home-tab">

               <div class="form-group row">
                   <label for="code" class="col-sm-2 col-form-label"><?= $language::translate('Code') ?></label>
                   <div class="col-sm-10">
                       <input type="text" name="code" class="form-control" id="code"
                              value="<?= $customerGroup->getCode() ?>">
                   </div>
               </div>

               <div class="form-group row">
                   <label for="name" class="col-sm-2 col-form-label"><?= $language::translate('Name') ?></label>
                   <div class="col-sm-10">
                       <input type="text" name="name" class="form-control" id="name" value="<?= $customerGroup->getName() ?>">
                   </div>
               </div>
               <div class="form-group row">
                   <div class="col-sm-2"><?= $language::translate('Status') ?></div>
                   <div class="col-sm-10">
                       <div class="form-check">
                           <label class="checkbox-inline">
                               <input type="checkbox" value="1" name="status" <?= !$customerGroup->getStatus() ?: 'checked'; ?> data-toggle="toggle">
                           </label>
                       </div>
                   </div>
               </div>

               </div>
               <div class="tab-pane fade" id="rounded-pills-icon-profile" role="tabpanel" aria-labelledby="rounded-pills-icon-profile-tab">
           <div class="form-group row">
             <label for="process_type" class="col-sm-2 col-form-label"><?= $language::translate('Process Type') ?></label>

               <div class="col-sm-10">
               <select name="process_type" class="form-control" required>

                   <?php
                   $datas = [
                     'percent',
                     'amount'
                   ];
                       foreach ($datas as $process) {
                         echo '<option value="'.$process.'" '.( strtolower($process) == strtolower(
                                 $customerGroup->getProcessType()) ? 'selected':'').'>'.$process.'</option>';
                       }
                    ?>

                 </select>
                 </div>

           </div>

           <div class="form-group row">
               <label for="type"
                      class="col-sm-2 col-form-label"><?= $language::translate('Type') ?></label>
               <div class="col-sm-10">
                   <select name="type" class="form-control" required>
                       <?php
                       foreach (['decrease', 'increase'] as $_type) {?>
                           <?= '<option value="' . $_type . '"' . ($customerGroup->getType(
                           ) != $_type ?: 'selected') . '>' . $language::translate($_type) . '</option>'; ?>
                       <?php } ?>

                   </select>
               </div>

           </div>

           <div class="form-group row">
               <label for="multiplier" class="col-sm-2 col-form-label"><?= $language::translate('Multiplier') ?></label>
               <div class="col-sm-10">
                   <input type="number" step=0.01 name="multiplier" class="form-control" id="multiplier" value="<?= $customerGroup->getMultiplier() ?>">
               </div>
           </div>
               </div>
               <div class="tab-pane fade" id="rounded-pills-icon-contact" role="tabpanel" aria-labelledby="rounded-pills-icon-contact-tab">

               <div class="form-group row">
                   <label for="multiplier" class="col-sm-2 col-form-label"><?= $language::translate('Extra') ?> % </label>
                   <div class="col-sm-10">
                       <input type="number" step=0.01 name="extra" class="form-control" id="extra" value="<?= $customerGroup->getExtra() ?>">
                   </div>
               </div>

               </div>
               <div class="tab-pane fade" id="rounded-pills-icon-settings" role="tabpanel" aria-labelledby="rounded-pills-icon-settings-tab">
                 <div class="form-group row">
                     <label for="bonus_credit_type"
                            class="col-sm-2 col-form-label"><?= $language::translate('Process Type') ?></label>
                     <div class="col-sm-10">
                         <select name="bonus_credit_type" class="form-control">
                             <?php
                             foreach (['percent', 'amount'] as $_bonusCreditType) { ?>
                                 <?= '<option value="' . $_bonusCreditType . '"' . ($customerGroup->getBonusCreditType() != $_bonusCreditType ?: 'selected') . '>' . $language::translate($_bonusCreditType) . '</option>'; ?>
                             <?php
                             } ?>

                         </select>
                     </div>

                 </div>

                 <div class="form-group row">
                     <label for="bonus_credit" class="col-sm-2 col-form-label"><?= $language::translate('Bonus Credit') ?></label>
                     <div class="col-sm-10">
                         <input type="number" step=0.01 name="bonus_credit" class="form-control" id="bonus_credit" value="<?= $customerGroup->getBonusCredit() ?>">
                     </div>
                 </div>
               </div>


               <div class="tab-pane fade" id="rounded-pills-icon-tax" role="tabpanel" aria-labelledby="rounded-pills-icon-tax-tab">


                 <div class="form-group row">
                     <label for="bonus_credit" class="col-sm-2 col-form-label"><?= $language::translate('Tax') ?> (%)</label>
                     <div class="col-sm-10">
                         <input type="number" step=0.01 name="tax_rate" class="form-control" id="tax_rate" value="<?= $customerGroup->getTaxRate() ?>">
                     </div>
                 </div>
               </div>

           </div>
       </div>
<button style="    width: 100%;    margin-top: 23px;" type="submit" class="btn btn-primary"><?= $language::translate('Save') ?></button>
   </form>
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


<style media="screen">
.rounded-pills-icon .nav-pills .nav-link.active, .rounded-pills-icon .nav-pills .show>.nav-link {
box-shadow: 0px 5px 15px 0px rgb(0 0 0 / 30%);
background-color: #4361ee;
}
</style>

</body>
</html>
