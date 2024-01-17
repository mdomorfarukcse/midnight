<?php

use Pemm\Core\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Customer;
use Pemm\Model\Setting;
use Pemm\Model\CustomerGroup;
use Symfony\Component\HttpFoundation\RedirectResponse;

global $container;

/* @var Request $request */
$request = $container->get('request');
$setting = (new Setting())->find(1);

/* @var Session $session */
$session = $container->get('session');

$language = $container->get('language');

$customerGroups = (new CustomerGroup())->findAll();

$customer = $container->has('detailId') ? (new Customer())->find($container->get('detailId')) : new Customer();

if ($request->isMethod('post')) {

    try {

        foreach ($customerGroups as $_customerGroup) {
            if ($request->request->get('customer_group') == $_customerGroup->getId()) {
                $customer->setCustomerGroup($_customerGroup);
                break;
            }
        }

        $new = empty($customer->getId());

        $customer
            ->setEmail($request->request->get('email'))
            ->setFirstName($request->request->get('first_name'))
            ->setLastName($request->request->get('last_name'))
            ->setContactNumber($request->request->get('contact_number'))
            ->setCredit($request->request->get('credit'))
            ->setAllowLogin($request->request->getInt('allow_login'))
            ->setStatus($request->request->getInt('status'))
            ->setCountry($request->request->get('country'))
            ->setCompanyName($request->request->get('companyName'))
            ->setVatNumber($request->request->get('vatNumber'))
            ->setCity($request->request->get('city'))
            ->setEvcnumber($request->request->get('evcnumber'))
            ->setEvcCredit($request->request->get('evccredit'))
            ->setAddress($request->request->get('address'));

        if (!empty($password = $request->request->get('password'))) {
            $customer->setPassword(password_hash($password, PASSWORD_DEFAULT));
        }

        $customer->save();

        $session->getFlashBag()->add('success', 'Success');

        if ($new) {
            (new RedirectResponse('/admin/currency/detail/' . $customer->getId() . '?confirm_message=Success'))->send();
        }

    } catch (\Exception $exception) {
        $session->getFlashBag()->add('danger', $exception->getMessage());
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('Customer') ?> - <?= SITE_NAME ?> </title>
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

                <div class="row layout-top-spacing">
                    <div class="col-xl-8 col-lg-8 col-sm-12 ">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= $language::translate('Customers') ?></h5>
                                <form action="" method="post">
                                    <?php
                                    foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                                        <div class="alert alert-<?= $type ?>">
                                            <?php foreach ($messages as $message) { echo $message;} ?>
                                        </div>
                                    <?php }
                                    ?>
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-2 col-form-label"><?= $language::translate('E-mail') ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="email" class="form-control" id="email" value="<?= $customer->getEmail() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="first_name" class="col-sm-2 col-form-label"><?= $language::translate('First name') ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="first_name" class="form-control" id="first_name" value="<?= $customer->getFirstName() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="last_name" class="col-sm-2 col-form-label"><?= $language::translate('Last name') ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="last_name" class="form-control" id="last_name" value="<?= $customer->getLastName() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="contact_number" class="col-sm-2 col-form-label"><?= $language::translate('Contact Number') ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="contact_number" class="form-control" id="contact_number" value="<?= $customer->getContactNumber() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="contact_number" class="col-sm-2 col-form-label"> <?= $language::translate('Company Name') ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="companyName" class="form-control" id="companyName" value="<?= $customer->getCompanyName() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="contact_number" class="col-sm-2 col-form-label"> <?= $language::translate('Vat Number') ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="vatNumber" class="form-control" id="vatNumber" value="<?= $customer->getVatNumber() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="credit" class="col-sm-2 col-form-label"><?= $language::translate('Credit') ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="credit" class="form-control" id="credit" value="<?= $customer->getCredit() ?>">
                                        </div>
                                    </div>
                                    <!--
                                    <div class="form-group row">
                                        <label for="credit" class="col-sm-2 col-form-label">EVC <?= $language::translate('Credit') ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="evccredit" class="form-control" id="evccredit" value="<?= $customer->getEvcCredit() ?>">
                                        </div>
                                    </div> -->
                                    <div class="form-group row">
                                        <label for="country" class="col-sm-2 col-form-label"><?= $language::translate('Country') ?></label>
                                        <div class="col-sm-10">
                                          <select name="country" class="form-control" required>
                                              <option value="" ><?= $language::translate('Select Country') ?></option>

                                              <?php
                                                  foreach ((new Pemm\Model\Country())->findAll() as $country) {
                                                    echo '<option value="'.$country->getId().'" '.(($customer->getCountry() == $country->getId()) ? 'selected':'').'>'.$country->getName().'</option>';
                                                  }
                                               ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="city" class="col-sm-2 col-form-label"><?= $language::translate('Select City') ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="city" class="form-control" id="city" value="<?= $customer->getCity() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="evcnumber" class="col-sm-2 col-form-label"><?= $language::translate('Evc Number') ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" id="evcnumber" name="evcnumber" class="form-control" id="evcnumber" value="<?= $customer->getEvcnumber() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="address" class="col-sm-2 col-form-label"><?= $language::translate('Address') ?></label>
                                        <div class="col-sm-10">
                                          <textarea name="address" class="form-control"><?= $customer->getAddress() ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="customer-group" class="col-sm-2 col-form-label"><?= $language::translate(
                                                'Customer Group'
                                            ) ?></label>
                                        <div class="col-sm-10">
                                            <select name="customer_group" class="form-control" required>
                                                <option value=""><?= $language::translate('Select Customer Group') ?></option>

                                                <?php
                                                foreach ($customerGroups as $customerGroup) {
                                                    echo '<option value="' . $customerGroup->getId(
                                                        ) . '" ' . (($customerGroup->getId() == $customer->getCustomerGroup()->getId()
                                                        ) ? 'selected' : '') . '>' . $customerGroup->getName(
                                                        ) . '</option>';
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password" class="col-sm-2 col-form-label"><?= $language::translate('Password') ?></label>
                                        <div class="col-sm-10">
                                            <input type="password" name="password" class="form-control" id="password" value="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-2"><?= $language::translate('Allow Login') ?></div>
                                        <div class="col-sm-10">
                                            <div class="form-check">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" value="1" name="allow_login" <?= !$customer->getAllowLogin() ?: 'checked'; ?> data-toggle="toggle">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-2"><?= $language::translate('Status') ?></div>
                                        <div class="col-sm-10">
                                            <div class="form-check">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" value="1" name="status" <?= !$customer->getStatus() ?: 'checked'; ?> data-toggle="toggle">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary"><?= $language::translate('Save') ?></button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-sm-12 ">
                        <div class="card">
                            <div id="evcFailBody" style="display:none;" class="card-body">
                                <div class="alert alert-danger" id="evcFailMessage"></div>
                            </div>
                            <div id="evcCardBody" style="display:none;" class="card-body">
                                <h5 class="card-title">Evc <?= $language::translate('System Control') ?></h5>
                                <form action="" method="post">
                                    <?php
                                    foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                                        <div class="alert alert-<?= $type ?>">
                                            <?php foreach ($messages as $message) { echo $message;} ?>
                                        </div>
                                    <?php }
                                    ?>


                                    <div class="form-group row">
                                        <label for="email" class="col-sm-4 col-form-label"><?= $language::translate('Evc Number Status') ?></label>
                                        <div class="col-sm-8">
                                            <b id="evcNumberStatus">?</b>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-sm-4 col-form-label"><?= $language::translate('Is Reseller Customer') ?></label>
                                        <div class="col-sm-8">
                                            <div id="evcResellerStatus">?</div>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="email" class="col-sm-4 col-form-label"><?= $language::translate('Current Evc Balance') ?></label>
                                        <div class="col-sm-8">
                                            <b id="evcBalance">?</b> <?= $language::translate('Credit') ?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-sm-4 col-form-label"><?= $language::translate('Add Balance') ?> (+)</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="addBalanceValue" value="">
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="button" onclick="addBalance();" class="btn btn-primary"><?= $language::translate('Add') ?>+</button>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label for="first_name" class="col-sm-4 col-form-label"> <?= $language::translate('Update Balance') ?></label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="updateBalanceValue" value="">
                                            <small> <?= $language::translate('Current Balance Delete and update') ?></small>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="button" onclick="updateBalance();" class="btn btn-primary"><?= $language::translate('Update') ?></button>
                                        </div>
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
     <script type="text/javascript">
         $(function(){
             $("#evcCardBody").show();
             $("#evcFailBody").hide();
             checkEvcCustomerStatus();
         });


         function failEvcPanel(message) {
             $("#evcCardBody").hide();
             $("#evcFailBody").show();
             $("#evcFailMessage").html(message);

             }




         function makeCustomer() {
             var evcnumber = $("#evcnumber").val();

             $.ajax({
                 url: "<?= SITE_URL ?>/evc/api.php?islem=evcAddCustomerReseller&customer="+evcnumber,
                 type: "GET",
                 data: null,
                 dataType: "json",
                 success: function (response) {
                     if(response.status=="OK"||response.status=="ok") {
                         $("#evcCardBody").show();
                         $("#evcFailBody").hide();
                         $("#evcFailMessage").html("");
                         checkEvcCustomerStatus();
                     }else{
                         failEvcPanel("Failed");
                     }
                 },
                 error: function (xhr, status) {
                     alert("error");
                 }
             });

          }


         function updateBalance() {
             var evcnumber = $("#evcnumber").val();
             var credit = $("#updateBalanceValue").val();

             $.ajax({
                 url: "<?= SITE_URL ?>/evc/api.php?islem=updateBalance&credits="+credit+"&customer="+evcnumber,
                 type: "GET",
                 data: null,
                 dataType: "json",
                 success: function (response) {
                     if(response.status=="OK"||response.status=="ok") {
                         $("#evcBalance").html(response.balance);
                         checkEvcBalance();
                         $("#updateBalanceValue").val("");
                     }else{
                         failEvcPanel("<b style='color:red'>We Can't process, please check your api informations</b>");
                     }
                 },
                 error: function (xhr, status) {
                     alert("error");
                 }
             });

         }

         function addBalance() {
             var evcnumber = $("#evcnumber").val();
             var credit = $("#addBalanceValue").val();

             $.ajax({
                 url: "<?= SITE_URL ?>/evc/api.php?islem=addBalance&credits="+credit+"&customer="+evcnumber,
                 type: "GET",
                 data: null,
                 dataType: "json",
                 success: function (response) {
                     if(response.status=="OK"||response.status=="ok") {
                         $("#evcBalance").html(response.balance);
                         checkEvcBalance();
                         $("#addBalanceValue").val("");
                     }else{
                         failEvcPanel("<b style='color:red'>We Can't process, please check your api informations</b>");
                     }
                 },
                 error: function (xhr, status) {
                     alert("error");
                 }
             });

         }

         function checkEvcBalance() {
             var evcnumber = $("#evcnumber").val();

             $.ajax({
                 url: "<?= SITE_URL ?>/evc/api.php?islem=checkBalance&customer="+evcnumber,
                 type: "GET",
                 data: null,
                 dataType: "json",
                 success: function (response) {
                     if(response.status=="OK"||response.status=="ok") {
                         $("#evcBalance").html(response.balance);

                     }else{
                         failEvcPanel("<b style='color:red'>We Can't get customer balance, please check your api informations</b>");
                     }
                 },
                 error: function (xhr, status) {
                     alert("error");
                 }
             });

         }

         function checkEvcResellerStatus(){
             var evcnumber = $("#evcnumber").val();

             $.ajax({
                 url: "<?= SITE_URL ?>/evc/api.php?islem=evcResellerCustomerControl&customer="+evcnumber,
                 type: "GET",
                 data: null,
                 dataType: "json",
                 success: function (response) {
                     if(response.status=="OK"||response.status=="ok") {
                         $("#evcResellerStatus").html("<b style='color:green'>Reseller Customer ("+evcnumber+")</b>");
                         checkEvcBalance();
                     }else{
                         failEvcPanel("<b style='color:red'>Not Reseller Customer.</b>"+"<button type='button' onclick='makeCustomer();' style='margin-left: 50px;' class='btn btn-primary'>Make Reseller Customer</button>");
                     }
                 },
                 error: function (xhr, status) {
                     alert("error");
                 }
             });

         }



         function checkEvcCustomerStatus(){
             var evcnumber = $("#evcnumber").val();

             $.ajax({
                 url: "<?= SITE_URL ?>/evc/api.php?islem=evcNumberControl&customer="+evcnumber,
                 type: "GET",
                 data: null,
                 dataType: "json",
                 success: function (response) {
                    if(response.status=="OK"||response.status=="ok") {
                        checkEvcResellerStatus();
                        $("#evcNumberStatus").html("<b style='color:green'>Valid ("+evcnumber+")</b>");
                        }else{
                        failEvcPanel("<b style='color:red'>Evc Number Invalid</b>");
                        }
                 },
                 error: function (xhr, status) {
                     alert("error");
                 }
             });

         }
     </script>

	</body>
</html>
