<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class Notification extends Controller {
    public $notification_id;
    public $notification;

    public function index() {

        Authentication::guard();

        $this->notification_id = isset($this->params[0]) ? (int) Database::clean_string($this->params[0]) : null;
        $method = isset($this->params[1]) && in_array($this->params[1], ['settings', 'statistics', 'data']) ? $this->params[1] : 'settings';

        /* Make sure the notification exists and is accessible to the user */
        $this->notification = $this->database->query("SELECT `notifications`.*, `campaigns`.`domain` FROM `notifications` LEFT JOIN `campaigns` ON `campaigns`.`campaign_id` = `notifications`.`campaign_id` WHERE `notifications`.`notification_id` = '{$this->notification_id}' AND `notifications`.`user_id` = {$this->user->user_id}")->fetch_object() ?? null;
        if(!$this->notification_id || !$this->notification) {
            redirect('dashboard');
        }

        /* Get the settings of the notification */
        $this->notification->settings = json_decode($this->notification->settings);

        switch($this->notification->type)  {
            case 'INFORMATIONAL':
            case 'COUPON':
            case 'LIVE_COUNTER':
            case 'VIDEO':
            case 'SOCIAL_SHARE':
            case 'EMOJI_FEEDBACK':
            case 'COOKIE_NOTIFICATION':
            case 'SCORE_FEEDBACK':
            case 'INFORMATIONAL_BAR':
            case 'IMAGE':
            case 'COUPON_BAR':
            case 'BUTTON_BAR':
            case 'BUTTON_MODAL':
            case 'ENGAGEMENT_LINKS':

                $this->notification->settings->enabled_methods = ['statistics', 'settings'];
                $this->notification->settings->enabled_settings_tabs = ['basic', 'display', 'customize', 'triggers'];

                break;

            case 'EMAIL_COLLECTOR':
            case 'LATEST_CONVERSION':
            case 'CONVERSIONS_COUNTER':
            case 'REQUEST_COLLECTOR':
            case 'COUNTDOWN_COLLECTOR':
            case 'COLLECTOR_BAR':
            case 'COLLECTOR_MODAL':
            case 'COLLECTOR_TWO_MODAL':
            case 'TEXT_FEEDBACK':

                $this->notification->settings->enabled_methods = ['statistics', 'settings', 'data'];
                $this->notification->settings->enabled_settings_tabs = ['basic', 'display', 'customize', 'triggers', 'data'];

            break;

            case 'RANDOM_REVIEW':

                $this->notification->settings->enabled_methods = ['statistics', 'settings', 'data'];
                $this->notification->settings->enabled_settings_tabs = ['basic', 'display', 'customize', 'triggers'];

            break;
        }

        /* Prepare the menu View */
        $data = [
            'notification'  => $this->notification,
            'method'        => $method
        ];

        $view = new \Altum\Views\View('notification/menu', (array) $this);

        $this->add_view_content('method_menu', $view->run($data));

        /* Handle code for different parts of the page */
        switch($method) {
            case 'settings':

                /* Handle form submission */
                $this->process_settings_post();

                /* Prepare the method View */
                $data = [
                    'notification'  => $this->notification,
                    'method'        => $method
                ];

                $view = new \Altum\Views\View('notification/' . $method . '.method', (array) $this);

                $this->add_view_content('method', $view->run($data));

                break;


            case 'statistics':

                $start_date = isset($this->params[2]) ? Database::clean_string($this->params[2]) : false;
                $end_date = isset($this->params[3]) ? Database::clean_string($this->params[3]) : false;

                $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

                /* Query for the statistics of the notification */
                $logs = [];
                $logs_chart = [];
                $logs_total = [
                    'impression'   => 0,
                    'hover'        => 0,
                    'click'        => 0
                ];

                /* Logs for the charts */
                $logs_result = Database::$database->query("
                    SELECT
                         `type`,
                         COUNT(`id`) AS `total`,
                         DATE_FORMAT(`datetime`, '%Y-%m-%d') AS `formatted_date`
                    FROM
                         `track_notifications`
                    WHERE
                        `notification_id` = {$this->notification->notification_id}
                        AND (`datetime` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                    GROUP BY
                        `formatted_date`,
                        `type`
                    ORDER BY
                        `formatted_date`
                ");

                /* Generate the raw chart data and save logs for later usage */
                while($row = $logs_result->fetch_object()) {
                    $logs[] = $row;

                    $row->formatted_date = \Altum\Date::get($row->formatted_date, 4);

                    /* Handle if the date key is not already set */
                    if(!array_key_exists($row->formatted_date, $logs_chart)) {
                        $logs_chart[$row->formatted_date] = [
                            'impression'        => 0,
                            'hover'             => 0,
                            'click'             => 0,
                            'form_submission'   => 0,
                            'feedback_emoji_angry'    => 0,
                            'feedback_emoji_sad'      => 0,
                            'feedback_emoji_neutral'  => 0,
                            'feedback_emoji_happy'    => 0,
                            'feedback_emoji_excited'  => 0,
                            'feedback_score_1'  => 0,
                            'feedback_score_2'  => 0,
                            'feedback_score_3'  => 0,
                            'feedback_score_4'  => 0,
                            'feedback_score_5'  => 0,

                        ];
                    }

                    $logs_chart[$row->formatted_date][$row->type] = $row->total;

                    /* Count totals */
                    if(in_array($row->type, ['impression', 'hover', 'click'])) {
                        $logs_total[$row->type] += $row->total;
                    }
                }

                $logs_chart = get_chart_data($logs_chart);

                /* Get most accessed urls and their type of notification */
                $top_pages_result = Database::$database->query("
                    SELECT 
                        DISTINCT `url`, 
                        `type`, 
                        COUNT(`id`) AS `pageviews`
                    FROM 
                        `track_notifications`
                    WHERE
                        `notification_id` = {$this->notification->notification_id}
                        AND (`datetime` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                    GROUP BY 
                        `url`, 
                         `type` 
                    ORDER BY 
                        `pageviews` DESC 
                    LIMIT 25
                ");

                /* Prepare the method View */
                $data = [
                    'notification' => $this->notification,
                    'method'=> $method,
                    'logs' => $logs,
                    'logs_chart' => $logs_chart,
                    'logs_total' => $logs_total,
                    'top_pages_result' => $top_pages_result,
                    'date' => $date,
                ];

                $view = new \Altum\Views\View('notification/' . $method . '.method', (array) $this);

                $this->add_view_content('method', $view->run($data));

                break;


            case 'data':

                $start_date = isset($this->params[2]) ? Database::clean_string($this->params[2]) : false;
                $end_date = isset($this->params[3]) ? Database::clean_string($this->params[3]) : false;

                $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

                /* Prepare the paginator */
                $total_rows = Database::$database->query("SELECT COUNT(*) AS `total` FROM `track_conversions` WHERE `notification_id` = {$this->notification->notification_id} AND (`datetime` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')")->fetch_object()->total ?? 0;
                $paginator = (new \Altum\Paginator($total_rows, 50, $_GET['page'] ?? 1, url('notification/' . $this->notification->notification_id . '/data/' . $date->start_date . '/' . $date->end_date . '?page=%d')));

                /* Get the data from the database */
                $conversions = [];

                $conversions_result = Database::$database->query("SELECT `id`, `notification_id`, `type`, `data`, `location`, `url`, `datetime` FROM `track_conversions` WHERE `notification_id` = {$this->notification->notification_id} AND (`datetime` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}') ORDER BY `id` DESC {$paginator->get_sql_limit()}");

                while($row = $conversions_result->fetch_object()) $conversions[] = $row;

                /* Prepare the pagination view */
                $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

                /* Handle JSON Export request */
                if(isset($_GET['json'])) {
                    header('Content-disposition: attachment; filename=data.json');
                    header('Content-type: application/json');

                    /* Prepare the json */
                    $conversions_json = [];

                    foreach($conversions as $row) {
                        $row->data = json_decode($row->data);
                        $row->location = json_decode($row->location);

                        $conversions_json[] = $row;
                    }

                    echo json_encode($conversions_json);

                    die();
                }

                /* Custom Data Import Modal */
                $modal = $this->notification->type == 'RANDOM_REVIEW' ? 'data.create_review_data_modal.method' : 'data.create_data_modal.method';
                $data = ['notification' => $this->notification];
                $view = new \Altum\Views\View('notification/data/' . $modal, (array) $this);
                \Altum\Event::add_content($view->run($data), 'modals');

                /* Prepare the method View */
                $data = [
                    'notification'      => $this->notification,
                    'method'            => $method,
                    'conversions'       => $conversions,
                    'pagination'        => $pagination,
                    'date'              => $date
                ];

                $view = new \Altum\Views\View('notification/' . $method . '.method', (array) $this);

                $this->add_view_content('method', $view->run($data));

                break;
        }

        /* Delete Modal */
        $view = new \Altum\Views\View('notification/notification_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the View */
        $data = [
            'notification'  => $this->notification,
            'method'        => $method
        ];

        $view = new \Altum\Views\View('notification/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->notification->title, $this->notification->name));

    }

    private function process_settings_post() {

        /* Handle the update of the notification */
        if(!empty($_POST)) {
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['trigger_all_pages'] = (bool) isset($_POST['trigger_all_pages']);
            $_POST['display_trigger'] = in_array($_POST['display_trigger'], [
                'delay',
                'exit_intent',
                'scroll'
            ]) ? $_POST['display_trigger'] : 'delay';
            $_POST['display_trigger_value'] = (int) $_POST['display_trigger_value'];
            $_POST['display_frequency'] = in_array($_POST['display_frequency'], [
                'all_time',
                'once_per_session',
                'once_per_browser',
            ]) ? $_POST['display_frequency'] : 'all_time';
            $_POST['display_mobile'] = (bool) isset($_POST['display_mobile']);
            $_POST['display_desktop'] = (bool) isset($_POST['display_desktop']);

            $_POST['display_duration'] = (int) $_POST['display_duration'];
            $_POST['display_position'] = in_array($_POST['display_position'], [
                'top_left',
                'top_center',
                'top_right',
                'middle_left',
                'middle_center',
                'middle_right',
                'bottom_left',
                'bottom_center',
                'bottom_right',
                'top',
                'bottom',
                'top_floating',
                'bottom_floating'
            ]) ? $_POST['display_position'] : 'bottom_left';
            $_POST['display_close_button'] = (bool) isset($_POST['display_close_button']);
            $_POST['display_branding'] = (bool) isset($_POST['display_branding']);

            $_POST['shadow'] = (bool) isset($_POST['shadow']);
            $_POST['border_width'] = (int) ($_POST['border_width'] >= 0 && $_POST['border_width'] <= 5 ? $_POST['border_width'] : 0);
            $_POST['on_animation'] = in_array($_POST['on_animation'], [
                'fadeIn',
                'slideInUp',
                'slideInDown',
                'zoomIn',
                'bounceIn',
            ]) ? $_POST['on_animation'] : 'fadeIn';
            $_POST['off_animation'] = in_array($_POST['off_animation'], [
                'fadeOut',
                'slideOutUp',
                'slideOutDown',
                'zoomOut',
                'bounceOut',
            ]) ? $_POST['off_animation'] : 'fadeOut';

            switch($this->notification->type) {

                case 'INFORMATIONAL':

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['image'] = Database::clean_string($_POST['image']);
                    $_POST['url'] = Database::clean_string($_POST['url']);
                    $_POST['url_new_tab'] = (bool) isset($_POST['url_new_tab']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                        'round',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    break;

                case 'COUPON':

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['image'] = Database::clean_string($_POST['image']);
                    $_POST['button_url'] = Database::clean_string($_POST['button_url']);
                    $_POST['button_text'] = Database::clean_string($_POST['button_text']);
                    $_POST['footer_text'] = Database::clean_string($_POST['footer_text']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    break;

                case 'LIVE_COUNTER':

                    /* Clean some posted variables */
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['last_activity'] = (int) $_POST['last_activity'];
                    $_POST['url'] = Database::clean_string($_POST['url']);
                    $_POST['url_new_tab'] = (bool) isset($_POST['url_new_tab']);
                    $_POST['display_minimum_activity'] = (int) $_POST['display_minimum_activity'];
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    break;

                case 'EMAIL_COLLECTOR' :

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['email_placeholder'] = Database::clean_string($_POST['email_placeholder']);
                    $_POST['button_text'] = Database::clean_string($_POST['button_text']);
                    $_POST['show_agreement'] = (bool) isset($_POST['show_agreement']);
                    $_POST['agreement_text'] = Database::clean_string($_POST['agreement_text']);
                    $_POST['agreement_url'] = Database::clean_string($_POST['agreement_url']);
                    $_POST['thank_you_url'] = Database::clean_string($_POST['thank_you_url']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    $_POST['data_send_is_enabled'] = (bool) isset($_POST['data_send_is_enabled']);
                    $_POST['data_send_webhook'] = Database::clean_string($_POST['data_send_webhook']);
                    $_POST['data_send_email'] = filter_var($_POST['data_send_email'], FILTER_VALIDATE_EMAIL) ? Database::clean_string($_POST['data_send_email']) : '';

                    break;

                case 'LATEST_CONVERSION':

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['image'] = Database::clean_string($_POST['image']);
                    $_POST['url'] = Database::clean_string($_POST['url']);
                    $_POST['url_new_tab'] = (bool) isset($_POST['url_new_tab']);
                    $_POST['conversions_count'] = (int) $_POST['conversions_count'] < 1 ? 1 : (int) $_POST['conversions_count'];
                    $_POST['in_between_delay'] = (int) $_POST['in_between_delay'] < 1 ? 0 : (int) $_POST['in_between_delay'];
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                        'round',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    $_POST['data_trigger_auto'] = (bool) isset($_POST['data_trigger_auto']);

                    break;

                case 'CONVERSIONS_COUNTER':

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['last_activity'] = (int) $_POST['last_activity'];
                    $_POST['url'] = Database::clean_string($_POST['url']);
                    $_POST['url_new_tab'] = (bool) isset($_POST['url_new_tab']);
                    $_POST['display_minimum_activity'] = (int) $_POST['display_minimum_activity'];
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                        'round',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    $_POST['data_trigger_auto'] = (bool) isset($_POST['data_trigger_auto']);

                    break;

                case 'VIDEO':

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['video'] = Database::clean_string($_POST['video']);
                    $_POST['button_url'] = Database::clean_string($_POST['button_url']);
                    $_POST['button_text'] = Database::clean_string($_POST['button_text']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    /* Make sure the video link has the proper format */
                    if(!preg_match('/^(https|http):\/\/(?:www\.)?youtube.com\/embed\/[A-z0-9]+/', $_POST['video'])) {
                        $_POST['video'] = '';
                    }

                    break;

                case 'SOCIAL_SHARE':

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['share_url'] = Database::clean_string($_POST['share_url']);
                    $_POST['share_facebook'] = (bool) isset($_POST['share_facebook']);
                    $_POST['share_twitter'] = (bool) isset($_POST['share_twitter']);
                    $_POST['share_linkedin'] = (bool) isset($_POST['share_linkedin']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;

                case 'RANDOM_REVIEW':

                    /* Clean some posted variables */
                    $_POST['url'] = Database::clean_string($_POST['url']);
                    $_POST['url_new_tab'] = (bool) isset($_POST['url_new_tab']);
                    $_POST['reviews_count'] = (int) $_POST['reviews_count'] < 1 ? 1 : (int) $_POST['reviews_count'];
                    $_POST['in_between_delay'] = (int) $_POST['in_between_delay'] < 1 ? 0 : (int) $_POST['in_between_delay'];
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                        'round',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;

                case 'EMOJI_FEEDBACK':

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['show_angry'] = (bool) isset($_POST['show_angry']);
                    $_POST['show_sad'] = (bool) isset($_POST['show_sad']);
                    $_POST['show_neutral'] = (bool) isset($_POST['show_neutral']);
                    $_POST['show_happy'] = (bool) isset($_POST['show_happy']);
                    $_POST['show_excited'] = (bool) isset($_POST['show_excited']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;

                case 'COOKIE_NOTIFICATION':

                    /* Clean some posted variables */
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['image'] = Database::clean_string($_POST['image']);
                    $_POST['url_text'] = Database::clean_string($_POST['url_text']);
                    $_POST['url'] = Database::clean_string($_POST['url']);
                    $_POST['button_text'] = Database::clean_string($_POST['button_text']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;

                case 'SCORE_FEEDBACK':

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;

                case 'REQUEST_COLLECTOR' :

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['image'] = Database::clean_string($_POST['image']);
                    $_POST['content_title'] = Database::clean_string($_POST['content_title']);
                    $_POST['content_description'] = Database::clean_string($_POST['content_description']);
                    $_POST['input_placeholder'] = Database::clean_string($_POST['input_placeholder']);
                    $_POST['button_text'] = Database::clean_string($_POST['button_text']);
                    $_POST['show_agreement'] = (bool) isset($_POST['show_agreement']);
                    $_POST['agreement_text'] = Database::clean_string($_POST['agreement_text']);
                    $_POST['agreement_url'] = Database::clean_string($_POST['agreement_url']);
                    $_POST['thank_you_url'] = Database::clean_string($_POST['thank_you_url']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    $_POST['data_send_is_enabled'] = (bool) isset($_POST['data_send_is_enabled']);
                    $_POST['data_send_webhook'] = Database::clean_string($_POST['data_send_webhook']);
                    $_POST['data_send_email'] = filter_var($_POST['data_send_email'], FILTER_VALIDATE_EMAIL) ? Database::clean_string($_POST['data_send_email']) : '';

                    break;

                case 'COUNTDOWN_COLLECTOR' :

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['content_title'] = Database::clean_string($_POST['content_title']);
                    $_POST['input_placeholder'] = Database::clean_string($_POST['input_placeholder']);
                    $_POST['button_text'] = Database::clean_string($_POST['button_text']);
                    $_POST['end_date'] = (new \DateTime($_POST['end_date'], new \DateTimeZone($this->user->timezone)))->setTimezone(new \DateTimeZone(\Altum\Date::$default_timezone))->format('Y-m-d H:i:s');
                    $_POST['show_agreement'] = (bool) isset($_POST['show_agreement']);
                    $_POST['agreement_text'] = Database::clean_string($_POST['agreement_text']);
                    $_POST['agreement_url'] = Database::clean_string($_POST['agreement_url']);
                    $_POST['thank_you_url'] = Database::clean_string($_POST['thank_you_url']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    $_POST['data_send_is_enabled'] = (bool) isset($_POST['data_send_is_enabled']);
                    $_POST['data_send_webhook'] = Database::clean_string($_POST['data_send_webhook']);
                    $_POST['data_send_email'] = filter_var($_POST['data_send_email'], FILTER_VALIDATE_EMAIL) ? Database::clean_string($_POST['data_send_email']) : '';

                    break;

                case 'INFORMATIONAL_BAR':

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['image'] = Database::clean_string($_POST['image']);
                    $_POST['url'] = Database::clean_string($_POST['url']);
                    $_POST['url_new_tab'] = (bool) isset($_POST['url_new_tab']);
                    $_POST['border_radius'] = 'straight';

                    break;

                case 'IMAGE':

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['image'] = Database::clean_string($_POST['image']);
                    $_POST['button_url'] = Database::clean_string($_POST['button_url']);
                    $_POST['button_text'] = Database::clean_string($_POST['button_text']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;

                case 'COLLECTOR_BAR' :

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['input_placeholder'] = Database::clean_string($_POST['input_placeholder']);
                    $_POST['button_text'] = Database::clean_string($_POST['button_text']);
                    $_POST['show_agreement'] = (bool) isset($_POST['show_agreement']);
                    $_POST['agreement_text'] = Database::clean_string($_POST['agreement_text']);
                    $_POST['agreement_url'] = Database::clean_string($_POST['agreement_url']);
                    $_POST['thank_you_url'] = Database::clean_string($_POST['thank_you_url']);
                    $_POST['border_radius'] = 'straight';

                    $_POST['data_send_is_enabled'] = (bool) isset($_POST['data_send_is_enabled']);
                    $_POST['data_send_webhook'] = Database::clean_string($_POST['data_send_webhook']);
                    $_POST['data_send_email'] = filter_var($_POST['data_send_email'], FILTER_VALIDATE_EMAIL) ? Database::clean_string($_POST['data_send_email']) : '';

                    break;

                case 'COUPON_BAR':

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['coupon_code'] = Database::clean_string($_POST['coupon_code']);
                    $_POST['url'] = Database::clean_string($_POST['url']);
                    $_POST['url_new_tab'] = (bool) isset($_POST['url_new_tab']);
                    $_POST['border_radius'] = 'straight';

                    break;

                case 'BUTTON_BAR':

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['button_text'] = Database::clean_string($_POST['button_text']);
                    $_POST['url'] = Database::clean_string($_POST['url']);
                    $_POST['url_new_tab'] = (bool) isset($_POST['url_new_tab']);
                    $_POST['border_radius'] = 'straight';

                    break;

                case 'COLLECTOR_MODAL' :
                case 'COLLECTOR_TWO_MODAL' :

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['image'] = Database::clean_string($_POST['image']);
                    $_POST['input_placeholder'] = Database::clean_string($_POST['input_placeholder']);
                    $_POST['button_text'] = Database::clean_string($_POST['button_text']);
                    $_POST['show_agreement'] = (bool) isset($_POST['show_agreement']);
                    $_POST['agreement_text'] = Database::clean_string($_POST['agreement_text']);
                    $_POST['agreement_url'] = Database::clean_string($_POST['agreement_url']);
                    $_POST['thank_you_url'] = Database::clean_string($_POST['thank_you_url']);
                    $_POST['border_radius'] = 'rounded';

                    $_POST['data_send_is_enabled'] = (bool) isset($_POST['data_send_is_enabled']);
                    $_POST['data_send_webhook'] = Database::clean_string($_POST['data_send_webhook']);
                    $_POST['data_send_email'] = filter_var($_POST['data_send_email'], FILTER_VALIDATE_EMAIL) ? Database::clean_string($_POST['data_send_email']) : '';

                    break;

                case 'BUTTON_MODAL' :

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['image'] = Database::clean_string($_POST['image']);
                    $_POST['button_text'] = Database::clean_string($_POST['button_text']);
                    $_POST['button_url'] = Database::clean_string($_POST['button_url']);
                    $_POST['border_radius'] = 'rounded';

                    break;

                case 'TEXT_FEEDBACK' :

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['input_placeholder'] = Database::clean_string($_POST['input_placeholder']);
                    $_POST['button_text'] = Database::clean_string($_POST['button_text']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    $_POST['data_send_is_enabled'] = (bool) isset($_POST['data_send_is_enabled']);
                    $_POST['data_send_webhook'] = Database::clean_string($_POST['data_send_webhook']);
                    $_POST['data_send_email'] = filter_var($_POST['data_send_email'], FILTER_VALIDATE_EMAIL) ? Database::clean_string($_POST['data_send_email']) : '';

                    break;

                case 'ENGAGEMENT_LINKS' :

                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);

                    $_POST['categories'] = $_POST['categories'] ? array_map(function($category) {
                        $category['title'] = Database::clean_string($category['title']);
                        $category['description'] = Database::clean_string($category['description']);

                        $category['links'] = array_map(function($category_link) {
                            $category_link['title'] = Database::clean_string($category_link['title']);
                            $category_link['description'] = Database::clean_string($category_link['description']);
                            $category_link['image'] = Database::clean_string($category_link['image']);
                            $category_link['url'] = Database::clean_string($category_link['url']);

                            return $category_link;
                        }, $category['links']);

                        return $category;
                    }, $_POST['categories']) : null;

                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;
            }

            /* Go over all the possible color inputs and make sure they comply */
            foreach($_POST as $key => $value) {
                if(string_ends_with('_color', $key) && !preg_match('/#([a-f0-9]{3}){1,2}\b/i', $value)) {

                    /* Replace it with a plain black color */
                    $_POST[$key] = '#000';

                }
            }

            /* Get the proper color for the pattern */
            $background_pattern_color_hex = new \OzdemirBurak\Iris\Color\Hex($_POST['background_color']);
            $background_pattern_color = $background_pattern_color_hex->isDark() ? $background_pattern_color_hex->brighten(40) : $background_pattern_color_hex->darken(40);

            /* Process the background pattern if needed */
            $background_patterns = (require_once APP_PATH . 'includes/notifications_background_patterns.php')($background_pattern_color);
            $_POST['background_pattern'] = array_key_exists($_POST['background_pattern'], $background_patterns) ? $_POST['background_pattern'] : false;
            $_POST['background_pattern_svg'] = $background_patterns[$_POST['background_pattern']] ?? false;

            /* Go over the triggers and clean them */
            foreach($_POST['trigger_type'] as $key => $value) {
                $_POST['trigger_type'][$key] = in_array($value, ['exact', 'not_exact', 'contains', 'not_contains', 'starts_with', 'not_starts_with', 'ends_with', 'not_ends_with', 'page_contains']) ? Database::clean_string($value) : 'exact';
            }

            foreach($_POST['trigger_value'] as $key => $value) {
                $_POST['trigger_value'][$key] = Database::clean_string($value);
            }

            /* Generate the trigger rules var */
            $triggers = [];

            foreach($_POST['trigger_type'] as $key => $value) {
                $triggers[] = [
                    'type' => $value,
                    'value' => $_POST['trigger_value'][$key]
                ];
            }

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(empty($_SESSION['error'])) {

                /* Default notification settings */
                $new_notification_settings = [
                    'trigger_all_pages' => $_POST['trigger_all_pages'],
                    'triggers' => $triggers,
                    'display_trigger' => $_POST['display_trigger'],
                    'display_trigger_value' => $_POST['display_trigger_value'],
                    'display_frequency' => $_POST['display_frequency'],
                    'display_mobile' => $_POST['display_mobile'],
                    'display_desktop' => $_POST['display_desktop'],

                    'shadow'        => $_POST['shadow'],
                    'border_radius' => $_POST['border_radius'],
                    'border_width' => $_POST['border_width'],
                    'border_color' => $_POST['border_color'],
                    'on_animation' => $_POST['on_animation'],
                    'off_animation' => $_POST['off_animation'],
                    'background_pattern' => $_POST['background_pattern'],
                    'background_pattern_svg' => $_POST['background_pattern_svg'],

                    'display_duration' => $_POST['display_duration'],
                    'display_position' => $_POST['display_position'],
                    'display_close_button' => $_POST['display_close_button'],
                    'display_branding' => $_POST['display_branding']
                ];


                /* Prepare the settings json based on the notification type */
                switch($this->notification->type) {

                    case 'INFORMATIONAL' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                            ]
                        );

                        break;

                    case 'COUPON' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'coupon_code' => $_POST['coupon_code'],
                                'button_url' => $_POST['button_url'],
                                'button_text' => $_POST['button_text'],
                                'footer_text' => $_POST['footer_text'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],
                            ]
                        );

                        break;

                    case 'LIVE_COUNTER' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'description' => $_POST['description'],
                                'last_activity' => $_POST['last_activity'],
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],

                                'display_minimum_activity' => $_POST['display_minimum_activity'],

                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'number_background_color' => $_POST['number_background_color'],
                                'number_color' => $_POST['number_color'],
                                'pulse_background_color' => $_POST['pulse_background_color'],
                            ]
                        );

                        break;

                    case 'EMAIL_COLLECTOR' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'email_placeholder' => $_POST['email_placeholder'],
                                'button_text' => $_POST['button_text'],
                                'show_agreement' => $_POST['show_agreement'],
                                'agreement_text' => $_POST['agreement_text'],
                                'agreement_url' => $_POST['agreement_url'],
                                'thank_you_url' => $_POST['thank_you_url'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],

                                'data_send_is_enabled' => $_POST['data_send_is_enabled'],
                                'data_send_webhook' => $_POST['data_send_webhook'],
                                'data_send_email' => $_POST['data_send_email'],
                            ]
                        );

                        break;

                    case 'LATEST_CONVERSION' :

                        /* Go over the data triggers auto and clean them */
                        foreach($_POST['data_trigger_auto_type'] as $key => $value) {
                            $_POST['data_trigger_auto_type'][$key] = in_array($value, ['exact', 'contains', 'starts_with', 'ends_with']) ? Database::clean_string($value) : 'exact';
                        }

                        foreach($_POST['data_trigger_auto_value'] as $key => $value) {
                            $_POST['data_trigger_auto_value'][$key] = Database::clean_string($value);
                        }

                        /* Generate the trigger rules var */
                        $data_triggers_auto = [];

                        foreach($_POST['data_trigger_auto_type'] as $key => $value) {
                            $data_triggers_auto[] = [
                                'type' => $value,
                                'value' => $_POST['data_trigger_auto_value'][$key]
                            ];
                        }

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],
                                'conversions_count' => $_POST['conversions_count'],
                                'in_between_delay' => $_POST['in_between_delay'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],

                                'data_trigger_auto' => $_POST['data_trigger_auto'],
                                'data_triggers_auto' => $data_triggers_auto
                            ]);

                        break;

                    case 'CONVERSIONS_COUNTER' :

                        /* Go over the data triggers auto and clean them */
                        foreach($_POST['data_trigger_auto_type'] as $key => $value) {
                            $_POST['data_trigger_auto_type'][$key] = in_array($value, ['exact', 'contains', 'starts_with', 'ends_with']) ? Database::clean_string($value) : 'exact';
                        }

                        foreach($_POST['data_trigger_auto_value'] as $key => $value) {
                            $_POST['data_trigger_auto_value'][$key] = Database::clean_string($value);
                        }

                        /* Generate the trigger rules var */
                        $data_triggers_auto = [];

                        foreach($_POST['data_trigger_auto_type'] as $key => $value) {
                            $data_triggers_auto[] = [
                                'type' => $value,
                                'value' => $_POST['data_trigger_auto_value'][$key]
                            ];
                        }

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'last_activity' => $_POST['last_activity'],
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],

                                'display_minimum_activity' => $_POST['display_minimum_activity'],

                                'title_color' => $_POST['title_color'],
                                'background_color' => $_POST['background_color'],
                                'number_background_color' => $_POST['number_background_color'],
                                'number_color' => $_POST['number_color'],

                                'data_trigger_auto' => $_POST['data_trigger_auto'],
                                'data_triggers_auto' => $data_triggers_auto
                            ]
                        );

                        break;

                    case 'VIDEO' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'video' => $_POST['video'],
                                'button_url' => $_POST['button_url'],
                                'button_text' => $_POST['button_text'],

                                'title_color' => $_POST['title_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],
                            ]
                        );

                        break;

                    case 'SOCIAL_SHARE' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'share_url' => $_POST['share_url'],
                                'share_facebook' => $_POST['share_facebook'],
                                'share_twitter' => $_POST['share_twitter'],
                                'share_linkedin' => $_POST['share_linkedin'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                            ]
                        );

                        break;

                    case 'RANDOM_REVIEW' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],
                                'reviews_count' => $_POST['reviews_count'],
                                'in_between_delay' => $_POST['in_between_delay'],

                                /* Keep the following keys to default */
                                'title' => $this->language->notification->random_review->title_default,
                                'description' => $this->language->notification->random_review->description_default,
                                'image' => $this->language->notification->random_review->image_default,
                                'stars' => 5,

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                            ]
                        );

                        break;

                    case 'EMOJI_FEEDBACK' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],

                                'show_angry' => $_POST['show_angry'],
                                'show_sad' => $_POST['show_sad'],
                                'show_neutral' => $_POST['show_neutral'],
                                'show_happy' => $_POST['show_happy'],
                                'show_excited' => $_POST['show_excited'],

                                'title_color' => $_POST['title_color'],
                                'background_color' => $_POST['background_color'],
                            ]
                        );

                        break;

                    case 'COOKIE_NOTIFICATION' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'url_text' => $_POST['url_text'],
                                'url' => $_POST['url'],
                                'button_text' => $_POST['button_text'],

                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],
                            ]
                        );

                        break;

                    case 'SCORE_FEEDBACK' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],
                            ]
                        );

                        break;

                    case 'REQUEST_COLLECTOR' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'content_title' => $_POST['content_title'],
                                'content_description' => $_POST['content_description'],
                                'input_placeholder' => $_POST['input_placeholder'],
                                'button_text' => $_POST['button_text'],
                                'show_agreement' => $_POST['show_agreement'],
                                'agreement_text' => $_POST['agreement_text'],
                                'agreement_url' => $_POST['agreement_url'],
                                'thank_you_url' => $_POST['thank_you_url'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'content_title_color' => $_POST['content_title_color'],
                                'content_description_color' => $_POST['content_description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],

                                'data_send_is_enabled' => $_POST['data_send_is_enabled'],
                                'data_send_webhook' => $_POST['data_send_webhook'],
                                'data_send_email' => $_POST['data_send_email'],
                            ]
                        );

                        break;

                    case 'COUNTDOWN_COLLECTOR' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'content_title' => $_POST['content_title'],
                                'input_placeholder' => $_POST['input_placeholder'],
                                'button_text' => $_POST['button_text'],
                                'end_date' => $_POST['end_date'],
                                'show_agreement' => $_POST['show_agreement'],
                                'agreement_text' => $_POST['agreement_text'],
                                'agreement_url' => $_POST['agreement_url'],
                                'thank_you_url' => $_POST['thank_you_url'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'content_title_color' => $_POST['content_title_color'],
                                'time_color' => $_POST['time_color'],
                                'time_background_color' => $_POST['time_background_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],

                                'data_send_is_enabled' => $_POST['data_send_is_enabled'],
                                'data_send_webhook' => $_POST['data_send_webhook'],
                                'data_send_email' => $_POST['data_send_email'],
                            ]
                        );

                        break;

                    case 'INFORMATIONAL_BAR' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                            ]
                        );

                        break;

                    case 'IMAGE' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'image' => $_POST['image'],
                                'button_url' => $_POST['button_url'],
                                'button_text' => $_POST['button_text'],

                                'title_color' => $_POST['title_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],
                            ]
                        );

                        break;

                    case 'COLLECTOR_BAR' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'input_placeholder' => $_POST['input_placeholder'],
                                'button_text' => $_POST['button_text'],
                                'show_agreement' => $_POST['show_agreement'],
                                'agreement_text' => $_POST['agreement_text'],
                                'agreement_url' => $_POST['agreement_url'],
                                'thank_you_url' => $_POST['thank_you_url'],

                                'title_color' => $_POST['title_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],

                                'data_send_is_enabled' => $_POST['data_send_is_enabled'],
                                'data_send_webhook' => $_POST['data_send_webhook'],
                                'data_send_email' => $_POST['data_send_email'],
                            ]
                        );

                        break;

                    case 'COUPON_BAR' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'coupon_code' => $_POST['coupon_code'],
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],

                                'title_color' => $_POST['title_color'],
                                'background_color' => $_POST['background_color'],
                                'coupon_code_color' => $_POST['coupon_code_color'],
                                'coupon_code_background_color' => $_POST['coupon_code_background_color'],
                                'coupon_code_border_color' => $_POST['coupon_code_border_color'],

                            ]
                        );

                        break;

                    case 'BUTTON_BAR' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'button_text' => $_POST['button_text'],
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],

                                'title_color' => $_POST['title_color'],
                                'background_color' => $_POST['background_color'],
                                'button_color' => $_POST['button_color'],
                                'button_background_color' => $_POST['button_background_color'],

                            ]
                        );

                        break;

                    case 'COLLECTOR_MODAL' :
                    case 'COLLECTOR_TWO_MODAL' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'input_placeholder' => $_POST['input_placeholder'],
                                'button_text' => $_POST['button_text'],
                                'show_agreement' => $_POST['show_agreement'],
                                'agreement_text' => $_POST['agreement_text'],
                                'agreement_url' => $_POST['agreement_url'],
                                'thank_you_url' => $_POST['thank_you_url'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],

                                'data_send_is_enabled' => $_POST['data_send_is_enabled'],
                                'data_send_webhook' => $_POST['data_send_webhook'],
                                'data_send_email' => $_POST['data_send_email'],
                            ]
                        );

                        break;

                    case 'BUTTON_MODAL' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'button_text' => $_POST['button_text'],
                                'button_url' => $_POST['button_url'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],
                            ]
                        );

                        break;

                    case 'TEXT_FEEDBACK' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'input_placeholder' => $_POST['input_placeholder'],
                                'button_text' => $_POST['button_text'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],

                                'data_send_is_enabled' => $_POST['data_send_is_enabled'],
                                'data_send_webhook' => $_POST['data_send_webhook'],
                                'data_send_email' => $_POST['data_send_email'],
                            ]
                        );

                        break;

                    case 'ENGAGEMENT_LINKS' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'categories' => $_POST['categories'],

                                'title_color' => $_POST['title_color'],
                                'categories_title_color' => $_POST['categories_title_color'],
                                'categories_description_color' => $_POST['categories_description_color'],
                                'categories_links_title_color' => $_POST['categories_links_title_color'],
                                'categories_links_description_color' => $_POST['categories_links_description_color'],
                                'categories_links_background_color' => $_POST['categories_links_background_color'],
                                'categories_links_border_color' => $_POST['categories_links_border_color'],
                                'background_color' => $_POST['background_color'],
                            ]
                        );

                        break;
                }

                /* Prepare as json for the database update */
                $new_notification_settings = json_encode($new_notification_settings);

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("UPDATE `notifications` SET `name` = ?, `settings` = ? WHERE `notification_id` = ?");
                $stmt->bind_param('sss', $_POST['name'], $new_notification_settings, $this->notification_id);
                $stmt->execute();
                $stmt->close();

                $_SESSION['success'][] = $this->language->notification->success_message->notification_updated;

                redirect('notification/' . $this->notification_id);
            }
        }

    }

    public function delete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $notification_id = (int) Database::clean_string($_POST['notification_id']);

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            redirect('dashboard');
        }

        /* Make sure the notification is created by the logged in user */
        if(!$notification = Database::get(['notification_id', 'campaign_id'], 'notifications', ['user_id' => $this->user->user_id, 'notification_id' => $notification_id])) {
            redirect('dashboard');
        }

        if(empty($_SESSION['error'])) {

            /* Delete from database */
            $stmt = Database::$database->prepare("DELETE FROM `notifications` WHERE `notification_id` = ? AND `user_id` = ?");
            $stmt->bind_param('ss', $_POST['notification_id'], $this->user->user_id);
            $stmt->execute();
            $stmt->close();

            /* Success message */
            $_SESSION['success'][] = $this->language->notification_delete_modal->success_message;

            redirect('campaign/' . $notification->campaign_id);

        }

        die();
    }
}
