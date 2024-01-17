<?php

use Pemm\Core\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Category;
use Pemm\Model\Helper;
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

/* @var Request $request */
$request = $container->get('request');

/* @var Session $session */
$session = $container->get('session');

$language = $container->get('language');

$brand = $container->has('detailId') ? (new Category())->find($container->get('detailId')) : new Category();

$types = (new Category())->findBy(['filter' => ['parent_id' => 0, 'is_active' => 1]]);

if($container->has('detailId')) {

        $brands = (new Category())->findBy(['filter' => ['parent_id' => $brand->getParent()->getParent()->getParentId(), 'is_active' => 1, 'type' => 'brand']]);
        $models = (new Category())->findBy(['filter' => ['parent_id' => $brand->getParent()->getParentId(), 'is_active' => 1, 'type' => 'model']]);
}

if ($request->isMethod('post')) {

    $sef = Helper::sef($request->request->get('name'));

    /* @var UploadedFile $image */
    if (!empty($image = $request->files->get('image'))) {
        $_image = $image->move($_SERVER['DOCUMENT_ROOT'] . '/images/category/', 'brand-' . $sef . '-' . time() .'.' . $image->getClientOriginalExtension());
        $brand->setImage($_image->getBasename());
    }

    try {

        $new = empty($brand->getId());

        $brand
            ->setType('generation')
            ->setParentId($request->request->get('parent_id'))
            ->setSlug($sef)
            ->setName($request->request->get('name'))
            ->setStatus($request->request->getInt('status'));

        $brand->store();

        $session->getFlashBag()->add('success', 'Success');

        if ($new) header('location: /admin/vehicle/years/detail/' . $brand->getId() . '?confirm_message=Success');

    } catch (\Exception $exception) {
        print_r($exception);die;
        $session->getFlashBag()->add('danger', $exception->getMessage());
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $language::translate('Years') ?> - <?= SITE_NAME ?> </title>
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
                            <h5 class="card-title"> <?= $language::translate('Years') ?></h5>
                            <form action="" method="post" enctype="multipart/form-data" style="width: 100%">
                                <?php
                                foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                                    <div class="alert alert-<?= $type ?>">
                                        <?php foreach ($messages as $message) { echo $message;} ?>
                                    </div>
                                <?php }
                                ?>
                                <div class="form-group row">
                                    <label for="parent_id" class="col-sm-2 col-form-label"> <?= $language::translate('Categories') ?></label>
                                    <div class="col-sm-10">
                                        <select name="parent_id" id="status" class="form-control" onchange="getSubCategoriesForSelect('category', this)" style="width: 50%">
                                            <option value=""><?= $language::translate('Select Category') ?></option>
                                            <?php
                                            /* @var Category $type */
                                            foreach ($types as $key => $type) {
                                                if($container->has('detailId')) {?>
                                                <option value="<?= $type->getId() ?>"<?= ($type->getId() == $brand->getParent()->getParent()->getParentId() ? 'selected' : '') ?>><?= $type->getName() ?></option>
                                            <?php } else { ?>
                                                    <option value="<?= $type->getId() ?>"><?= $type->getName() ?></option>
                                                <?php }} ?>
                                        </select>
                                    </div>
                                </div>

                                <?php
                                foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                                    <div class="alert alert-<?= $type ?>">
                                        <?php foreach ($messages as $message) { echo $message;} ?>
                                    </div>
                                <?php }
                                ?>
                                <div class="form-group row">
                                    <label for="parent_id" class="col-sm-2 col-form-label"> <?= $language::translate('Brand') ?></label>
                                    <div class="col-sm-10">
                                        <select name="parent_id" id="brands" class="form-control"   onchange="getSubCategoriesForSelect('brand', this)" style="width: 50%">
                                            <option value=""><?= $language::translate('Select Brand') ?></option>
                                            <?php
                                            if($container->has('detailId')) {
                                                /* @var Category $type */
                                                foreach ($brands as $key => $type) {?>
                                                    <option value="<?= $type->getId() ?>"<?= ($type->getId() == $brand->getParent()->getParentId() ? 'selected' : '') ?>><?= $type->getName() ?></option>
                                                <?php }  }?>
                                        </select>
                                    </div>
                                </div>

                                <?php
                                foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                                    <div class="alert alert-<?= $type ?>">
                                        <?php foreach ($messages as $message) { echo $message;} ?>
                                    </div>
                                <?php }
                                ?>
                                <div class="form-group row">
                                    <label for="parent_id" class="col-sm-2 col-form-label"> <?= $language::translate('Model') ?></label>
                                    <div class="col-sm-10">
                                        <select name="parent_id" id="models" class="form-control" style="width: 50%">
                                            <option value=""><?= $language::translate('Select Model') ?></option>
                                            <?php
                                            if($container->has('detailId')) {
                                                /* @var Category $type */
                                                foreach ($models as $key => $type) {?>
                                                    <option value="<?= $type->getId() ?>"<?= ($type->getId() == $brand->getParentId() ? 'selected' : '') ?>><?= $type->getName() ?></option>
                                                <?php }  }?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="parent_id" class="col-sm-2 col-form-label"> <?= $language::translate('Name') ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="name" class="form-control" id="name" value="<?= $brand->getName() ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                  <div class="col-sm-2"><?= $language::translate('Status') ?></div>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="1" name="status" <?= !$brand->getStatus() ?: 'checked'; ?> data-toggle="toggle">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" style="width: 100%">Save</button>
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

    function getSubCategoriesForSelect(type, element) {
            if ($(element).val()) {
                $('button').prop('disabled', true);
                $.ajax({
                    url: '/ajax/admin/category/' + $(element).val() + '/get-sub-categories-for-select',
                    success: function (response) {
                        if (response.success) {
                            pushAfterSelectCategory(type, response.result)
                        }
                    },
                    error: function (error) {
                    }
                })
                $('button').prop('disabled', false);
            }
    }

    function pushAfterSelectCategory(type, result)
    {
        if(type == 'category') {

            var selectOptionsHtml = '<option value="">Select Brand</option>';

            result.forEach(function (data) {
                selectOptionsHtml += '<option value="' + data.id + '">' + data.name + '</option>\n'
            });

            $('#brands').html(selectOptionsHtml);
        }

        if(type == 'brand') {

            var selectOptionsHtml = '<option value="">Select Model</option>';

            result.forEach(function (data) {
                selectOptionsHtml += '<option value="' + data.id + '">' + data.name + '</option>\n'
            });

            $('#models').html(selectOptionsHtml);
        }
    }

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
