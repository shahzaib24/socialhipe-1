<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class AdminStatistics extends Controller {
    public $type;
    public $date;

    public function index() {

        Authentication::guard('admin');

        $this->type = (isset($this->params[0])) && in_array($this->params[0], ['payments', 'growth', 'notifications']) ? Database::clean_string($this->params[0]) : 'growth';

        $start_date = isset($_GET['start_date']) ? Database::clean_string($_GET['start_date']) : (new \DateTime())->modify('-30 day')->format('Y-m-d');
        $end_date = isset($_GET['end_date']) ? Database::clean_string($_GET['end_date']) : (new \DateTime())->format('Y-m-d');

        $this->date = \Altum\Date::get_start_end_dates($start_date, $end_date);

        /* Process only data that is needed for that specific page */
        $type_data = $this->{$this->type}();

        /* Main View */
        $data = [
            'type' => $this->type,
            'date' => $this->date
        ];
        $data = array_merge($data, $type_data);

        $view = new \Altum\Views\View('admin/statistics/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    protected function payments() {

        $select_date_format_query = $this->date->start_date == $this->date->end_date ? "DATE_FORMAT(`date`, '%Y-%m-%d %H')" : "DATE_FORMAT(`date`, '%Y-%m-%d')";

        $payments_chart = [];
        $result = $this->database->query("SELECT COUNT(*) AS `total_payments`, {$select_date_format_query} AS `formatted_date`, TRUNCATE(SUM(`total_amount`), 2) AS `total_amount` FROM `payments` WHERE `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {

            if($this->date->start_date == $this->date->end_date) {
                $formatted_date = explode(' ', $row->formatted_date);
                $row->formatted_date = ((new \DateTime($formatted_date[0]))->setTime($formatted_date[1], 0)->setTimezone(new \DateTimeZone(\Altum\Date::$timezone))->format('H A'));
            } else {
                $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);
            }

            $payments_chart[$row->formatted_date] = [
                'total_amount' => $row->total_amount,
                'total_payments' => $row->total_payments
            ];

        }

        $payments_chart = get_chart_data($payments_chart);

        return [
            'payments_chart' => $payments_chart
        ];

    }

    protected function growth() {

        $select_date_format_query = $this->date->start_date == $this->date->end_date ? "DATE_FORMAT(`date`, '%Y-%m-%d %H')" : "DATE_FORMAT(`date`, '%Y-%m-%d')";

        /* Users */
        $users_chart = [];
        $result = $this->database->query("
            SELECT
                 COUNT(*) AS `total`,
                 {$select_date_format_query} AS `formatted_date`
            FROM
                 `users`
            WHERE
                `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            if($this->date->start_date == $this->date->end_date) {
                $formatted_date = explode(' ', $row->formatted_date);
                $row->formatted_date = ((new \DateTime($formatted_date[0]))->setTime($formatted_date[1], 0)->setTimezone(new \DateTimeZone(\Altum\Date::$timezone))->format('H A'));
            } else {
                $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);
            }

            $users_chart[$row->formatted_date] = [
                'users' => $row->total
            ];
        }

        $users_chart = get_chart_data($users_chart);

        /* Campaigns */
        $campaigns_chart = [];
        $result = $this->database->query("
            SELECT
                 COUNT(*) AS `total`,
                 {$select_date_format_query} AS `formatted_date`
            FROM
                 `campaigns`
            WHERE
                `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            if($this->date->start_date == $this->date->end_date) {
                $formatted_date = explode(' ', $row->formatted_date);
                $row->formatted_date = ((new \DateTime($formatted_date[0]))->setTime($formatted_date[1], 0)->setTimezone(new \DateTimeZone(\Altum\Date::$timezone))->format('H A'));
            } else {
                $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);
            }

            $campaigns_chart[$row->formatted_date] = [
                'campaigns' => $row->total
            ];
        }

        $campaigns_chart = get_chart_data($campaigns_chart);

        /* Notifications */
        $notifications_chart = [];
        $result = $this->database->query("
            SELECT
                 COUNT(*) AS `total`,
                 {$select_date_format_query} AS `formatted_date`
            FROM
                 `notifications`
            WHERE
                `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            if($this->date->start_date == $this->date->end_date) {
                $formatted_date = explode(' ', $row->formatted_date);
                $row->formatted_date = ((new \DateTime($formatted_date[0]))->setTime($formatted_date[1], 0)->setTimezone(new \DateTimeZone(\Altum\Date::$timezone))->format('H A'));
            } else {
                $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);
            }

            $notifications_chart[$row->formatted_date] = [
                'notifications' => $row->total
            ];
        }

        $notifications_chart = get_chart_data($notifications_chart);

        /* Users logs */
        $users_logs_chart = [];
        $result = $this->database->query("
            SELECT
                 COUNT(*) AS `total`,
                 {$select_date_format_query} AS `formatted_date`
            FROM
                 `users_logs`
            WHERE
                `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            if($this->date->start_date == $this->date->end_date) {
                $formatted_date = explode(' ', $row->formatted_date);
                $row->formatted_date = ((new \DateTime($formatted_date[0]))->setTime($formatted_date[1], 0)->setTimezone(new \DateTimeZone(\Altum\Date::$timezone))->format('H A'));
            } else {
                $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);
            }

            $users_logs_chart[$row->formatted_date] = [
                'users_logs' => $row->total
            ];
        }

        $users_logs_chart = get_chart_data($users_logs_chart);

        /* Redeemed codes */
        if(in_array($this->settings->license->type, ['SPECIAL', 'Extended License'])) {
            $redeemed_codes_chart = [];
            $result = $this->database->query("
                SELECT
                     COUNT(*) AS `total`,
                     {$select_date_format_query} AS `formatted_date`
                FROM
                     `redeemed_codes`
                WHERE
                    `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
                GROUP BY
                    `formatted_date`
                ORDER BY
                    `formatted_date`
            ");
            while($row = $result->fetch_object()) {

                if($this->date->start_date == $this->date->end_date) {
                    $formatted_date = explode(' ', $row->formatted_date);
                    $row->formatted_date = ((new \DateTime($formatted_date[0]))->setTime($formatted_date[1], 0)->setTimezone(new \DateTimeZone(\Altum\Date::$timezone))->format('H A'));
                } else {
                    $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);
                }

                $redeemed_codes_chart[$row->formatted_date] = [
                    'redeemed_codes' => $row->total
                ];
            }

            $redeemed_codes_chart = get_chart_data($redeemed_codes_chart);
        }

        return [
            'users_chart' => $users_chart,
            'campaigns_chart' => $campaigns_chart,
            'notifications_chart' => $notifications_chart,
            'users_logs_chart' => $users_logs_chart,
            'redeemed_codes_chart' => $redeemed_codes_chart ?? null
        ];
    }
    protected function notifications() {

        $select_date_format_query = $this->date->start_date == $this->date->end_date ? "DATE_FORMAT(`datetime`, '%Y-%m-%d %H')" : "DATE_FORMAT(`datetime`, '%Y-%m-%d')";

        /* Track notifications */
        $track_notifications_chart = [];
        $result = $this->database->query("    
            SELECT
                 `type`,
                 COUNT(`id`) AS `total`,
                 {$select_date_format_query} AS `formatted_date`
            FROM
                 `track_notifications`
            WHERE
                `datetime` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`,
                `type`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            /* Handle if the date key is not already set */
            if(!array_key_exists($row->formatted_date, $track_notifications_chart)) {
                $track_notifications_chart[$row->formatted_date] = [
                    'impression'        => 0,
                    'hover'             => 0,
                    'click'             => 0,
                    'form_submission'   => 0
                ];
            }

            $track_notifications_chart[$row->formatted_date][$row->type] = $row->total;

        }

        $track_notifications_chart = get_chart_data($track_notifications_chart);

        /* Track conversions */
        $track_conversions_chart = [];
        $result = $this->database->query("    
            SELECT
                 `type`,
                 COUNT(`id`) AS `total`,
                 {$select_date_format_query} AS `formatted_date`
            FROM
                 `track_conversions`
            WHERE
                `datetime` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`,
                `type`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            /* Handle if the date key is not already set */
            if(!array_key_exists($row->formatted_date, $track_conversions_chart)) {
                $track_conversions_chart[$row->formatted_date] = [
                    'webhook'        => 0,
                    'auto_capture'   => 0,
                    'collector'      => 0,
                ];
            }

            $track_conversions_chart[$row->formatted_date][$row->type] = $row->total;

        }

        $track_conversions_chart = get_chart_data($track_conversions_chart);


        return [
            'track_notifications_chart' => $track_notifications_chart,
            'track_conversions_chart' => $track_conversions_chart
        ];
    }

}
