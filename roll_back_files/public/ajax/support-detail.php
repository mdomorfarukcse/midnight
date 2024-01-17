<?php

use Pemm\Core\Container;
use Pemm\Model\Support;

/* @var Container $container */
global $container;

/* @var Support $support */
$support = $container->get('support');
$today = new DateTime();
$yesterday = new DateTime('yesterday');

$lastMessage = $support->getLastMessage();

$supportDate = DateTime::createFromFormat('Y-m-d H:i:s', $lastMessage->getCreatedAt());

$date = $supportDate->format('d M Y');
if ($supportDate->format('Y-m-d') == $today->format('Y-m-d')) {
    $date = 'Today';
} elseif ($supportDate->format('Y-m-d') == $yesterday->format('Y-m-d')) {
    $date = 'Yesterday';
}
?>

<div class="media media-chat media-chat-reverse"><img class="avatar" src="<?= $lastMessage->getAvatar() ?>" alt="...">
    <div class="media-body">
        <?php
        if (!empty($lastMessage->getText())) { ?>
            <p><?= $lastMessage->getText() ?></p>
        <?php }
        if ($lastMessage->getFile() != null) { ?>
            <br>
            <a onclick="supportFileDownload('/panel/file/support/<?= $lastMessage->getId() ?>/download')"
               class="btn btn-sq btn-success"><i
                        class="ti-download"></i><br> <?= $lastMessage->getFile() ?> </a><br>
            <?php
        } ?>
        <p class="meta">
            <time style="    color: #9b9b9b;" datetime="2018"><?= $date ?>, <?= $supportDate->format('H:i') ?></time>
        </p>
    </div>
</div>
