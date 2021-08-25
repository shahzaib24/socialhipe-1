<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class AdminIndex extends Controller {

    public function index()
    {

        Authentication::guard('admin');

        $notifications = Database::$database->query("SELECT SUM(`current_month_notifications_impressions`) AS `current_month_notifications_impressions` FROM `users`")->fetch_object();
        $total_track_notifications = $this->database->query("SELECT MAX(`id`) AS `total` FROM `track_notifications`")->fetch_object()->total ?? 0;

        $users = Database::$database->query("
            SELECT
              (SELECT COUNT(*) FROM `users` WHERE MONTH(`last_activity`) = MONTH(CURRENT_DATE()) AND YEAR(`last_activity`) = YEAR(CURRENT_DATE())) AS `active_users_month`,
              (SELECT COUNT(*) FROM `users`) AS `active_users`
        ")->fetch_object();

        if(in_array($this->settings->license->type, ['SPECIAL','Extended License'])) {
            $payments = Database::$database->query("SELECT COUNT(*) AS `payments`, IFNULL(TRUNCATE(SUM(`total_amount`), 2), 0) AS `earnings` FROM `payments` WHERE `currency` = '{$this->settings->payment->currency}'")->fetch_object();

            /* Data for the months transactions and earnings */
            $payments_month = Database::$database->query("SELECT COUNT(*) AS `payments`, IFNULL(TRUNCATE(SUM(`total_amount`), 2), 0) AS `earnings` FROM `payments` WHERE `currency` = '{$this->settings->payment->currency}' AND MONTH(`date`) = MONTH(CURRENT_DATE()) AND YEAR(`date`) = YEAR(CURRENT_DATE())")->fetch_object();

        } else {
            $payments = $payments_month = null;
        }

        /* Login Modal */
        $view = new \Altum\Views\View('admin/users/user_login_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/users/user_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'notifications' => $notifications,
            'total_track_notifications' => $total_track_notifications,
            'users' => $users,
            'payments' => $payments,
            'payments_month' => $payments_month
        ];

        $view = new \Altum\Views\View('admin/index/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
