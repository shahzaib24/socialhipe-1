<?php

namespace Altum\Controllers;

use Altum\Database\Database;

class PixelWebhook extends Controller {

    public function index() {

        $notification_key = isset($this->params[0]) ? Database::clean_string($this->params[0]) : false;
        $date = \Altum\Date::$date;

        /* Make sure the api key exists */
        if(!$notification = Database::get('*', 'notifications', ['notification_key' => $notification_key, 'is_enabled' => 1])) {
            die();
        }

        /* Make sure the $notification_key belongs to an active user */
        if(!$user_id = Database::simple_get('user_id', 'users', ['user_id' => $notification->user_id, 'active' => 1])) {
            die();
        }

        /* Check for JSON submitted payload */
        $payload = @json_decode(@file_get_contents('php://input'), true);

        if($payload) {

            $_POST = (array) $payload;

        }

        /* Flatten everything recursively */
        $_POST = array_flatten($_POST);

        /* Location */
        $location = [];

        /* Clean all the received variables */
        foreach($_POST as $key => $value) {
            $_POST[$key] = Database::clean_string($value);

            if($key == 'city') {
                $location['city'] = $_POST[$key];
                unset($_POST[$key]);
            }

            if($key == 'country') {
                $location['country'] = $_POST[$key];
                unset($_POST[$key]);
            }

            if($key == 'country_code') {
                $location['country_code'] = $_POST[$key];
                unset($_POST[$key]);
            }
        }

        /* Data for the conversion */
        $data = json_encode($_POST);
        $type = 'webhook';
        $url = '';
        $location = !empty($location) ? json_encode($location) : null;

        /* Make sure that the data is not already submitted and exists for this notification */
        $result = Database::$database->query("SELECT `id` FROM `track_conversions` WHERE `notification_id` = {$notification->notification_id} AND `data` = '{$data}'");

        if($result->num_rows) {
            die();
        }

        /* Insert the conversion log */
        $stmt = Database::$database->prepare("
            INSERT INTO 
                `track_conversions` (`notification_id`, `type`, `data`, `url`, `location`, `datetime`) 
            VALUES 
                (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            'ssssss',
            $notification->notification_id,
            $type,
            $data,
            $url,
            $location,
            $date
        );
        $stmt->execute();
        $stmt->close();

        /* Update the notification with the last conversion date */
        $stmt = Database::$database->prepare("UPDATE `notifications` SET `last_action_date` = ? WHERE `notification_id` = ? ");
        $stmt->bind_param(
            'ss',
            $date,
            $notification->notification_id
        );
        $stmt->execute();
        $stmt->close();

    }

}
