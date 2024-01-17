<?php

use Pemm\Core\Container;
use Pemm\Model\Customer;
use Pemm\Model\Support;
use Pemm\Model\Setting;
use Symfony\Component\HttpFoundation\Request;

global $container;
$setting = (new Setting())->find(1);

/** @var Request $request */
$request = $container->get('request');

$type = $request->query->has('type') ? $request->query->get('type') : 'inbox';
$read = $request->query->getInt('read');
$page = $request->query->getInt('page');

if ($page == 0) {
    $page = 1;
}

$language = $container->get('language');

$supportModel = Support::counter(null, $type);

$filter = [];

switch ($type) {
    case 'inbox':
        $filter['administrator_read'] = $read;
        $filter['type'] = 'customer';
        break;
    case 'open':
        $filter['first_question'] = 1;
        $filter['status'] = ['pending', 'answered'];
        break;
    case 'closed':
        $filter['first_question'] = 1;
        $filter['status'] = 'closed';
        break;
    default:
        break;
}

$filterModel = new Support();

$supports = $filterModel->findBy([
    'filter' => $filter,
    'order' => ['field' => 'created_at', 'sort' => 'DESC'],
    'pagination' => ['limit' => 4, 'page' => $page]
]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $language::translate('Support') ?> - <?= SITE_NAME ?> </title>
    <?php
    include("css.php") ?>
    <link href="<?= SITE_URL ?>\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\bootstrap-select\bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\bootstrap-toggle\bootstrap-toggle.min.css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\table\datatable\datatables.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\assets\css\forms\theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="<?= SITE_URL ?>\plugins\table\datatable\dt-global_style.css">
    <link rel="stylesheet" href="\assets/css/themify-icons.css">
    <link rel="stylesheet" href="\assets/css/ie7/ie7.css">
    <link rel="stylesheet" href="\plugins/font-icons/fontawesome/css/regular.css">
    <link rel="stylesheet" href="\plugins/font-icons/fontawesome/css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="\assets/css/destek.css">
</head>
<body>
<?php
include("header.php") ?>

<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">
    <?php
    include("sidebar.php") ?>
    <div id="content" class="main-content">
        <div class="layout-px-spacing">
            <div class="chat-section layout-top-spacing">
                <div class="row">
                    <!-- BEGIN NAV TICKET -->
                    <div class="col-md-3">
                        <div class="grid support">
                            <div class="grid-body">
                                <h2 style="text-align:center;"><img style="    width: 180px; "
                                                                    src="<?= $setting->getsiteUrl(
                                                                    ); ?>/assets/img/<?= $setting->getLogo2(); ?>"></h2>
                                <hr>
                                <ul>
                                    <li class="<?= empty($type) ? 'active' : '' ?>"><a
                                                href="?<?= http_build_query(['type' => 'inbox']
                                                ) ?>"><?= $language::translate('Inbox') ?><span
                                                    class="float-right"><?= $supportModel->getInboxSupportCount(
                                                ) ?></span></a></li>
                                    <li class="<?= $type == 'open' ? 'active' : '' ?>"><a
                                                href="?<?= http_build_query(['type' => 'open']
                                                ) ?>"><?= $language::translate('Open') ?> <?= $language::translate(
                                                'Supports'
                                            ) ?> <span class="float-right"><?= $supportModel->getOpenSupportCount(
                                                ) ?></span></a></li>
                                    <li class="<?= $type == 'closed' ? 'active' : '' ?>"><a
                                                href="?<?= http_build_query(['type' => 'closed']
                                                ) ?>"><?= $language::translate('Close') ?> <?= $language::translate(
                                                'Supports'
                                            ) ?><span class="float-right"><?= $supportModel->getClosedSupportCount(
                                                ) ?></span></a></li>
                                </ul>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="grid support-content">
                            <div class="grid-body">
                                <div class="row">
                                    <h2 class="col-6"><?= $language::translate('Support Requests') ?></h2>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-primary float-right"
                                                data-toggle="modal"
                                                data-target="#composeMailModal"><?= $language::translate(
                                                'Create support request'
                                            ) ?></button>
                                    </div>
                                </div>
                                <hr>
                                <?php
                                if ($type == 'inbox') { ?>
                                    <div class="btn-group">
                                        <a href="?<?= http_build_query(['type' => $type, 'read' => 1]) ?>"
                                           type="button"
                                           class="btn <?= $read == 1 ? 'btn-primary active' : 'btn-default' ?>"><?= $supportModel->getInboxReadSupportMessageCount(
                                            ) ?> <?= $language::translate('Read Messages') ?></a>
                                        <a href="?<?= http_build_query(['type' => $type, 'read' => 0]) ?>"
                                           type="button"
                                           class="btn <?= $read == 0 ? 'btn-primary active' : 'btn-default' ?>"><?= $supportModel->getInboxUnReadSupportMessageCount(
                                            ) ?> <?= $language::translate('Unread Messages') ?></a>
                                    </div>
                                <?php } ?>
                                <div class="padding"></div>

                                <div class="row">

                                    <div class="col-md-12">
                                        <ul class="list-group fa-padding">
                                            <?php

                                            if (!empty($supports)) {
                                                /* @var Support $support */
                                                foreach ($supports as $support) { ?>
                                                    <li class="list-group-item">
                                                        <div class="media">
                                                            <img style="width: 42px;" class="rounded-circle"
                                                                 src="<?= $support->getAvatar() ?>"/>
                                                            <div style="margin-left: 13px;" class="media-body">
                                                                <a href="/admin/ticket?ticket_id=<?= $support->getId() ?>">
                                                                    <strong><?= $support->getSubject() ?></strong>
                                                                    <?php
                                                                    if ($support->isOpen() && $type != 'inbox') { ?>
                                                                        <span class="badge badge-success"><?= $language::translate($support->getStatus()) ?></span>
                                                                        <?php
                                                                        if ($support->isCustomerRead()) { ?>
                                                                            <span class="badge badge-success"><?= $language::translate('Customer Read') ?></span>
                                                                            <?php
                                                                        } else { ?>
                                                                            <span class="badge badge-light"><?= $language::translate('Customer Unread') ?></span>
                                                                            <?php
                                                                        } ?>
                                                                    <?php
                                                                    } ?>
                                                                    <span class="number float-right"># <?= $support->getId() ?></span>
                                                                    <?php
                                                                        if ($type == 'inbox'){ ?>
                                                                            <p class="info"><?= $support->getText() ?> </p>
                                                                        <?php } else { ?>
                                                                            <p class="info"><?= $support->getLastMessage()->getText() ?> </p>
                                                                        <?php } ?>

                                                                    <p class="info">
                                                                        <i class="ti-user"></i> <?= $support->getCustomer()->getFirstName()  . ' '.$support->getCustomer()->getLastName() ?>

                                                                    <?php if($support->getVehicle()  != ""):?>
                                                                      <br>
                                                                     <i class="ti-car"></i> <?= $support->getVehicle() ?>
                                                                   <?php endif;?>
                                                                    <br><i class="ti-alarm-clock"></i> <?= $support->getSupportDateOrTime() ?> </p>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                        <?php
                                        if ($filterModel->queryTotalPage > 1) { ?>
                                            <nav style="margin-top: 33px;" aria-label="Page navigation example">
                                                <ul class="pagination justify-content-center">
                                                    <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                                                        <a class="page-link" href="?<?= http_build_query(
                                                            ['type' => $type, 'read' => $read, 'page' => 1]
                                                        ) ?>"
                                                           tabindex="-1"><?= $language::translate(
                                                                'Previous'
                                                            ) ?></a>
                                                    </li>
                                                    <?php
                                                    for ($i = 1; $i <= $filterModel->queryTotalPage; $i++) { ?>
                                                        <li class="page-item <?= $page == $i ? 'active' : '' ?>"><a
                                                                    class="page-link" href="?<?= http_build_query(
                                                                ['type' => $type, 'read' => $read, 'page' => $i]
                                                            ) ?>"><?= $i ?></a></li>
                                                    <?php
                                                    }
                                                    ?>
                                                    <li class="page-item <?= $page == $filterModel->queryTotalPage ? 'disabled' : '' ?>">
                                                        <a class="page-link" href="?<?= http_build_query(
                                                            [
                                                                'type' => $type,
                                                                'read' => $read,
                                                                'page' => $filterModel->queryTotalPage
                                                            ]
                                                        ) ?>"><?= $language::translate('Next') ?></a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        <?php
                                        }
                                        ?>


                                    </div>
                                    <!-- END TICKET CONTENT -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="composeMailModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel"><?= $language::translate(
                                                    'Create support request'
                                                ) ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label> <?= $language::translate('Support subject') ?> </label>
                                                <input id="support-title" type="text" class="form-control"
                                                       name="subject"
                                                       placeholder="<?= $language::translate('Support subject') ?>"
                                                       required="">
                                            </div>
                                            <div class="form-group">
                                                <label> <?= $language::translate('Select Customer') ?> </label>
                                                <select id="customer" name="customer" class="form-control">
                                                    <option value=""><?= $language::translate('-- None --') ?></option>
                                                    <?php

                                                    $customers = (new Customer())->findAll();
                                                    /** @var  Customer $customer */
                                                    foreach ($customers as $customer) { ?>
                                                        <option value="<?= $customer->getId(
                                                        ) ?>"><?= $customer->getFullName() ?></option>
                                                    <?php
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label> <?= $language::translate('Select Vehicle') ?> </label>
                                                <select id="vehicle" name="vehicle" class="form-control">
                                                    <option value=""><?= $language::translate('-- None --') ?></option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label> <?= $language::translate('Message') ?> </label>
                                                <textarea id="support-text"
                                                          placeholder="<?= $language::translate('Message') ?>"
                                                          class="form-control" name="text" required></textarea>
                                            </div>
                                            <div style="margin-bottom:15px;" class="form-group">
                                                <div class="d-flex">
                                                    <input type="file" class="form-control-file" name="file">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal"><?= $language::translate('Cancel') ?></button>
                                            <button type="submit" class="btn btn-primary"><?= $language::translate(
                                                    'Create'
                                                ) ?></button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <?php
        include("alt.php") ?>
    </div>
    <?php
    include("js.php") ?>
    <style media="screen">

    </style>

    <style media="screen">
    .btn-group>.btn-group:not(:first-child)>.btn, .btn-group>.btn:not(:first-child) {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        color: black;
    }
    </style>
    <script>
        $('select#customer').on('change', function () {
            $.ajax({
                method: 'GET',
                type: 'GET',
                url: '/ajax/admin/customer/vehicle/list-for-customer',
                data: {
                    customer_id: $(this).val()
                },
                success: function (response) {
                    var options = '<option value=""><?= $language::translate('-- None --') ?></option>';

                    response.forEach(function (vehicle) {
                        options += '<option value="' + vehicle.id + '">' + vehicle.fullname + '</option>'
                    });

                    $('select#vehicle').html(options);
                },
                error: function (xhr, status) {

                }
            });
        })
    </script>
</body>
</html>
