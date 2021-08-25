<?php
defined('ALTUMCODE') || die();

/* Create the content for each tab */
$html = [];

/* Extra Javascript needed */
$javascript = '';
?>

<?php /* Basic Tab */ ?>
<?php ob_start() ?>
    <div class="form-group">
        <label for="settings_name"><?= $this->language->notification->settings->name ?></label>
        <input type="text" id="settings_name" name="name" class="form-control" value="<?= $data->notification->name ?>" required="required" />
    </div>

    <div class="form-group">
        <label for="settings_description"><i class="fa fa-fw fa-sm fa-feather text-muted mr-1"></i> <?= $this->language->notification->settings->description ?></label>
        <input type="text" id="settings_description" name="description" class="form-control" value="<?= $data->notification->settings->description ?>" required="required" />
    </div>

    <div class="form-group">
        <label for="settings_last_activity"><?= sprintf($this->language->notification->settings->last_activity, $this->language->global->date->minutes) ?></label>
        <input type="number" id="settings_last_activity" name="last_activity" class="form-control" value="<?= $data->notification->settings->last_activity ?>" required="required" />
    </div>

    <div class="form-group">
        <label for="settings_url"><?= $this->language->notification->settings->url ?></label>
        <input type="text" id="settings_url" name="url" class="form-control" value="<?= $data->notification->settings->url ?>" />
        <small class="form-text text-muted"><?= $this->language->notification->settings->url_help ?></small>
    </div>

    <div class="custom-control custom-switch mr-3 mb-3">
        <input
                type="checkbox"
                class="custom-control-input"
                id="settings_url_new_tab"
                name="url_new_tab"
            <?= $data->notification->settings->url_new_tab ? 'checked="checked"' : null ?>
        >

        <label class="custom-control-label clickable" for="settings_url_new_tab"><?= $this->language->notification->settings->url_new_tab ?></label>

        <div>
            <small class="form-text text-muted"><?= $this->language->notification->settings->url_new_tab_help ?></small>
        </div>
    </div>
<?php $html['basic'] = ob_get_clean() ?>


<?php /* Triggers Tab Extra */ ?>
<?php ob_start() ?>

<div class="form-group">
    <label for="settings_display_minimum_activity"><?= $this->language->notification->settings->display_minimum_activity ?></label>
    <input type="number" min="0" id="settings_display_minimum_activity" name="display_minimum_activity" class="form-control" value="<?= $data->notification->settings->display_minimum_activity ?>" />
    <small class="form-text text-muted"><?= $this->language->notification->settings->display_minimum_activity_help ?></small>
</div>

<?php $html['triggers'] = ob_get_clean() ?>


<?php /* Customize Tab */ ?>
<?php ob_start() ?>
    <div class="form-group">
        <label for="settings_number_color"><?= $this->language->notification->settings->number_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_number_color_pickr"></div>
            </div>
            <input type="text" id="settings_number_color" name="number_color" class="form-control border-left-0" value="<?= $data->notification->settings->number_color ?>" required="required" />
        </div>
    </div>

    <div class="form-group">
        <label for="settings_number_background_color"><?= $this->language->notification->settings->number_background_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_number_background_color_pickr"></div>
            </div>
            <input type="text" id="settings_number_background_color" name="number_background_color" class="form-control border-left-0" value="<?= $data->notification->settings->number_background_color ?>" required="required" />
        </div>
    </div>

    <div class="form-group">
        <label for="settings_description_color"><?= $this->language->notification->settings->description_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_description_color_pickr"></div>
            </div>
            <input type="text" id="settings_description_color" name="description_color" class="form-control border-left-0" value="<?= $data->notification->settings->description_color ?>" required="required" />
        </div>
    </div>

    <div class="form-group">
        <label for="settings_background_color"><?= $this->language->notification->settings->background_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_background_color_pickr"></div>
            </div>
            <input type="text" id="settings_background_color" name="background_color" class="form-control border-left-0" value="<?= $data->notification->settings->background_color ?>" required="required" />
        </div>
    </div>

    <div class="form-group">
        <label for="settings_background_pattern"><?= $this->language->notification->settings->background_pattern ?></label>
        <select class="form-control" id="settings_background_pattern" name="background_pattern">
            <option value="" <?= $data->notification->settings->background_pattern == '' ? 'selected="selected"' : null ?>><?= $this->language->notification->settings->background_pattern_none ?></option>

            <?php $background_patterns = (require_once APP_PATH . 'includes/notifications_background_patterns.php')(); ?>

            <?php foreach($background_patterns as $key => $value): ?>
                <option value="<?= $key ?>" <?= $data->notification->settings->background_pattern == $key ? 'selected="selected"' : null ?> data-value="<?= $value ?>"><?= $this->language->notification->settings->{'background_pattern_' . $key} ?></option>
            <?php endforeach ?>
        </select>
    </div>

    <div class="form-group">
        <label for="settings_pulse_background_color"><?= $this->language->notification->settings->pulse_background_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_pulse_background_color_pickr"></div>
            </div>
            <input type="text" id="settings_pulse_background_color" name="pulse_background_color" class="form-control border-left-0" value="<?= $data->notification->settings->pulse_background_color ?>" required="required" />
        </div>
    </div>

        <div class="row">
        <div class="col-12 col-md-4">
            <div class="form-group">
                <label for="settings_border_radius"><?= $this->language->notification->settings->border_radius ?></label>
                <select class="form-control" name="border_radius">
                    <option value="straight" <?= $data->notification->settings->border_radius == 'straight' ? 'selected="selected"' : null ?>><?= $this->language->notification->settings->border_radius_straight ?></option>
                    <option value="rounded" <?= $data->notification->settings->border_radius == 'rounded' ? 'selected="selected"' : null ?>><?= $this->language->notification->settings->border_radius_rounded ?></option>
                </select>
                <small class="form-text text-muted"><?= $this->language->notification->settings->border_radius_help ?></small>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="form-group">
                <label for="settings_border_width"><?= $this->language->notification->settings->border_width ?></label>
                <input type="number" min="0" max="5" id="settings_border_width" name="border_width" class="form-control" value="<?= $data->notification->settings->border_width ?>" />
                <small class="form-text text-muted"><?= $this->language->notification->settings->border_width_help ?></small>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="form-group">
                <label for="settings_border_color"><?= $this->language->notification->settings->border_color ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div id="settings_border_color_pickr"></div>
                    </div>
                    <input type="text" id="settings_border_color" name="border_color" class="form-control border-left-0" value="<?= $data->notification->settings->border_color ?>" required="required" />
                </div>
            </div>
        </div>
    </div>

    <div class="custom-control custom-switch mr-3 mb-3">
        <input
                type="checkbox"
                class="custom-control-input"
                id="settings_shadow"
                name="shadow"
                <?= $data->notification->settings->shadow ? 'checked="checked"' : null ?>
        >

        <label class="custom-control-label clickable" for="settings_shadow"><?= $this->language->notification->settings->shadow ?></label>

        <div>
            <small class="form-text text-muted"><?= $this->language->notification->settings->shadow_help ?></small>
        </div>
    </div>
<?php $html['customize'] = ob_get_clean() ?>


<?php ob_start() ?>
<script>
    /* Notification Preview Handlers */
    $('#settings_title').on('change paste keyup', event => {
        $('#notification_preview .altumcode-live-counter-title').text($(event.currentTarget).val());
    });

    $('#settings_description').on('change paste keyup', event => {
        $('#notification_preview .altumcode-live-counter-description').text($(event.currentTarget).val());
    });

    $('#settings_image').on('change paste keyup', event => {
        $('#notification_preview .altumcode-live-counter-image').attr('src', $(event.currentTarget).val());
    });

    /* Number Color Handler */
    let settings_number_color_pickr = Pickr.create({
        el: '#settings_number_color_pickr',
        default: $('#settings_number_color').val(),
        ...pickr_options
    });

    settings_number_color_pickr.on('change', hsva => {
        $('#settings_number_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-live-counter-number').css('color', hsva.toHEXA().toString());
    });

    /* Number Background Color Handler */
    let settings_number_background_color_pickr = Pickr.create({
        el: '#settings_number_background_color_pickr',
        default: $('#settings_number_background_color').val(),
        ...pickr_options
    });

    settings_number_background_color_pickr.on('change', hsva => {
        $('#settings_number_background_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-live-counter-number').css('background', hsva.toHEXA().toString());
    });


    /* Description Color Handler */
    let settings_description_color_pickr = Pickr.create({
        el: '#settings_description_color_pickr',
        default: $('#settings_description_color').val(),
        ...pickr_options
    });

    settings_description_color_pickr.on('change', hsva => {
        $('#settings_description_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-live-counter-description').css('color', hsva.toHEXA().toString());
    });


    /* Background Color Handler */
    let settings_background_color_pickr = Pickr.create({
        el: '#settings_background_color_pickr',
        default: $('#settings_background_color').val(),
        ...pickr_options
    });

    settings_background_color_pickr.on('change', hsva => {
        $('#settings_background_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-wrapper').css('background-color', hsva.toHEXA().toString());
    });

    /* Background Pattern Handler */
    $('#settings_background_pattern').on('change paste keyup', event => {
        let value = $(event.currentTarget).find(':selected').data('value');

        if(value) {
            $('#notification_preview .altumcode-wrapper').css('background-image', `url(${value})`);
        } else {
            $('#notification_preview .altumcode-wrapper').css('background-image', '');
        }
    });

    /* Pulse Background Color Handler */
    let settings_pulse_background_color_pickr = Pickr.create({
        el: '#settings_pulse_background_color_pickr',
        default: $('#settings_pulse_background_color').val(),
        ...pickr_options
    });

    settings_pulse_background_color_pickr.on('change', hsva => {
        $('#settings_pulse_background_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-toast-pulse').css('background', hsva.toHEXA().toString());
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
