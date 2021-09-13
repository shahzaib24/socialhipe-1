<?php defined('ALTUMCODE') || die() ?>


<?php ob_start() ?>
<div class="altumcode-wrapper altumcode-wrapper-<?= $notification->settings->border_radius ?> <?= $notification->settings->shadow ? 'altumcode-wrapper-shadow' : null ?> altumcode-live-counter-wrapper" style='background-color: <?= $notification->settings->background_color ?>;border-width: <?= $notification->settings->border_width ?>px;border-color: <?= $notification->settings->border_color ?>;<?= $notification->settings->background_pattern_svg ? 'background-image: url("' . $notification->settings->background_pattern_svg . '")' : null ?>;'>
    <div class="altumcode-live-counter-content-custom">
        <?php if(!empty($notification->settings->icon)): ?>
        <div class="position-absolute small-notification-icon">
             <img src="<?= SITE_URL . ASSETS_URL_PATH . $notification->settings->icon ?>" class="" loading="lazy" />
        </div>
         <?php endif ?>
        
<?php if(empty($notification->settings->image)): ?>
        <img src="<?= SITE_URL . ASSETS_URL_PATH ?>/<?= $notification->settings->image ?>" class="altumcode-informational-image" loading="lazy" />
        <?php endif ?>
        <div class="altumcode-live-counter-header">
            <div class="altumcode-live-counter-main-custom">
               

                <div class="altumcode-live-counter-number" style="background: <?= $notification->settings->number_background_color ?>; color: <?= $notification->settings->number_color ?>">
                    <?= isset($notification->counter) && $notification->counter >= $notification->settings->display_minimum_activity ? $notification->counter : \Altum\Language::get()->notification->live_counter->number_default ?>
                </div>
                 <div class="altumcode-toast-pulse" style="background: <?= $notification->settings->pulse_background_color ?>;"></div>
            </div>

            <div class="altumcode-live-counter-close">
                <span class="altumcode-close"></span>
            </div>
        </div>

        <p class="altumcode-live-counter-description" style="color: <?= $notification->settings->description_color ?>"><?= $notification->settings->description ?></p>

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
    on_animation: <?= json_encode($notification->settings->on_animation) ?>,
    off_animation: <?= json_encode($notification->settings->off_animation) ?>,

    notification_id: <?= $notification->notification_id ?>
}).initiate();
<?php $javascript = ob_get_clean(); ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
