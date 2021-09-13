<?php defined('ALTUMCODE') || die() ?>


<?php ob_start() ?>
<div class="altumcode-wrapper altumcode-wrapper-<?= $notification->settings->border_radius ?> <?= $notification->settings->shadow ? 'altumcode-wrapper-shadow' : null ?> altumcode-conversions-counter-wrapper" style='background-color: <?= $notification->settings->background_color ?>;border-width: <?= $notification->settings->border_width ?>px;border-color: <?= $notification->settings->border_color ?>;<?= $notification->settings->background_pattern_svg ? 'background-image: url("' . $notification->settings->background_pattern_svg . '")' : null ?>;'>
    <div class="altumcode-conversions-counter-content pr-2">

        <?php if(!empty($notification->settings->icon)): ?>
        <div class="row">
        <div class="col-10">
        
         <?php endif ?>
        
        <div class="altumcode-conversions-counter-header">
            <?php $notification->image = isset($notification->image) && $notification->image ? $notification->image : $notification->settings->image; ?>
        <?php if(!empty($notification->image)): ?>
        <img src="<?= SITE_URL . ASSETS_URL_PATH ?>/<?= $notification->image ?>" class="altumcode-latest-conversion-image" loading="lazy" />
        <?php endif ?>

            <div>
                <p class="altumcode-conversions-counter-title pb-2" style="color: <?= $notification->settings->title_color ?>"><?= isset($notification->counter) && $notification->counter >= $notification->settings->display_minimum_activity ? $notification->counter : \Altum\Language::get()->notification->conversions_counter->number_default ?> <?= $notification->settings->title ?></p>
                <p class="altumcode-conversions-counter-time"><?= sprintf(\Altum\Language::get()->notification->conversions_counter->time_default, $notification->settings->last_activity) ?></p>
            </div>

            <div class="altumcode-conversions-counter-close">
                <span class="altumcode-close"></span>
            </div>
        
        </div>
        <?php if(!empty($notification->settings->icon)): ?>
        </div>
        <div class="col-2">
             <img src="<?= SITE_URL . ASSETS_URL_PATH . $notification->settings->icon ?>" class="small-notification-icon" loading="lazy" />
        </div>
        </div>
         <?php endif ?>

        
        
        <?php if($notification->settings->display_branding): ?>
            <?php if(isset($notification->branding, $notification->branding->name, $notification->branding->url) && !empty($notification->branding->name) && !empty($notification->branding->url)): ?>
                <a href="<?= $notification->branding->url ?>" class="altumcode-site"><?= $notification->branding->name ?></a>
            <?php else: ?>
                <a href="<?= url() ?>" class="altumcode-site"><?= $settings->socialproofo->branding ?></a>
            <?php endif ?>
        <?php endif ?>
    </div>
</div>
<?php $html = ob_get_clean(); ?>


<?php ob_start() ?>
new AltumCodeManager({
    should_show: <?= json_encode(isset($notification->counter) && $notification->counter >= $notification->settings->display_minimum_activity) ?>,
    content: <?= json_encode($html) ?>,
    display_mobile: <?= json_encode($notification->settings->display_mobile) ?>,
    display_desktop: <?= json_encode($notification->settings->display_desktop) ?>,
    display_trigger: <?= json_encode($notification->settings->display_trigger) ?>,
    display_trigger_value: <?= json_encode($notification->settings->display_trigger_value) ?>,
    duration: <?= $notification->settings->display_duration === -1 ? -1 : $notification->settings->display_duration * 1000 ?>,
    url: <?= json_encode($notification->settings->url) ?>,
    url_new_tab: <?= json_encode($notification->settings->url_new_tab) ?>,
    close: <?= json_encode($notification->settings->display_close_button) ?>,
    display_frequency: <?= json_encode($notification->settings->display_frequency) ?>,
    position: <?= json_encode($notification->settings->display_position) ?>,
    trigger_all_pages: <?= json_encode($notification->settings->trigger_all_pages) ?>,
    triggers: <?= json_encode($notification->settings->triggers) ?>,
    data_trigger_auto: <?= json_encode($notification->settings->data_trigger_auto) ?>,
    data_triggers_auto: <?= json_encode($notification->settings->data_triggers_auto) ?>,
    on_animation: <?= json_encode($notification->settings->on_animation) ?>,
    off_animation: <?= json_encode($notification->settings->off_animation) ?>,

    notification_id: <?= $notification->notification_id ?>
}).initiate();
<?php $javascript = ob_get_clean(); ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
