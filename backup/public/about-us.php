<?php

use Pemm\Core\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Customer;
use Pemm\Core\Language;

global $container;

/* @var Request $request */
$request = $container->get('request');

/* @var Language $language */
$language = $container->get('language');

/* @var Session $session */
$session = $container->get('session');

/* @var Customer $customer */
$customer = $container->get('customer');

if ($request->isMethod('post')) {

    try {

        /* @var UploadedFile $avatar */
        if (!empty($avatar = $request->files->get('avatar'))) {
            $_image = $avatar->move($_SERVER['DOCUMENT_ROOT'] . '/images/customer/avatar/', $customer->getId(). '-avatar.' . $avatar->getClientOriginalExtension());
            $customer->setAvatar($_image->getBasename());
        }

        $customer
            ->setEmail($request->request->get('email'))
            ->setFirstName($request->request->get('first_name'))
            ->setLastName($request->request->get('last_name'))
            ->setContactNumber($request->request->get('contact_number'));

        if (!empty($password = $request->request->get('password'))) {
            $customer->setPassword(password_hash($password, PASSWORD_DEFAULT));
        }

        $customer->save();

        $session->getFlashBag()->add('success', 'Success');

    } catch (\Exception $exception) {
        $session->getFlashBag()->add('danger', $exception->getMessage());
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('My Profile') ?> - <?= SITE_NAME ?> </title>
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
   	<?php include("ust2.php")?>
	 <div class="sub-header-container">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>

            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">

                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);"><?= $language::translate('Dashboard') ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><span><?= $language::translate('My Profile') ?></span></li>
                            </ol>
                        </nav>

                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
	<?php include("ust.php")?>
         <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">
                    <div class="col-xl-6 col-lg-6 col-sm-12 ">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">My Profile</h5>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <?php
                                    foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                                        <div class="alert alert-<?= $type ?>">
                                            <?php foreach ($messages as $message) { echo $message;} ?>
                                        </div>
                                    <?php }
                                    ?>
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="email" class="form-control" id="email" value="<?= $customer->getEmail() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="first_name" class="col-sm-2 col-form-label">First Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="first_name" class="form-control" id="first_name" value="<?= $customer->getFirstName() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="last_name" class="col-sm-2 col-form-label">Last Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="last_name" class="form-control" id="last_name" value="<?= $customer->getLastName() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="contact_number" class="col-sm-2 col-form-label">Contact Number</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="contact_number" class="form-control" id="contact_number" value="<?= $customer->getContactNumber() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                                        <div class="col-sm-10">
                                            <input type="password" name="password" class="form-control" id="password" value="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="imgInp" class="col-sm-2 col-form-label"> <?= $language::translate('Image') ?></label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                            <span class="input-group-btn">
                                                <span class="btn btn-default btn-file">
                                                    Browse… <input type="file" id="imgInp" name="avatar">
                                                </span>
                                            </span>
                                                <input type="text" class="form-control" readonly>
                                            </div>
                                            <img id="img-upload"src="<?= $customer->getAvatar(true) ?>" style="width: 100px"/>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </div>
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
<script>
    $(document).ready( function() {

        $(document).on('change', '.btn-file :file', function() {
            var input = $(this),
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [label]);
        });

        $('.btn-file :file').on('fileselect', function(event, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = label;
            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }

        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#img-upload').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp").change(function(){
            readURL(this);
        });
    });
</script>
<style>
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }

    #img-upload{
        width: 100%;
    }
</style>
</html>
