<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class NotificationCreate extends Controller {

    public function index() {

        Authentication::guard();

        $campaign_id = isset($this->params[0]) ? (int) $this->params[0] : false;

        /* Make sure the campaign exists and is accessible to the user */
        if(!$campaign = Database::get('*', 'campaigns', ['campaign_id' => $campaign_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        /* Make sure that the user didn't exceed the limit */
        $user_notifications_total = Database::$database->query("SELECT COUNT(*) AS `total` FROM `notifications` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total;

        if($this->user->plan_settings->notifications_limit != -1 && $user_notifications_total >= $this->user->plan_settings->notifications_limit) {
            $_SESSION['error'][] = $this->language->notification->error_message->notifications_limit;
            redirect('dashboard');
        }

        /* Prepare the View */
        $data = [
            'campaign' => $campaign,
            'notifications' => \Altum\Notification::get_config(),
        ];

        $view = new \Altum\Views\View('notification-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
