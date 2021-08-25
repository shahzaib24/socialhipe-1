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
        <label for="settings_title"><?= $this->language->notification->settings->title ?></label>
        <input type="text" id="settings_title" name="title" class="form-control" value="<?= $data->notification->settings->title ?>" required="required" />
    </div>

    <div class="form-group">
        <label for="settings_coupon_code"><?= $this->language->notification->settings->coupon_code ?></label>
        <input type="text" id="settings_coupon_code" name="coupon_code" class="form-control" value="<?= $data->notification->settings->coupon_code ?>" required="required" />
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

<?php /* Default Display Tab */ ?>
<?php ob_start() ?>
<div class="form-group">
    <label for="settings_display_duration"><?= $this->language->notification->settings->display_duration ?></label>
    <input type="number" min="-1" id="settings_display_duration" name="display_duration" class="form-control" value="<?= $data->notification->settings->display_duration ?>" required="required" />
    <small class="form-text text-muted"><?= $this->language->notification->settings->display_duration_help ?></small>
</div>

<div class="form-group">
    <label for="settings_display_position"><?= $this->language->notification->settings->display_position ?></label>
    <select class="form-control" name="display_position">
        <option value="top" <?= $data->notification->settings->display_position == 'top' ? 'selected="selected"' : null ?>><?= $this->language->notification->settings->display_position_top ?></option>
        <option value="bottom" <?= $data->notification->settings->display_position == 'bottom' ? 'selected="selected"' : null ?>><?= $this->language->notification->settings->display_position_bottom ?></option>
        <option value="top_floating" <?= $data->notification->settings->display_position == 'top_floating' ? 'selected="selected"' : null ?>><?= $this->language->notification->settings->display_position_top_floating ?></option>
        <option value="bottom_floating" <?= $data->notification->settings->display_position == 'bottom_floating' ? 'selected="selected"' : null ?>><?= $this->language->notification->settings->display_position_bottom_floating ?></option>
    </select>
    <small class="form-text text-muted"><?= $this->language->notification->settings->display_position_help ?></small>
</div>

<div class="custom-control custom-switch mr-3 mb-3">
    <input
            type="checkbox"
            class="custom-control-input"
            id="display_close_button"
            name="display_close_button"
            <?= $data->notification->settings->display_close_button ? 'checked="checked"' : null ?>
    >
    <label class="custom-control-label clickable" for="display_close_button"><?= $this->language->notification->settings->display_close_button ?></label>
</div>

<div class="custom-control custom-switch mr-3 mb-3 <?= !$this->user->plan_settings->removable_branding ? 'container-disabled': null ?>">
    <input
            type="checkbox"
            class="custom-control-input"
            id="display_branding"
            name="display_branding"
            <?= $data->notification->settings->display_branding ? 'checked="checked"' : null ?>
    >
    <label class="custom-control-label clickable" for="display_branding"><?= $this->language->notification->settings->display_branding ?></label>
</div>
<?php $html['display'] = ob_get_clean() ?>

<?php /* Customize Tab */ ?>
<?php ob_start() ?>
    <div class="form-group">
        <label for="settings_title_color"><?= $this->language->notification->settings->title_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_title_color_pickr"></div>
            </div>
            <input type="text" id="settings_title_color" name="title_color" class="form-control border-left-0" value="<?= $data->notification->settings->title_color ?>" required="required" />
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
        <label for="settings_coupon_code_color"><?= $this->language->notification->settings->coupon_code_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_coupon_code_color_pickr"></div>
            </div>
            <input type="text" id="settings_coupon_code_color" name="coupon_code_color" class="form-control border-left-0" value="<?= $data->notification->settings->coupon_code_color ?>" required="required" />
        </div>
    </div>

    <div class="form-group">
        <label for="settings_coupon_code_background_color"><?= $this->language->notification->settings->coupon_code_background_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_coupon_code_background_color_pickr"></div>
            </div>
            <input type="text" id="settings_coupon_code_background_color" name="coupon_code_background_color" class="form-control border-left-0" value="<?= $data->notification->settings->coupon_code_background_color ?>" required="required" />
        </div>
    </div>

    <div class="form-group">
        <label for="settings_coupon_code_border_color"><?= $this->language->notification->settings->coupon_code_border_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_coupon_code_border_color_pickr"></div>
            </div>
            <input type="text" id="settings_coupon_code_border_color" name="coupon_code_border_color" class="form-control border-left-0" value="<?= $data->notification->settings->coupon_code_border_color ?>" required="required" />
        </div>
    </div>

        <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-group">
                <label for="settings_border_width"><?= $this->language->notification->settings->border_width ?></label>
                <input type="number" min="0" max="5" id="settings_border_width" name="border_width" class="form-control" value="<?= $data->notification->settings->border_width ?>" />
                <small class="form-text text-muted"><?= $this->language->notification->settings->border_width_help ?></small>
            </div>
        </div>

        <div class="col-12 col-md-6">
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
<?php $html['customize'] = ob_get_clean() ?>


<?php ob_start() ?>
<script>
    /* Notification Preview Handlers */
    $('#settings_title').on('change paste keyup', event => {
        $('#notification_preview .altumcode-coupon-bar-title').text($(event.currentTarget).val());
    });

    $('#settings_coupon_code').on('change paste keyup', event => {
        $('#notification_preview .altumcode-coupon-bar-coupon-code').text($(event.currentTarget).val());
    });

    /* Title Color Handler */
    let settings_title_color_pickr = Pickr.create({
        el: '#settings_title_color_pickr',
        default: $('#settings_title_color').val(),
        ...pickr_options
    });

    settings_title_color_pickr.on('change', hsva => {
        $('#settings_title_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-coupon-bar-title').css('color', hsva.toHEXA().toString());
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

    /* Coupon code Color Handler */
    let settings_coupon_code_color_pickr = Pickr.create({
        el: '#settings_coupon_code_color_pickr',
        default: $('#settings_coupon_code_color').val(),
        ...pickr_options
    });

    settings_coupon_code_color_pickr.on('change', hsva => {
        $('#settings_coupon_code_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-coupon-bar-coupon-code').css('color', hsva.toHEXA().toString());
    });

    /* Coupon code background Color Handler */
    let settings_coupon_code_background_color_pickr = Pickr.create({
        el: '#settings_coupon_code_background_color_pickr',
        default: $('#settings_coupon_code_background_color').val(),
        ...pickr_options
    });

    settings_coupon_code_background_color_pickr.on('change', hsva => {
        $('#settings_coupon_code_background_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-coupon-bar-coupon-code').css('background', hsva.toHEXA().toString());
    });

    /* Coupon code border Color Handler */
    let settings_coupon_code_border_color_pickr = Pickr.create({
        el: '#settings_coupon_code_border_color_pickr',
        default: $('#settings_coupon_code_border_color').val(),
        ...pickr_options
    });

    settings_coupon_code_border_color_pickr.on('change', hsva => {
        $('#settings_coupon_code_border_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-coupon-bar-coupon-code').css('border-color', hsva.toHEXA().toString());
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
