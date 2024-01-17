<?php

use Pemm\Core\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\ProductEvc;
use Pemm\Model\Currency;
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

/* @var Request $request */
$request = $container->get('request');

/* @var Session $session */
$session = $container->get('session');

/* @var Currency $currency */
$currency = $container->get('currency');

$language = $container->get('language');

$product = $container->has('detailId') ? (new ProductEvc())->find($container->get('detailId')) : new ProductEvc();

if ($request->isMethod('post')) {

    try {

        $new = empty($product->getId());

        $product
            ->setName($request->request->get('name'))
            ->setCredit($request->request->get('credit'))
            ->setCurrency($currency->get($request->request->get('currency_code')))
            ->setPrice($request->request->get('price'))
            ->setTaxRate($request->request->getInt('tax_rate'))
            ->setSortOrder($request->request->getInt('sort_order'))
            ->setDiscountedPrice(floatval($request->request->get('discount_price')))
            ->setDiscountStatus(@$request->request->getInt('discount_status'))
            ->setStatus(@$request->request->getInt('status'));

        $product->store();

        $session->getFlashBag()->add('success', 'Success');

        if ($new) header('location: /admin/product-evc/detail/' . $product->getId() . '?confirm_message=Success');

    } catch (\Exception $exception) {
        $session->getFlashBag()->add('danger', $exception->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $language::translate('Product') ?> - <?= SITE_NAME ?> </title>
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
                <div class="col-xl-12 col-lg-12 col-sm-12 ">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Evc <?= $language::translate('Product') ?></h5>
                            <form action="" method="post" style="width: 100%">
                                <?php
                                foreach ($container->get('session')->getFlashBag()->all() as $type => $messages) {?>
                                    <div class="alert alert-<?= $type ?>">
                                        <?php foreach ($messages as $message) { echo $message;} ?>
                                    </div>
                                <?php }
                                ?>
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label"> <?= $language::translate('Name') ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="name" class="form-control" id="name" value="<?= $product->getName() ?>" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="credit" class="col-sm-2 col-form-label"> <?= $language::translate('Credit') ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="credit" class="form-control" id="credit" value="<?= $product->getCredit() ?>" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="currency_code" class="col-sm-2 col-form-label"> <?= $language::translate('Currency') ?></label>
                                    <div class="col-sm-10">
                                        <select name="currency_code" class="form-control" id="currency_code" required>
                                            <option value=""><?= $language::translate('Select Currency') ?></option>
                                        <?php
                                        /* @var Currency $_currency */
                                        foreach ($currency->getList() as $_currency) {?>
                                            <option value="<?= $_currency->getCode() ?>" <?= (!(empty($product->getId())) && $product->getCurrency()->getCode() == $_currency->getCode()) ? 'selected' : '' ?>>
                                                <?= $_currency->getCode() ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="price" class="col-sm-2 col-form-label"> <?= $language::translate('Price') ?></label>
                                    <div class="col-sm-10">
                                        <input type="number" name="price" class="form-control" id="price" value="<?= $product->getPrice() ?>" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2"><?= $language::translate('Discount Status') ?></div>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="1" name="discount_status" <?= !$product->getDiscountStatus() ?: 'checked'; ?> data-toggle="toggle">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="discounted_price" class="col-sm-2 col-form-label"> <?= $language::translate('Discounted Price') ?></label>
                                    <div class="col-sm-10">
                                        <input type="number" name="discounted_price" class="form-control" id="discounted_price" value="<?= $product->getDiscountedPrice() ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tax_rate" class="col-sm-2 col-form-label"> <?= $language::translate('Tax Rate') ?></label>
                                    <div class="col-sm-10">
                                        <input type="number" name="tax_rate" class="form-control" id="tax_rate" value="<?= $product->getTaxRate() ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="sort_order" class="col-sm-2 col-form-label"> <?= $language::translate('Sort Order') ?></label>
                                    <div class="col-sm-10">
                                        <input type="number" name="sort_order" class="form-control" id="sort_order" value="<?= $product->getSortOrder() ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2"><?= $language::translate('Status') ?></div>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="1" name="status" <?= !$product->getStatus() ?: 'checked'; ?> data-toggle="toggle">
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
