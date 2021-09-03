<?php defined('ALTUMCODE') || die() ?>

<ul class="mt-5 nav nav-custom lol">
    <?php if(in_array('settings', $data->notification->settings->enabled_methods)): ?>
        <li class="nav-item">
            <a href="<?= url('notification/' . $data->notification->notification_id . '/settings') ?>" class="nav-link px-4 border-round-1 <?= $data->method == 'settings' ? 'active' : null ?>">
                 <?= $this->language->notification->settings->link ?>
            </a>
        </li>
    <?php endif ?>

    <?php if(in_array('statistics', $data->notification->settings->enabled_methods)): ?>
        <li class="nav-item">
            <a href="<?= url('notification/' . $data->notification->notification_id . '/statistics') ?>" class="nav-link  px-4 border-round-2 <?= $data->method == 'statistics' ? 'active' : null ?>">
                <?= $this->language->notification->statistics->link ?>
            </a>
        </li>
    <?php endif ?>

    <?php if(in_array('data', $data->notification->settings->enabled_methods)): ?>
    <li class="nav-item">
        <a href="<?= url('notification/' . $data->notification->notification_id . '/data') ?>" class="nav-link <?= $data->method == 'data' ? 'active' : null ?>">
            <i class="fa fa-fw fa-sm fa-database mr-1"></i> <?= $this->language->notification->data->link ?>
        </a>
    </li>
    <?php endif ?>
</ul>
