<?php

use Pemm\Core\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\SmsProvider;
use Pemm\Model\Setting;
use Symfony\Component\HttpFoundation\RedirectResponse;

global $container;

/* @var Request $request */
$request = $container->get('request');
$setting = (new Setting())->find(1);

/* @var Session $session */
$session = $container->get('session');

$language = $container->get('language');

$customerGroup = $container->has('detailId') ? (new SmsProvider())->find($container->get('detailId')) : new SmsProvider();

if ($request->isMethod('post')) {

try {

$new = empty($customerGroup->getId());

$customerGroup
->setName($request->request->get('name'))
->setHeader($request->request->get('header'))
->setToken($request->request->get('token'))
->setToken2($request->request->get('token2'))
->setNumber($request->request->get('number'))
->setStatus($request->request->getInt('status'));

$customerGroup->store();

$session->getFlashBag()->add('success', 'Success');

if ($new) (new RedirectResponse('/admin/sms_providers/detail/' . $customerGroup->getId() . '?confirm_message=Success'))->send();

} catch (\Exception $exception) {
$session->getFlashBag()->add('danger', $exception->getMessage());
}

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title><?= $language::translate('Sms Provider') ?> - <?= SITE_NAME ?> </title>
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
                   <h4><?= $language::translate('Sms Provider') ?></h4>
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



               <div class="form-group row">
                   <label for="name" class="col-sm-2 col-form-label"><?= $language::translate('Name') ?></label>
                   <div class="col-sm-10">
                       <input type="text" name="name" class="form-control" id="name" value="<?= $customerGroup->getName() ?>">
                   </div>
               </div>
               <div class="form-group row">
                   <label for="number" class="col-sm-2 col-form-label"><?= $language::translate('Number') ?></label>
                   <div class="col-sm-10">
                       <input type="text" name="number" class="form-control" id="number" value="<?= $customerGroup->getNumber() ?>">
                   </div>
               </div>

              <div class="form-group row">
                  <label for="header" class="col-sm-2 col-form-label"><?= $language::translate('Header') ?>  (<?= $language::translate('if any') ?>)</label>
                  <div class="col-sm-10">
                      <input type="text" name="header" class="form-control" id="header" value="<?= $customerGroup->getHeader() ?>">
                  </div>
              </div>

              <div class="form-group row">
              <label for="token" class="col-sm-2 col-form-label"><?= $language::translate('Token') ?></label>
              <div class="col-sm-10">
              <input type="text" name="token" class="form-control" id="token" value="<?= $customerGroup->getToken() ?>">
              </div>
              </div>

              <div class="form-group row">
              <label for="token2" class="col-sm-2 col-form-label"><?= $language::translate('Token') ?> 2</label>
              <div class="col-sm-10">
              <input type="text" name="token2" class="form-control" id="token2" value="<?= $customerGroup->getToken2() ?>">
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
