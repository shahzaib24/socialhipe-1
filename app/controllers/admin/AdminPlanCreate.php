<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Csrf;
use Altum\Middlewares\Authentication;

class AdminPlanCreate extends Controller {

    public function index() {

        Authentication::guard('admin');

        if(in_array($this->settings->license->type, ['SPECIAL','Extended License'])) {
            /* Get the available taxes from the system */
            $taxes = [];

            $result = $this->database->query("SELECT `tax_id`, `internal_name`, `name`, `description` FROM `taxes`");

            while ($row = $result->fetch_object()) {
                $taxes[] = $row;
            }
        }

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = Database::clean_string($_POST['name']);
            $_POST['monthly_price'] = (float) $_POST['monthly_price'];
            $_POST['annual_price'] = (float) $_POST['annual_price'];
            $_POST['lifetime_price'] = (float) $_POST['lifetime_price'];

            /* Determine the enabled notifications */
            $enabled_notifications = [];

            foreach(array_keys(\Altum\Notification::get_config()) as $notification) {
                $enabled_notifications[$notification] = (bool) isset($_POST['enabled_notifications']) && in_array($notification, $_POST['enabled_notifications']);
            }

            $_POST['settings'] = json_encode([
                'no_ads'                            => (bool) isset($_POST['no_ads']),
                'removable_branding'                => (bool) isset($_POST['removable_branding']),
                'custom_branding'                   => (bool) isset($_POST['custom_branding']),
                'campaigns_limit'                   => (int) $_POST['campaigns_limit'],
                'notifications_limit'               => (int) $_POST['notifications_limit'],
                'notifications_impressions_limit'   => (int) $_POST['notifications_impressions_limit'],
                'enabled_notifications'             => $enabled_notifications
            ]);
            $_POST['status'] = (int) $_POST['status'];
            $_POST['taxes_ids'] = json_encode(array_keys($_POST['taxes_ids'] ?? []));

            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(empty($_SESSION['error'])) {
                /* Update the database */
                $stmt = Database::$database->prepare("INSERT INTO `plans` (`name`, `monthly_price`, `annual_price`, `lifetime_price`, `settings`, `taxes_ids`, `status`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('ssssssss', $_POST['name'], $_POST['monthly_price'], $_POST['annual_price'], $_POST['lifetime_price'], $_POST['settings'], $_POST['taxes_ids'], $_POST['status'], Date::$date);
                $stmt->execute();
                $stmt->close();

                /* Set a nice success message */
                $_SESSION['success'][] = $this->language->global->success_message->basic;

                redirect('admin/plans');
            }
        }


        /* Main View */
        $data = [
            'taxes' => $taxes ?? null,
            'notifications' => \Altum\Notification::get_config(),
        ];

        $view = new \Altum\Views\View('admin/plan-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
