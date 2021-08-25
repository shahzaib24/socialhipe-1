<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <h1 class="h3 mr-3"><i class="fa fa-fw fa-xs fa-user text-primary-900 mr-2"></i> <?= $this->language->admin_user_update->header ?></h1>

        <?= include_view(THEME_PATH . 'views/admin/partials/admin_user_dropdown_button.php', ['id' => $data->user->user_id]) ?>
    </div>
</div>

<?php display_notifications() ?>

<div class="card">
    <div class="card-body">

        <form action="" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="row">
                <div class="col-12 col-md-4">
                    <h2 class="h4"><?= $this->language->admin_user_update->main->header ?></h2>
                    <p class="text-muted"><?= $this->language->admin_user_update->main->subheader ?></p>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label><?= $this->language->admin_user_update->main->name ?></label>
                        <input type="text" name="name" class="form-control form-control-lg" value="<?= $data->user->name ?>" />
                    </div>

                    <div class="form-group">
                        <label><?= $this->language->admin_user_update->main->email ?></label>
                        <input type="text" name="email" class="form-control form-control-lg" value="<?= $data->user->email ?>" />
                    </div>

                    <div class="form-group">
                        <label><?= $this->language->admin_user_update->main->is_enabled ?></label>

                        <select class="form-control form-control-lg" name="is_enabled">
                            <option value="2" <?php if($data->user->active == 2) echo 'selected' ?>><?= $this->language->admin_user_update->main->is_enabled_disabled ?></option>
                            <option value="1" <?php if($data->user->active == 1) echo 'selected' ?>><?= $this->language->admin_user_update->main->is_enabled_active ?></option>
                            <option value="0" <?php if($data->user->active == 0) echo 'selected' ?>><?= $this->language->admin_user_update->main->is_enabled_unconfirmed ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?= $this->language->admin_user_update->main->type ?></label>

                        <select class="form-control form-control-lg" name="type">
                            <option value="1" <?php if($data->user->type == 1) echo 'selected' ?>><?= $this->language->admin_user_update->main->type_admin ?></option>
                            <option value="0" <?php if($data->user->type == 0) echo 'selected' ?>><?= $this->language->admin_user_update->main->type_user ?></option>
                        </select>

                        <small class="form-text text-muted"><?= $this->language->admin_user_update->main->type_help ?></small>
                    </div>
                </div>
            </div>

                <div class="mt-5"></div>

                <div class="row">
                    <div class="col-12 col-md-4">
                        <h2 class="h4"><?= $this->language->admin_user_update->plan->header ?></h2>
                        <p class="text-muted"><?= $this->language->admin_user_update->plan->header_help ?></p>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label><?= $this->language->admin_user_update->plan->plan_id ?></label>

                            <select class="form-control form-control-lg" name="plan_id">
                                <option value="free" <?php if($data->user->plan->plan_id == 'free') echo 'selected' ?>><?= $this->settings->plan_free->name ?></option>
                                <option value="trial" <?php if($data->user->plan->plan_id == 'trial') echo 'selected' ?>><?= $this->settings->plan_trial->name ?></option>
                                <option value="custom" <?php if($data->user->plan->plan_id == 'custom') echo 'selected' ?>><?= $this->settings->plan_custom->name ?></option>

                                <?php while($row = $data->plans_result->fetch_object()): ?>
                                    <option value="<?= $row->plan_id ?>" <?php if($data->user->plan->plan_id == $row->plan_id) echo 'selected' ?>><?= $row->name ?></option>
                                <?php endwhile ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><?= $this->language->admin_user_update->plan->plan_trial_done ?></label>

                            <select class="form-control form-control-lg" name="plan_trial_done">
                                <option value="1" <?= $data->user->plan_trial_done ? 'selected="selected"' : null ?>><?= $this->language->global->yes ?></option>
                                <option value="0" <?= !$data->user->plan_trial_done ? 'selected="selected"' : null ?>><?= $this->language->global->no ?></option>
                            </select>
                        </div>

                        <div id="plan_expiration_date_container" class="form-group">
                            <label><?= $this->language->admin_user_update->plan->plan_expiration_date ?></label>
                            <input type="text" class="form-control form-control-lg" name="plan_expiration_date" autocomplete="off" value="<?= $data->user->plan_expiration_date ?>">
                            <div class="invalid-feedback">
                                <?= $this->language->admin_user_update->plan->plan_expiration_date_invalid ?>
                            </div>
                        </div>

                        <div id="plan_settings" style="display: none">
                            <div class="form-group">
                                <label for="campaigns_limit"><?= $this->language->admin_plans->plan->campaigns_limit ?></label>
                                <input type="number" id="campaigns_limit" name="campaigns_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->campaigns_limit ?>" />
                                <small class="form-text text-muted"><?= $this->language->admin_plans->plan->campaigns_limit_help ?></small>
                            </div>

                            <div class="form-group">
                                <label for="notifications_limit"><?= $this->language->admin_plans->plan->notifications_limit ?></label>
                                <input type="number" id="notifications_limit" name="notifications_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->notifications_limit ?>" />
                                <small class="form-text text-muted"><?= $this->language->admin_plans->plan->notifications_limit_help ?></small>
                            </div>

                            <div class="form-group">
                                <label for="notifications_impressions_limit"><?= $this->language->admin_plans->plan->notifications_impressions_limit ?></label>
                                <input type="number" id="notifications_impressions_limit" name="notifications_impressions_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->notifications_impressions_limit ?>" />
                                <small class="form-text text-muted"><?= $this->language->admin_plans->plan->notifications_impressions_limit_help ?></small>
                            </div>

                            <div class="custom-control custom-switch mb-3">
                                <input id="no_ads" name="no_ads" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->no_ads ? 'checked="checked"' : null ?>>
                                <label class="custom-control-label" for="no_ads"><?= $this->language->admin_plans->plan->no_ads ?></label>
                                <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->no_ads_help ?></small></div>
                            </div>

                            <div class="custom-control custom-switch mb-3">
                                <input id="removable_branding" name="removable_branding" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->removable_branding ? 'checked="checked"' : null ?>>
                                <label class="custom-control-label" for="removable_branding"><?= $this->language->admin_plans->plan->removable_branding ?></label>
                                <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->removable_branding_help ?></small></div>
                            </div>

                            <div class="custom-control custom-switch mb-3">
                                <input id="custom_branding" name="custom_branding" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->custom_branding ? 'checked="checked"' : null ?>>
                                <label class="custom-control-label" for="custom_branding"><?= $this->language->admin_plans->plan->custom_branding ?></label>
                                <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->custom_branding_help ?></small></div>
                            </div>

                            <h3 class="h5 mt-3"><?= $this->language->admin_plans->plan->enabled_notifications ?></h3>
                            <p class="text-muted"><?= $this->language->admin_plans->plan->enabled_notifications_help ?></p>

                            <div class="row">
                                <?php foreach($data->notifications as $notification_type => $notification_config): ?>
                                    <div class="col-6">
                                        <div class="custom-control custom-switch">
                                            <input id="enabled_notifications_<?= $notification_type ?>" name="enabled_notifications[]" value="<?= $notification_type ?>" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->enabled_notifications->{$notification_type} ? 'checked="checked"' : null ?>>
                                            <label class="custom-control-label" for="enabled_notifications_<?= $notification_type ?>"><?= $this->language->notification->{strtolower($notification_type)}->name ?></label>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5"></div>

                <div class="row">
                    <div class="col-12 col-md-4">
                        <h2 class="h4"><?= $this->language->admin_user_update->change_password->header ?></h2>
                        <p class="text-muted"><?= $this->language->admin_user_update->change_password->subheader ?></p>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label><?= $this->language->admin_user_update->change_password->new_password ?></label>
                            <input type="password" name="new_password" class="form-control form-control-lg" />
                        </div>

                        <div class="form-group">
                            <label><?= $this->language->admin_user_update->change_password->repeat_password ?></label>
                            <input type="password" name="repeat_password" class="form-control form-control-lg" />
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 col-md-4"></div>

                    <div class="col">
                        <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->global->update ?></button>
                    </div>
                </div>

        </form>
    </div>
</div>

<?php ob_start() ?>
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/daterangepicker.min.css' ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/moment.min.js' ?>"></script>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/daterangepicker.min.js' ?>"></script>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/moment-timezone-with-data-10-year-range.min.js' ?>"></script>

<script>
    'use strict';

    moment.tz.setDefault(<?= json_encode($this->user->timezone) ?>);

    let check_plan_id = () => {
        let selected_plan_id = document.querySelector('[name="plan_id"]').value;

        if(selected_plan_id == 'free') {
            document.querySelector('#plan_expiration_date_container').style.display = 'none';
        } else {
            document.querySelector('#plan_expiration_date_container').style.display = 'block';
        }

        if(selected_plan_id == 'custom') {
            document.querySelector('#plan_settings').style.display = 'block';
        } else {
            document.querySelector('#plan_settings').style.display = 'none';
        }
    };

    check_plan_id();

    /* Dont show expiration date when the chosen plan is the free one */
    document.querySelector('[name="plan_id"]').addEventListener('change', check_plan_id);

    /* Check for expiration date to show a warning if expired */
    let check_plan_expiration_date = () => {
        let plan_expiration_date = document.querySelector('[name="plan_expiration_date"]');

        let plan_expiration_date_object = new Date(plan_expiration_date.value);
        let today_date_object = new Date();

        if(plan_expiration_date_object < today_date_object) {
            plan_expiration_date.classList.add('is-invalid');
        } else {
            plan_expiration_date.classList.remove('is-invalid');
        }
    };

    check_plan_expiration_date();
    document.querySelector('[name="plan_expiration_date"]').addEventListener('change', check_plan_expiration_date);

    /* Daterangepicker */
    $('[name="plan_expiration_date"]').daterangepicker({
        startDate: <?= json_encode($data->user->plan_expiration_date) ?>,
        minDate: new Date(),
        alwaysShowCalendars: true,
        singleCalendar: true,
        singleDatePicker: true,
        locale: <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>,
    }, (start, end, label) => {
        check_plan_expiration_date()
    });

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
