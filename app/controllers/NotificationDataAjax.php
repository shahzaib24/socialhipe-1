<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;

class NotificationDataAjax extends Controller {

    public function index() {

        Authentication::guard();

        if(!empty($_POST) && (Csrf::check('token') || Csrf::check('global_token')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Create */
                case 'create': $this->create(); break;

                /* Delete */
                case 'delete': $this->delete(); break;

            }

        }

        die();
    }

    private function create() {
        $_POST['notification_id'] = (int) $_POST['notification_id'];
        $type = 'imported';

        /* Check for possible errors */
        if(!Database::exists(['notification_id'], 'notifications', ['user_id' => $this->user->user_id, 'notification_id' => $_POST['notification_id']])) {
            $errors[] = true;
        }

        if(empty($_POST['key']) && empty($_POST['value'])) {
            $errors[] = true;
        }

        if(empty($errors)) {

            /* Parse the keys and values */
            $data = [];
            foreach($_POST['key'] as $key => $value) {

                if(!empty($_POST['key'][$key]) && isset($_POST['value'][$key])) {
                    $cleaned_value = Database::clean_string($value);

                    $data[$cleaned_value] = Database::clean_string($_POST['value'][$key]);
                }

            }

            $data = json_encode($data);

            /* Insert in the database */
            $stmt = Database::$database->prepare("INSERT INTO `track_conversions` (`notification_id`, `type`, `data`, `datetime`) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $_POST['notification_id'], $type, $data, \Altum\Date::$date);
            $stmt->execute();
            $stmt->close();

            Response::json('', 'success');

        }
    }

    private function delete() {
        $_POST['id'] = (int) $_POST['id'];

        /* Check for possible errors */
        if(!$notification_id = Database::simple_get('notification_id', 'track_conversions', ['id' => $_POST['id']])) {
            $errors[] = true;
        }

        if(!Database::exists('notification_id', 'notifications', ['notification_id' => $notification_id, 'user_id' => $this->user->user_id])) {
            $errors[] = true;
        }

        if(empty($errors)) {

            /* Delete from database */
            $stmt = Database::$database->prepare("DELETE FROM `track_conversions` WHERE `id` = ?");
            $stmt->bind_param('s', $_POST['id']);
            $stmt->execute();
            $stmt->close();

            Response::json('', 'success');

        }
    }

}
