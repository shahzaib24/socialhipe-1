<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="altumcode-wrapper altumcode-wrapper-<?= $notification->settings->border_radius ?> <?= $notification->settings->shadow ? 'altumcode-wrapper-shadow' : null ?> altumcode-cookie-notification-wrapper" style='background-color: <?= $notification->settings->background_color ?>;border-width: <?= $notification->settings->border_width ?>px;border-color: <?= $notification->settings->border_color ?>;<?= $notification->settings->background_pattern_svg ? 'background-image: url("' . $notification->settings->background_pattern_svg . '")' : null ?>;'>
    <?php if(!empty($notification->settings->icon)): ?>
        <div class="small-notification-icon float-right">
             <img src="<?= SITE_URL . ASSETS_URL_PATH . $notification->settings->icon ?>" class="" loading="lazy" />
        </div>
         <?php endif ?>
    <div class="altumcode-cookie-notification-content p-1">
       
        <div class="col-3 pl-4">  
            <?php if(!empty($notification->settings->image)): ?>
                <img src="<?= SITE_URL . ASSETS_URL_PATH?>/<?= $notification->settings->image ?>" class="altumcode-cookie-notification-image" loading="lazy" />
            <?php endif ?>
        </div>
        
        <div class="col-9 pl-0">
        <div class="altumcode-cookie-notification-header">
          
            <p class="altumcode-cookie-notification-description" style="color: <?= $notification->settings->description_color ?>">
                <?= $notification->settings->description ?>

                <span class="altumcode-cookie-notification-url"><a href="<?= $notification->settings->url ?>"><?= $notification->settings->url_text ?></a></span>
            </p>

            <div class="altumcode-cookie-notification-close">
                <span class="altumcode-close"></span>
            </div>
        </div>

        <button type="button" class="altumcode-cookie-notification-button" style="background: <?= $notification->settings->button_background_color ?>;color: <?= $notification->settings->button_color ?>"><?= $notification->settings->button_text ?></button>

        <?php if($notification->settings->display_branding): ?>
            <?php if(isset($notification->branding, $notification->branding->name, $notification->branding->url) && !empty($notification->branding->name) && !empty($notification->branding->url)): ?>
                <a href="<?= $notification->branding->url ?>" class="altumcode-site"><?= $notification->branding->name ?></a>
            <?php else: ?>
                <a href="<?= url() ?>" class="altumcode-site"><?= $settings->socialproofo->branding ?></a>
            <?php endif ?>
        <?php endif ?>
     </div>
    </div>
</div>
<?php $html = ob_get_clean() ?>


<?php ob_start() ?>
new AltumCodeManager({
    should_show: !localStorage.getItem('notification_<?= $notification->notification_id ?>_converted'),
    content: <?= json_encode($html) ?>,
    display_mobile: <?= json_encode($notification->settings->display_mobile) ?>,
    display_desktop: <?= json_encode($notification->settings->display_desktop) ?>,
    display_trigger: <?= json_encode($notification->settings->display_trigger) ?>,
    display_trigger_value: <?= json_encode($notification->settings->display_trigger_value) ?>,
    duration: <?= $notification->settings->display_duration === -1 ? -1 : $notification->settings->display_duration * 1000 ?>,
    url: '',
    close: <?= json_encode($notification->settings->display_close_button) ?>,
    display_frequency: <?= json_encode($notification->settings->display_frequency) ?>,
    position: <?= json_encode($notification->settings->display_position) ?>,
    trigger_all_pages: <?= json_encode($notification->settings->trigger_all_pages) ?>,
    triggers: <?= json_encode($notification->settings->triggers) ?>,
    on_animation: <?= json_encode($notification->settings->on_animation) ?>,
    off_animation: <?= json_encode($notification->settings->off_animation) ?>,

    notification_id: <?= $notification->notification_id ?>
}).initiate({
    displayed: main_element => {

        /* On click the footer remove element */
        main_element.querySelector('.altumcode-cookie-notification-button').addEventListener('click', event => {

            AltumCodeManager.remove_notification(main_element);

            /* Make sure to let the browser know of the conversion so that it is not shown again */
            localStorage.setItem(`notification_${notification_id}_converted`, true);

            event.preventDefault();

        });

    }
});
<?php $javascript = ob_get_clean(); ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
