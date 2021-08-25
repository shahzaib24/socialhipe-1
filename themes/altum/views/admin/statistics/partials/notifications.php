<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-bell fa-xs text-muted"></i> <?= $this->language->admin_statistics->notifications->track_notifications->header ?></h2>
        <p class="text-muted"><?= $this->language->admin_statistics->notifications->track_notifications->subheader ?></p>

        <div class="chart-container">
            <canvas id="track_notifications"></canvas>
        </div>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-bell fa-xs text-muted"></i> <?= $this->language->admin_statistics->notifications->track_conversions->header ?></h2>
        <p class="text-muted"><?= $this->language->admin_statistics->notifications->track_conversions->subheader ?></p>

        <div class="chart-container">
            <canvas id="track_conversions"></canvas>
        </div>
    </div>
</div>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    let impression_color = css.getPropertyValue('--teal');
    let hover_color = css.getPropertyValue('--indigo');
    let click_color = css.getPropertyValue('--cyan');
    let form_submission_color = css.getPropertyValue('--blue');

    /* Display chart */
    new Chart(document.getElementById('track_notifications').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= $data->track_notifications_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode($this->language->admin_statistics->notifications->track_notifications->chart_impression) ?>,
                    data: <?= $data->track_notifications_chart['impression'] ?? '[]' ?>,
                    backgroundColor: impression_color,
                    borderColor: impression_color,
                    fill: false
                },
                {
                    label: <?= json_encode($this->language->admin_statistics->notifications->track_notifications->chart_hover) ?>,
                    data: <?= $data->track_notifications_chart['hover'] ?? '[]' ?>,
                    backgroundColor: hover_color,
                    borderColor: hover_color,
                    fill: false
                },
                {
                    label: <?= json_encode($this->language->admin_statistics->notifications->track_notifications->chart_click) ?>,
                    data: <?= $data->track_notifications_chart['click'] ?? '[]' ?>,
                    backgroundColor: click_color,
                    borderColor: click_color,
                    fill: false
                },
                {
                    label: <?= json_encode($this->language->admin_statistics->notifications->track_notifications->chart_form_submission) ?>,
                    data: <?= $data->track_notifications_chart['form_submission'] ?? '[]' ?>,
                    backgroundColor: form_submission_color,
                    borderColor: form_submission_color,
                    fill: false
                }
            ]
        },
        options: chart_options
    });


    let webhook_color = css.getPropertyValue('--teal');
    let collector_color = css.getPropertyValue('--indigo');
    let auto_capture_color = css.getPropertyValue('--cyan');

    /* Display chart */
    new Chart(document.getElementById('track_conversions').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= $data->track_conversions_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode($this->language->admin_statistics->notifications->track_conversions->chart_webhook) ?>,
                    data: <?= $data->track_conversions_chart['webhook'] ?? '[]' ?>,
                    backgroundColor: webhook_color,
                    borderColor: webhook_color,
                    fill: false
                },
                {
                    label: <?= json_encode($this->language->admin_statistics->notifications->track_conversions->chart_collector) ?>,
                    data: <?= $data->track_conversions_chart['collector'] ?? '[]' ?>,
                    backgroundColor: collector_color,
                    borderColor: collector_color,
                    fill: false
                },
                {
                    label: <?= json_encode($this->language->admin_statistics->notifications->track_conversions->chart_auto_capture) ?>,
                    data: <?= $data->track_conversions_chart['auto_capture'] ?? '[]' ?>,
                    backgroundColor: auto_capture_color,
                    borderColor: auto_capture_color,
                    fill: false
                }
            ]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
