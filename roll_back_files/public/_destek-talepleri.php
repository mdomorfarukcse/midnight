<?php

use Pemm\Core\Container;
use Pemm\Model\Customer;
use Pemm\Model\Support;
use Pemm\Model\Setting;

global $container;
$setting = (new Setting())->find(1);

/* @var Customer $customer */
$customer = $container->get('customer');

$language = $container->get('language');

$supports = (new Support())->findBy([
    'filter' => ['customer_id' => $customer->getId(), 'id' => 108],
    'order' => ['field' => 'created_at', 'sort' => 'DESC']
]);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $language::translate('Supports') ?> - <?= SITE_NAME ?> </title>
    <?php include("css.php") ?>
    <link href="\assets\css\scrollspyNav.css" rel="stylesheet" type="text/css">
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link href="\assets\css\apps\mailing-chat.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php include("ust2.php") ?>

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
        <?php include("ust.php") ?>
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="chat-section layout-top-spacing">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">


                            <a class="btn   btn-outline-primary btn-block mb-2 mr-2" data-toggle="modal" data-target="#createSupport"> <?= $language::translate('Create support request') ?></a>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12">

                            <div class="chat-system">
                                <div class="hamburger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu mail-menu d-lg-none">
                                        <line x1="3" y1="12" x2="21" y2="12"></line>
                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                        <line x1="3" y1="18" x2="21" y2="18"></line>
                                    </svg></div>
                                <div class="user-list-box">
                                    <div class="search">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg>
                                        <input type="text" class="form-control" placeholder="<?= $language::translate('Search') ?>">
                                    </div>
                                    <div class="people">
                                        <?php
                                        if (!empty($supports)) {
                                            /* @var Support $support */
                                            foreach ($supports as $support) { ?>
                                                <div class="person" data-id="<?= $support->getId() ?>" data-chat="support-<?= $support->getId() ?>">
                                                    <div class="user-info">
                                                        <div class="f-head">
                                                            <img src="/images/admin/avatar/admin.png" alt="avatar">
                                                        </div>
                                                        <div class="f-body">
                                                            <div class="meta-info">
                                                                <span class="user-name"><?= $support->getSubject() ?></span>
                                                                <span class="user-meta-time"><?= $support->getSupportDateOrTime() ?></span>
                                                            </div>
                                                            <span class="preview"><?= $support->getStatus() ?></span>
                                                            <?php if ($support->getAdministratorRead()) { ?>
                                                                <button class="btn btn-success btn-sm">Admin Read</button>
                                                            <?php } else { ?>
                                                                <button class="btn btn-danger btn-sm">Admin UnRead</button>
                                                            <?php } ?>
                                                            <span alt="<?= $support->getVehicle() ?>" title="<?= $support->getVehicle() ?>" class="preview vehiclename"><?= $support->getVehicle() ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="chat-box">

                                    <div class="chat-not-selected">
                                        <p>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square">
                                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                            </svg> <a href="#" data-toggle="modal" data-target="#createSupport"> <?= $language::translate('Click to Create a Support Request') ?> </a>
                                        </p>
                                        <div class="modal fade" id="createSupport" tabindex="-1" role="dialog" aria-labelledby="createSupportlLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="" method="post">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"><?= $language::translate('Create support request') ?></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label> <?= $language::translate('Support subject') ?> </label>
                                                                <input id="support-title" type="text" class="form-control" name="subject" placeholder="<?= $language::translate('Support subject') ?>" required="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label> <?= $language::translate('Select Vehicle') ?> </label>
                                                                <select name="vehicle" class="form-control">
                                                                    <option value=""><?= $language::translate('-- None --') ?></option>
                                                                    <?php

                                                                    use Pemm\Model\CustomerVehicle;

                                                                    $newCustomerVehicles = (new CustomerVehicle())->findBy(['filter' => ['customer_id' => $customer->getId(), 'status' => 'pending', 'deleted' => 0]]);
                                                                    foreach ($newCustomerVehicles as $_customerVehicle) {
                                                                    ?>
                                                                        <option value="<?= $_customerVehicle->vehicle->getFullName() ?>"><?= $_customerVehicle->vehicle->getFullName() ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label> <?= $language::translate('Message') ?> </label>
                                                                <textarea id="support-text" placeholder="<?= $language::translate('Message') ?>" class="form-control" name="text" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $language::translate('Cancel') ?></button>
                                                            <button type="submit" class="btn btn-primary"><?= $language::translate('Create') ?></button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="chat-box-inner">
                                        <div class="chat-meta-user">
                                            <div class="current-chat-user-name"><span><img src="\assets\img\90x90.jpeg" alt="dynamic-image"><span class="name"></span><span style="font-size: 13px;" class="vehicle"></span></span></div>

                                        </div>
                                        <div class="chat-conversation-box">
                                            <div id="chat-conversation-box-scroll" class="chat-conversation-box-scroll"></div>
                                        </div>
                                        <div class="chat-footer">
                                            <div class="chat-input">
                                                <form class="chat-form" action="javascript:void(0);">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square">
                                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                    </svg>
                                                    <input type="file" id="chat_file">
                                                    <input style="padding: 12px 6px 15px 65px;" id="message" data-id="" data-who="customer" type="text" class="mail-write-box form-control" placeholder="Message">
                                                </form>
                                            </div>
                                        </div>
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
        <style media="screen">
            .btn-outline-primary:hover {
                color: #fff !important;
                background-color: #161621;
                box-shadow: 0 10px 20px -10px #161621;
            }

            .btn-outline-primary {
                border: 1px solid #161621 !important;
                color: #161621 !important;
            }

            .btn-outline-primary:not(:disabled):not(.disabled).active,
            .btn-outline-primary:not(:disabled):not(.disabled):active,
            .show>.btn-outline-primary.dropdown-toggle {
                background-color: #161621;
                color: #fff !important;
            }
        </style>

        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="\assets\js\apps\mailbox-chat.js"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/12.0.0/classic/ckeditor.js"></script>
</body>

</html>