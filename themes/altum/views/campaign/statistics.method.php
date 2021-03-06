<?php defined('ALTUMCODE') || die() ?>

<?php if(!$this->settings->socialproofo->analytics_is_enabled): ?>
    <div class="alert alert-warning" role="alert">
        <?= $this->language->campaign->statistics->disabled ?>
    </div>
<?php endif ?>

<div class="mt-5 mb-3">
    <div class="d-flex flex-column flex-md-row justify-content-between">
        <div>
            <h2 class="h3"><?= $this->language->campaign->statistics->header ?></h2>
        </div>

        <div>
            <button
                    id="daterangepicker"
                    type="button"
                    class="btn btn-sm btn-outline-primary"
                    data-min-date="<?= \Altum\Date::get($data->campaign->date, 4) ?>"
                    data-max-date="<?= \Altum\Date::get('', 4) ?>"
            >
                <i class="fa fa-fw fa-calendar mr-1"></i>
                <span>
                    <?php if($data->date->start_date == $data->date->end_date): ?>
                        <?= \Altum\Date::get($data->date->start_date, 2, \Altum\Date::$default_timezone) ?>
                    <?php else: ?>
                        <?= \Altum\Date::get($data->date->start_date, 2, \Altum\Date::$default_timezone) . ' - ' . \Altum\Date::get($data->date->end_date, 2, \Altum\Date::$default_timezone) ?>
                    <?php endif ?>
                </span>
                <i class="fa fa-fw fa-caret-down ml-1"></i>
            </button>
        </div>
    </div>
</div>

<?php if(!count($data->logs)): ?>

    <div class="d-flex flex-column align-items-center justify-content-center">
        <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->global->no_data ?>" />
        <h2 class="h4 text-muted"><?= $this->language->global->no_data ?></h2>
        <p><?= $this->language->campaign->statistics->no_data ?></a></p>
    </div>

<?php else: ?>

    <div class="row justify-content-between mb-5">
        <div class="col-12 col-md-6 col-lg-3 mb-3 mb-xl-0">
            <div class="card border-0 h-100">
                <div class="card-body d-flex">

                    <div>
                        <div class="card border-0 bg-gray-200 text-gray-700 mr-3">
                            <div class="p-3 d-flex align-items-center justify-content-between">
                                <i class="fa fa-fw fa-eye fa-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card-title h4 m-0"><?= nr($data->logs_total['impression']) ?></div>
                        <small class="form-text text-muted"><?= $this->language->campaign->statistics->impressions_chart ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3 mb-3 mb-xl-0">
            <div class="card border-0 h-100">
                <div class="card-body d-flex">

                    <div>
                        <div class="card border-0 bg-gray-200 text-gray-700 mr-3">
                            <div class="p-3 d-flex align-items-center justify-content-between">
                                <i class="fa fa-fw fa-mouse-pointer fa-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card-title h4 m-0"><?= nr($data->logs_total['hover']) ?></div>
                        <small class="form-text text-muted"><?= $this->language->campaign->statistics->hovers_chart ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3 mb-3 mb-xl-0">
            <div class="card border-0 h-100">
                <div class="card-body d-flex">

                    <div>
                        <div class="card border-0 bg-gray-200 text-gray-700 mr-3">
                            <div class="p-3 d-flex align-items-center justify-content-between">
                                <i class="fa fa-fw fa-mouse fa-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card-title h4 m-0"><?= nr($data->logs_total['click']) ?></div>
                        <small class="form-text text-muted"><?= $this->language->campaign->statistics->clicks_chart ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3 mb-3 mb-xl-0">
            <div class="card border-0 h-100">
                <div class="card-body d-flex">

                    <div>
                        <div class="card border-0 bg-gray-200 text-gray-700 mr-3">
                            <div class="p-3 d-flex align-items-center justify-content-between">
                                <i class="fa fa-fw fa-database fa-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card-title h4 m-0"><?= nr($data->logs_total['form_submission']) ?></div>
                        <small class="form-text text-muted"><?= $this->language->campaign->statistics->form_submissions_chart ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="chart-container mb-5">
        <canvas id="impressions_chart"></canvas>
    </div>

    <div class="chart-container mb-5">
        <canvas id="hovers_chart"></canvas>
    </div>

    <div class="chart-container mb-5">
        <canvas id="clicks_chart"></canvas>
    </div>

    <div class="chart-container mb-5">
        <canvas id="form_submissions_chart"></canvas>
    </div>
<?php endif ?>

<?php ob_start() ?>
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/daterangepicker.min.css' ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/moment.min.js' ?>"></script>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/daterangepicker.min.js' ?>"></script>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/moment-timezone-with-data-10-year-range.min.js' ?>"></script>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/Chart.bundle.min.js' ?>"></script>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/chartjs_defaults.js' ?>"></script>

<script>
    'use strict';

    moment.tz.setDefault(<?= json_encode($this->user->timezone) ?>);

    /* Daterangepicker */
    $('#daterangepicker').daterangepicker({
        startDate: <?= json_encode($data->date->start_date) ?>,
        endDate: <?= json_encode($data->date->end_date) ?>,
        minDate: $('#daterangepicker').data('min-date'),
        maxDate: $('#daterangepicker').data('max-date'),
        ranges: {
            <?= json_encode($this->language->global->date->today) ?>: [moment(), moment()],
            <?= json_encode($this->language->global->date->yesterday) ?>: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            <?= json_encode($this->language->global->date->last_7_days) ?>: [moment().subtract(6, 'days'), moment()],
            <?= json_encode($this->language->global->date->last_30_days) ?>: [moment().subtract(29, 'days'), moment()],
            <?= json_encode($this->language->global->date->this_month) ?>: [moment().startOf('month'), moment().endOf('month')],
            <?= json_encode($this->language->global->date->last_month) ?>: [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            <?= json_encode($this->language->global->date->all_time) ?>: [moment($('#daterangepicker').data('min-date')), moment()]
        },
        alwaysShowCalendars: true,
        linkedCalendars: false,
        singleCalendar: true,
        locale: <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>,
    }, (start, end, label) => {

        /* Redirect */
        redirect(`${$('#base_controller_url').val()}/statistics/${start.format('YYYY-MM-DD')}/${end.format('YYYY-MM-DD')}`, true);

    });


    <?php if(count($data->logs)): ?>
    let impressions_chart = document.getElementById('impressions_chart').getContext('2d');

    let gradient = impressions_chart.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(96, 122, 226, 0.6)');
    gradient.addColorStop(1, 'rgba(96, 122, 226, 0.05)');

    new Chart(impressions_chart, {
        type: 'line',
        data: {
            labels: <?= $data->logs_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode($this->language->campaign->statistics->impressions_chart) ?>,
                data: <?= $data->logs_chart['impression'] ?? '[]' ?>,
                backgroundColor: gradient,
                borderColor: '#607ae2',
                fill: true
            }]
        },
        options: chart_options
    });


    let hovers_chart = document.getElementById('hovers_chart').getContext('2d');
    gradient = hovers_chart.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(213, 96, 226, 0.6)');
    gradient.addColorStop(1, 'rgba(213, 96, 226, 0.05)');

    new Chart(hovers_chart, {
        type: 'line',
        data: {
            labels: <?= $data->logs_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode($this->language->campaign->statistics->hovers_chart) ?>,
                data: <?= $data->logs_chart['hover'] ?? '[]' ?>,
                backgroundColor: gradient,
                borderColor: '#d560e2',
                fill: true
            }]
        },
        options: chart_options
    });

    let clicks_chart = document.getElementById('clicks_chart').getContext('2d');

    gradient = clicks_chart.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(96, 187, 226, 0.4)');
    gradient.addColorStop(1, 'rgba(96, 187, 226, 0.05)');

    new Chart(clicks_chart, {
        type: 'line',
        data: {
            labels: <?= $data->logs_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode($this->language->campaign->statistics->clicks_chart) ?>,
                data: <?= $data->logs_chart['click'] ?? '[]' ?>,
                backgroundColor: gradient,
                borderColor: '#60BBE2',
                fill: true
            }]
        },
        options: chart_options
    });

    let form_submissions_chart = document.getElementById('form_submissions_chart').getContext('2d');

    gradient = form_submissions_chart.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(226, 96, 174, 0.4)');
    gradient.addColorStop(1, 'rgba(226, 96, 174, 0.05)');

    new Chart(form_submissions_chart, {
        type: 'line',
        data: {
            labels: <?= $data->logs_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode($this->language->campaign->statistics->form_submissions_chart) ?>,
                data: <?= $data->logs_chart['form_submission'] ?? '[]' ?>,
                backgroundColor: gradient,
                borderColor: '#E260AE',
                fill: true
            }]
        },
        options: chart_options
    });
    <?php endif ?>
</script>

<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
