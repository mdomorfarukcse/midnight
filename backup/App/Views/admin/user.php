<?php

use Pemm\Core\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\User;
use Pemm\Model\Helper;
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

/* @var Request $request */
$request = $container->get('request');

/* @var Session $session */
$session = $container->get('session');

$language = $container->get('language');

$administrator = $container->has('detailId') ? (new User())->find($container->get('detailId')) : new User();

if ($request->isMethod('post')) {

    try {

        $sef = Helper::sef($request->request->get('first_name') . ' ' . $request->request->get('last_name'));

        /* @var UploadedFile $image */
        if (!empty($image = $request->files->get('avatar'))) {
            $_image = $image->move($_SERVER['DOCUMENT_ROOT'] . '/images/admin/avatar/', 'admin-' . $sef . '-' . time() . '.' . $image->getClientOriginalExtension());
            $administrator->setAvatar($_image->getBasename());
        }

        $new = empty($administrator->getId());

        $administrator
            ->setEmail($request->request->get('email'))
            ->setFirstName($request->request->get('first_name'))
            ->setLastName($request->request->get('last_name'))
            ->setContactNumber($request->request->get('contact_number'))
            ->setAllowLogin($request->request->getInt('allow_login'))
            ->setStatus($request->request->getInt('status'))
            ->setUserRole(1);

        if (!empty($password = $request->request->get('password'))) {
            $administrator->setPassword(password_hash($password, PASSWORD_DEFAULT));
        }

        $administrator->save();

        $session->getFlashBag()->add('success', 'Success');

        if ($new) header('location: /admin/user/detail/' . $administrator->getId() . '?confirm_message=Success');

    } catch (\Exception $exception) {
        $session->getFlashBag()->add('danger', $exception->getMessage());
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <title><?= $language::translate('Administrator') ?> - <?= SITE_NAME ?> </title>
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
                    <div class="col-xl-6 col-lg-6 col-sm-12 ">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= $language::translate('Administrator') ?></h5>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <?php
                                    foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                                        <div class="alert alert-<?= $type ?>">
                                            <?php foreach ($messages as $message) { echo $message;} ?>
                                        </div>
                                    <?php }
                                    ?>
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-2 col-form-label"> <?= $language::translate('Email') ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="email" class="form-control" id="email" value="<?= $administrator->getEmail() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="first_name" class="col-sm-2 col-form-label"> <?= $language::translate('First Name') ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="first_name" class="form-control" id="first_name" value="<?= $administrator->getFirstName() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="last_name" class="col-sm-2 col-form-label"> <?= $language::translate('Last Name') ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="last_name" class="form-control" id="last_name" value="<?= $administrator->getLastName() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="contact_number" class="col-sm-2 col-form-label"> <?= $language::translate('Contact Number') ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="contact_number" class="form-control" id="contact_number" value="<?= $administrator->getContactNumber() ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password" class="col-sm-2 col-form-label"> <?= $language::translate('Password') ?></label>
                                        <div class="col-sm-10">
                                            <input type="password" name="password" class="form-control" value="*************" id="password">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-2"> <?= $language::translate('Allow Login') ?></div>
                                        <div class="col-sm-10">
                                            <div class="form-check">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" value="1" name="allow_login" <?= !$administrator->getAllowLogin() ?: 'checked'; ?> data-toggle="toggle">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-2"> <?= $language::translate('Status') ?></div>
                                        <div class="col-sm-10">
                                            <div class="form-check">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" value="1" name="status" <?= !$administrator->getStatus() ?: 'checked'; ?> data-toggle="toggle">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="parent_id" class="col-sm-2 col-form-label"> <?= $language::translate('Avatar') ?></label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                            <span class="input-group-btn">
                                                <span class="btn btn-default btn-file">
                                                     <?= $language::translate('Browse') ?>... <input type="file" id="imgInp" name="avatar">
                                                </span>
                                            </span>
                                                <input type="text" class="form-control" readonly>
                                            </div>
                                            <img id="img-upload"src="<?= $administrator->getAvatar(true) ?>" style="width: 100px"/>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary"> <?= $language::translate('Save') ?></button>
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
