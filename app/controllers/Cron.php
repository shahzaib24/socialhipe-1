<?php

namespace Altum\Controllers;


use Altum\Database\Database;
use Altum\Date;

class Cron extends Controller {

    public function index() {

        /* Initiation */
        set_time_limit(0);

        /* Make sure the key is correct */
        if(!isset($_GET['key']) || (isset($_GET['key']) && $_GET['key'] != $this->settings->cron->key)) {
            die();
        }

        /* Make sure the reset date month is different than the current one to avoid double resetting */
        $reset_date = (new \DateTime($this->settings->cron->reset_date))->format('m');
        $current_date = (new \DateTime())->format('m');

        if($reset_date == $current_date) {
            die();
        }

        /* Clean the track_logs table */
        $activity_date = (new \DateTime())->modify('-30 day')->format('Y-m-d H:i:s');
        Database::$database->query("DELETE FROM `track_logs` WHERE `datetime` < '{$activity_date}'");

        /* Reset the users notification impressions */
        Database::$database->query("UPDATE `users` SET `current_month_notifications_impressions` = 0");

        /* Update the settings with the updated time */
        $cron_settings = json_encode([
            'key' => $this->settings->cron->key,
            'reset_date' => Date::$date
        ]);

        Database::$database->query("UPDATE `settings` SET `value` = '{$cron_settings}' WHERE `key` = 'cron'");

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItem('settings');

    }

}
