<?php defined('ALTUMCODE') || die() ?>


<?php ob_start() ?>
<div class="altumcode-wrapper altumcode-wrapper-<?= $notification->settings->border_radius ?> <?= $notification->settings->shadow ? 'altumcode-wrapper-shadow' : null ?> altumcode-score-feedback-wrapper" style='background-color: <?= $notification->settings->background_color ?>;border-width: <?= $notification->settings->border_width ?>px;border-color: <?= $notification->settings->border_color ?>;<?= $notification->settings->background_pattern_svg ? 'background-image: url("' . $notification->settings->background_pattern_svg . '")' : null ?>;'>
    <div class="altumcode-score-feedback-content px-4">
        <?php if(!empty($notification->settings->icon)): ?>
        <div class="position-absolute small-notification-icon left-90">
             <img src="<?= SITE_URL . ASSETS_URL_PATH . $notification->settings->icon ?>" class="" loading="lazy" />
        </div>
         <?php endif ?>
        <div class="altumcode-score-feedback-header">
            <p class="altumcode-score-feedback-title" style="color: <?= $notification->settings->title_color ?>"><?= $notification->settings->title ?></p>

            <span class="altumcode-close"></span>
        </div>

        <?php
        function hexToRgb($hex, $alpha = false) {
   $hex      = str_replace('#', '', $hex);
   $length   = strlen($hex);
   $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
   $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
   $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
   if ( $alpha ) {
      $rgb['a'] = $alpha;
   }
   return $rgb;
}
        
        ?>
        
        
        
        
        
        <div class="altumcode-score-feedback-scores">
            <button type="button" class="altumcode-score-feedback-button" data-score="1" style="background: <?= $notification->settings->button_background_color ?>;color: <?= $notification->settings->button_color ?>; box-shadow: 0px 8px 17px rgba(<?php echo implode(',',hexToRgb($notification->settings->button_background_color, 0.3)); ?>)"><?= \Altum\Language::get()->notification->score_feedback->feedback_score_1 ?></button>
            <button type="button" class="altumcode-score-feedback-button" data-score="2" style="background: <?= $notification->settings->button_background_color ?>;color: <?= $notification->settings->button_color ?>; box-shadow: 0px 8px 17px rgba(<?php echo implode(',',hexToRgb($notification->settings->button_background_color, 0.3)); ?>)"><?= \Altum\Language::get()->notification->score_feedback->feedback_score_2 ?></button>
            <button type="button" class="altumcode-score-feedback-button" data-score="3" style="background: <?= $notification->settings->button_background_color ?>;color: <?= $notification->settings->button_color ?>; box-shadow: 0px 8px 17px rgba(<?php echo implode(',',hexToRgb($notification->settings->button_background_color, 0.3)); ?>)"><?= \Altum\Language::get()->notification->score_feedback->feedback_score_3 ?></button>
            <button type="button" class="altumcode-score-feedback-button" data-score="4" style="background: <?= $notification->settings->button_background_color ?>;color: <?= $notification->settings->button_color ?>; box-shadow: 0px 8px 17px rgba(<?php echo implode(',',hexToRgb($notification->settings->button_background_color, 0.3)); ?>)"><?= \Altum\Language::get()->notification->score_feedback->feedback_score_4 ?></button>
            <button type="button" class="altumcode-score-feedback-button" data-score="5" style="background: <?= $notification->settings->button_background_color ?>;color: <?= $notification->settings->button_color ?>; box-shadow: 0px 8px 17px rgba(<?php echo implode(',',hexToRgb($notification->settings->button_background_color, 0.3)); ?>)"><?= \Altum\Language::get()->notification->score_feedback->feedback_score_5 ?></button>
        </div>

        <p class="altumcode-score-feedback-description" style="color: <?= $notification->settings->description_color ?>"><?= $notification->settings->description ?></p>

        <?php if($notification->settings->display_branding): ?>
            <?php if(isset($notification->branding, $notification->branding->name, $notification->branding->url)):  ?>
                <a href="<?= $notification->branding->url ?>" class="altumcode-site"><?= $notification->branding->name ?></a>
            <?php else: ?>
                <a href="<?= url() ?>" class="altumcode-site"><?= $settings->socialproofo->branding ?></a>
            <?php endif ?>
        <?php endif ?>
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
    close: <?= json_encode($notification->settings->display_close_button) ?>,
    display_frequency: <?= json_encode($notification->settings->display_frequency) ?>,
    position: <?= json_encode($notification->settings->display_position) ?>,
    trigger_all_pages: <?= json_encode($notification->settings->trigger_all_pages) ?>,
    triggers: <?= json_encode($notification->settings->triggers) ?>,

    notification_id: <?= $notification->notification_id ?>
}).initiate({
    displayed: main_element => {

        /* On click event to the button */
        let scores = main_element.querySelectorAll('.altumcode-score-feedback-button');

        for(let score of scores) {
            score.addEventListener('click', event => {

                /* Trigger the animation */
                score.className += ' altumcode-score-feedback-button-clicked';

                /* Get all the other emojis and remove them */
                let other_scores = main_element.querySelectorAll('.altumcode-score-feedback-button:not(.altumcode-score-feedback-button-clicked)');
                for(let other_score of other_scores) {
                    other_score.remove();
                }

                let notification_id = main_element.getAttribute('data-notification-id');
                let feedback = score.getAttribute('data-score');

                send_tracking_data({
                    ...user,
                    notification_id: notification_id,
                    type: 'notification',
                    subtype: `feedback_score_${feedback}`
                });

                /* Make sure to let the browser know of the conversion so that it is not shown again */
                localStorage.setItem(`notification_${notification_id}_converted`, true);

                setTimeout(() => {
                    AltumCodeManager.remove_notification(main_element);
                }, 950);

            });
        }


    }
});
<?php $javascript = ob_get_clean(); ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
