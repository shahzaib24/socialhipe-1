<?php

namespace Altum\Controllers;


class Index extends Controller {

    public function index() {

        /* Custom index redirect if set */
        if(!empty($this->settings->index_url)) {
            header('Location: ' . $this->settings->index_url);
            die();
        }

        $total_track_notifications = $this->database->query("SELECT MAX(`id`) AS `total` FROM `track_notifications`")->fetch_object()->total ?? 0;
        $total_notifications = $this->database->query("SELECT MAX(`notification_id`) AS `total` FROM `notifications`")->fetch_object()->total ?? 0;

        /* Plans View */
        $data = [
            'simple_plan_settings' => [
                'no_ads',
                'removable_branding',
                'custom_branding',
            ],
        ];

        $view = new \Altum\Views\View('partials/plans', (array) $this);

        $this->add_view_content('plans', $view->run($data));


        /* Main View */
        $data = [
            'notifications' => \Altum\Notification::get_config(),
            'total_track_notifications' => $total_track_notifications,
            'total_notifications' => $total_notifications
        ];

        $view = new \Altum\Views\View('index/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
