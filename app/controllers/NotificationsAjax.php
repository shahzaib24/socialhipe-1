<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Notification;
use Altum\Response;

class NotificationsAjax extends Controller {

    public function index() {

        Authentication::guard();

        if(!empty($_POST) && (Csrf::check('token') || Csrf::check('global_token')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Status toggle */
                case 'is_enabled_toggle': $this->is_enabled_toggle(); break;

                /* Create */
                case 'create': $this->create(); break;

                /* Get conversion data */
                case 'read_data_conversion': $this->read_data_conversion(); break;

            }

        }

        if(!empty($_GET) && (Csrf::check('token') || Csrf::check('global_token')) && isset($_GET['request_type'])) {

            switch($_GET['request_type']) {

                /* Get conversion data */
                case 'read_data_conversion': $this->read_data_conversion(); break;

            }

        }

        die();
    }

    private function is_enabled_toggle() {
        $_POST['notification_id'] = (int)$_POST['notification_id'];

        /* Get the current status */
        $is_enabled = Database::simple_get('is_enabled', 'notifications', ['notification_id' => $_POST['notification_id']]);

        if($is_enabled !== false) {

            $new_is_enabled = (int) !$is_enabled;

            Database::$database->query("UPDATE `notifications` SET `is_enabled` = {$new_is_enabled} WHERE `user_id` = {$this->user->user_id} AND `notification_id` = {$_POST['notification_id']}");

            Response::json('', 'success');

        }
    }

    private function create() {
        $_POST['type'] = trim(Database::clean_string($_POST['type']));
        $_POST['campaign_id'] = (int) $_POST['campaign_id'];
        $is_enabled = 0;

        /* If the notification settings is not set it means we got an invalid type */
        if(!Notification::get_config($_POST['type'])) {
            $errors[] = true;
        }

        /* Check for possible errors */
        if(!Database::exists('campaign_id', 'campaigns', ['user_id' => $this->user->user_id, 'campaign_id' => $_POST['campaign_id']])) {
            $errors[] = true;
        }

        if(empty($_POST['type'])) {
            $errors[] = $this->language->global->error_message->empty_fields;
        }

        /* Check for permission of usage of the notification */
        if(!$this->user->plan_settings->enabled_notifications->{$_POST['type']}) {
            $errors[] = true;
        }

        if(empty($errors)) {
            /* Determine the default settings */
            $notification_settings = json_encode(Notification::get_config($_POST['type']));
            $notification_key = md5($this->user->user_id . $_POST['campaign_id'] . $_POST['type'] . time());
            $name = $this->language->notification_create->default_name;

            /* Insert to database */
            $stmt = Database::$database->prepare("INSERT INTO `notifications` (`campaign_id`, `user_id`, `name`, `type`, `settings`, `notification_key`, `is_enabled`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssssss', $_POST['campaign_id'], $this->user->user_id, $name, $_POST['type'], $notification_settings, $notification_key, $is_enabled, \Altum\Date::$date);
            $stmt->execute();
            $notification_id = $stmt->insert_id;
            $stmt->close();

            Response::json($this->language->create_campaign_modal->success_message->created, 'success', ['notification_id' => $notification_id]);
        }
    }

    private function read_data_conversion() {
        $_GET['notification_id'] = (int)$_GET['notification_id'];
        $_GET['id'] = (int)$_GET['id'];

        /* Get the current status */
        $user_id = Database::simple_get('user_id', 'notifications', ['notification_id' => $_GET['notification_id']]);

        if($user_id && $user_id == $this->user->user_id) {

            /* Get the data from the conversions table */
            $conversion = Database::$database->query("SELECT `type`, `data`, `location`, `url` FROM `track_conversions` WHERE `id` = {$_GET['id']} AND `notification_id` = {$_GET['notification_id']}")->fetch_object() ?? null;

            if($conversion) {

                $conversion->data = json_decode($conversion->data);
                $conversion->location = !empty($conversion->location) ? json_decode($conversion->location) : null;

                /* Generate the view */
                $data = [
                    'conversion' => $conversion,
                ];
                $view = new \Altum\Views\View('notification/data/data.read_conversion.method', (array) $this);

                Response::json('', 'success', ['html' => $view->run($data)]);
            }

        }
    }

}
