<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Models\User;
use Altum\Title;

class Campaign extends Controller {

    public function index() {

        Authentication::guard();

        $campaign_id = isset($this->params[0]) ? (int) $this->params[0] : false;
        $method = isset($this->params[1]) && in_array($this->params[1], ['settings', 'statistics']) ? $this->params[1] : 'settings';

        /* Make sure the campaign exists and is accessible to the user */
        if(!$campaign = Database::get('*', 'campaigns', ['campaign_id' => $campaign_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        /* Get the custom branding details */
        $campaign->branding = json_decode($campaign->branding);

        /* Handle code for different parts of the page */
        switch($method) {
            case 'settings':

                /* Prepare the filtering system */
                $filters = (new \Altum\Filters(['is_enabled', 'type'], ['name'], ['name', 'date']));

                /* Prepare the paginator */
                $total_rows = Database::$database->query("SELECT COUNT(*) AS `total` FROM `notifications` WHERE `campaign_id` = {$campaign->campaign_id} AND `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
                $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('campaign/' . $campaign->campaign_id . '?' . $filters->get_get() . '&page=%d')));

                /* Get the notifications list for the user */
                $notifications = [];
                $notifications_result = Database::$database->query("SELECT * FROM `notifications` WHERE `campaign_id` = {$campaign->campaign_id} AND `user_id` = {$this->user->user_id} {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
                while($row = $notifications_result->fetch_object()) $notifications[] = $row;

                /* Prepare the pagination view */
                $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

                /* Prepare the method View */
                $data = [
                    'campaign'      => $campaign,
                    'notifications' => $notifications,
                    'notifications_total' => $total_rows,
                    'pagination'    => $pagination,
                    'filters' => $filters
                ];

                $view = new \Altum\Views\View('campaign/' . $method . '.method', (array) $this);

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
                    'impression'        => 0,
                    'hover'             => 0,
                    'click'             => 0,
                    'form_submission'   => 0
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
                        `campaign_id` = {$campaign->campaign_id}
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
                        ];
                    }

                    $logs_chart[$row->formatted_date][$row->type] = $row->total;

                    /* Count totals */
                    if(in_array($row->type, ['impression', 'hover', 'click', 'form_submission'])) {
                        $logs_total[$row->type] += $row->total;
                    }
                }

                $logs_chart = get_chart_data($logs_chart);

                /* Prepare the method View */
                $data = [
                    'campaign'      => $campaign,
                    'logs'          => $logs,
                    'logs_chart'    => $logs_chart,
                    'logs_total'    => $logs_total,
                    'date'          => $date,
                ];

                $view = new \Altum\Views\View('campaign/' . $method . '.method', (array) $this);

                $this->add_view_content('method', $view->run($data));

                break;
        }

        /* Custom Branding Campaign Modal */
        if($this->user->plan_settings->custom_branding) {
            $data = ['campaign' => $campaign];
            $view = new \Altum\Views\View('campaign/custom_branding_campaign_modal', (array) $this);
            \Altum\Event::add_content($view->run($data), 'modals');
        }

        /* Delete Campaign Modal */
        $view = new \Altum\Views\View('campaign/campaign_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Pixel Modal */
        $view = new \Altum\Views\View('campaign/campaign_pixel_key_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Update Campaign Modal */
        $view = new \Altum\Views\View('campaign/update_campaign_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Notification Modal */
        $view = new \Altum\Views\View('notification/notification_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the View */
        $data = [
            'campaign'      => $campaign,
            'method'        => $method
        ];

        $view = new \Altum\Views\View('campaign/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->campaign->title, $campaign->name));

    }

    public function delete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $campaign_id = (int) Database::clean_string($_POST['campaign_id']);

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            redirect('dashboard');
        }

        /* Make sure the campaign is created by the logged in user */
        if(!$campaign = Database::get(['campaign_id'], 'campaigns', ['user_id' => $this->user->user_id, 'campaign_id' => $campaign_id])) {
            redirect('dashboard');
        }

        if(empty($_SESSION['error'])) {

            /* Delete from database */
            $stmt = Database::$database->prepare("DELETE FROM `campaigns` WHERE `campaign_id` = ? AND `user_id` = ?");
            $stmt->bind_param('ss', $_POST['campaign_id'], $this->user->user_id);
            $stmt->execute();
            $stmt->close();

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('campaign_id=' . $_POST['campaign_id']);

            /* Success message */
            $_SESSION['success'][] = $this->language->campaign_delete_modal->success_message;

            redirect('dashboard');

        }

        die();
    }
}
