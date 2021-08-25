<?php defined('ALTUMCODE') || die() ?>

<ul class="list-style-none m-0">

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->no_ads ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->no_ads ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->no_ads ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->removable_branding ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->removable_branding ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->removable_branding ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->custom_branding ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->custom_branding ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->custom_branding ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->campaigns_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->campaigns_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->campaigns_limit == -1): ?>
                <?= \Altum\Language::get()->global->plan_settings->unlimited_campaigns_limit ?>
            <?php else: ?>
                <?= sprintf(\Altum\Language::get()->global->plan_settings->campaigns_limit, '<strong>' . nr($data->plan_settings->campaigns_limit) . '</strong>') ?>
            <?php endif ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->notifications_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->notifications_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->notifications_limit == -1): ?>
                <?= \Altum\Language::get()->global->plan_settings->unlimited_notifications_limit ?>
            <?php else: ?>
                <?= sprintf(\Altum\Language::get()->global->plan_settings->notifications_limit, '<strong>' . nr($data->plan_settings->notifications_limit) . '</strong>') ?>
            <?php endif ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->notifications_impressions_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->notifications_impressions_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->notifications_impressions_limit == -1): ?>
                <?= \Altum\Language::get()->global->plan_settings->unlimited_notifications_impressions_limit ?>
            <?php else: ?>
                <?= sprintf(\Altum\Language::get()->global->plan_settings->notifications_impressions_limit, '<strong>' . nr($data->plan_settings->notifications_impressions_limit) . '</strong>') ?>
            <?php endif ?>
        </div>
    </li>

    <?php $enabled_notifications = array_filter((array) $data->plan_settings->enabled_notifications) ?>
    <?php $enabled_notifications_count = count($enabled_notifications) ?>
    <?php
        $enabled_notifications_string = implode(', ', array_map(function($key) {
            return \Altum\Language::get()->notification->{strtolower($key)}->name;
        }, array_keys($enabled_notifications)));
    ?>
    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $enabled_notifications_count ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $enabled_notifications_count ? null : 'text-muted' ?>">
            <?php if($enabled_notifications_count == count(\Altum\Notification::get_config())): ?>
                <?= \Altum\Language::get()->global->plan_settings->enabled_notifications_all ?>
            <?php else: ?>
                <span data-toggle="tooltip" title="<?= $enabled_notifications_string ?>">
                    <?= sprintf(\Altum\Language::get()->global->plan_settings->enabled_notifications_x, '<strong>' . nr($enabled_notifications_count) . '</strong>') ?>
                </span>
            <?php endif ?>
        </div>
    </li>

</ul>
