<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Language;
use MaxMind\Db\Reader;
use Unirest\Request;

class PixelTrack extends Controller {

    public function index() {

        if(!isset($_SERVER['HTTP_REFERER'])) {
            die();
        }

        /* Check against bots */
        $CrawlerDetect = new \Jaybizzle\CrawlerDetect\CrawlerDetect();

        if($CrawlerDetect->isCrawler()) {
            die();
        }

        /* Allowed types of requests to this endpoint */
        $allowed_types = ['track', 'notification', 'auto_capture', 'collector'];
        $date = \Altum\Date::$date;
        $domain = Database::clean_string(parse_url(trim(Database::clean_string($_SERVER['HTTP_REFERER'])))['host']);
        $pixel_key = Database::clean_string($_GET['pixel_key']);

        if(!isset($_GET['type']) || isset($_GET['type']) && !in_array($_GET['type'], $allowed_types)) {
            die();
        }

        /* Flatten everything recursively */
        $_GET = array_flatten($_GET);

        /* Clean all the received variables */
        foreach($_GET as $key => $value) {

            /* Whitelist */
            if(in_array($key, ['location'])) {
                continue;
            }

            $_GET[$key] = Database::clean_string($value);
        }

        $_GET['url'] = urldecode($_GET['url']);

        /* Get the details of the campaign from the database */
        $campaign = (new \Altum\Models\Campaign())->get_campaign_by_pixel_key($pixel_key);

        /* Make sure the campaign has access */
        if(!$campaign) {
            die();
        }

        if(
            !$campaign->is_enabled
            || ($campaign->include_subdomains && !string_ends_with($campaign->domain, $domain))
            || (!$campaign->include_subdomains && $campaign->domain != $domain && $campaign->domain != 'www.' . $domain)
        ) {
            die();
        }

        /* Make sure to get the user data and confirm the user is ok */
        $user = (new \Altum\Models\User())->get_user_by_user_id($campaign->user_id);

        if(!$user) {
            die();
        }

        if(!$user->active) {
            die();
        }

        /* Make sure the user's plan is not already expired */
        if((new \DateTime()) > (new \DateTime($user->plan_expiration_date)) && $user->plan_id != 'free') {
            die();
        }

        /* Make sure that the user didnt exceed the current plan */
        if($user->plan_settings->notifications_impressions_limit != -1 && $user->current_month_notifications_impressions >= $user->plan_settings->notifications_impressions_limit) {
            die();
        }

        switch($_GET['type']) {

            /* Tracking the notifications states, impressions, hovers..etc */
            case 'notification':

                $_GET['notification_id'] = (int) $_GET['notification_id'];
                $_GET['subtype'] = in_array(
                    $_GET['subtype'],
                    [
                        'hover',
                        'impression',
                        'click',
                        'feedback_emoji_angry',
                        'feedback_emoji_sad',
                        'feedback_emoji_neutral',
                        'feedback_emoji_happy',
                        'feedback_emoji_excited',
                        'feedback_score_1',
                        'feedback_score_2',
                        'feedback_score_3',
                        'feedback_score_4',
                        'feedback_score_5'
                    ]
                ) ? $_GET['subtype'] : false;

                /* Make sure the type of notification is the correct one */
                if(!$_GET['subtype']) {
                    die();
                }

                /* Make sure the notification provided is a child of the campaign, exists and is enabled */
                if(!$notification = Database::get(['campaign_id', 'notification_id'], 'notifications', ['notification_id' => $_GET['notification_id'], 'campaign_id' => $campaign->campaign_id, 'is_enabled' => 1])) {
                    die();
                }

                /* Insert or update the log */
                $stmt = Database::$database->prepare("
                    INSERT INTO 
                        `track_notifications` (`notification_id`, `campaign_id`, `type`, `url`, `datetime`) 
                    VALUES 
                        (?, ?, ?, ?, ?)
                ");
                $stmt->bind_param(
                    'sssss',
                    $notification->notification_id,
                    $notification->campaign_id,
                    $_GET['subtype'],
                    $_GET['url'],
                    $date
                );
                $stmt->execute();
                $stmt->close();

                /* Count it in the users account if its an impression */
                if($_GET['subtype'] == 'impression') {
                    $stmt = Database::$database->prepare("UPDATE `users` SET `current_month_notifications_impressions` = `current_month_notifications_impressions` + 1, `total_notifications_impressions` = `total_notifications_impressions` + 1 WHERE `user_id` = ?");
                    $stmt->bind_param('s', $campaign->user_id);
                    $stmt->execute();
                    $stmt->close();
                }

                break;

            /* Tracking the visits of the user */
            case 'track':

                /* Generate an id for the log */
                $ip = get_ip();
                $ip_binary = $ip ? inet_pton($ip) : null;

                /* Insert or update the log */
                $stmt = Database::$database->prepare("
                    INSERT INTO 
                        `track_logs` (`user_id`, `domain`, `url`, `ip_binary`, `datetime`) 
                    VALUES 
                        (?, ?, ?, ?, ?)   
                ");
                $stmt->bind_param(
                    'sssss',
                    $campaign->user_id,
                    $domain,
                    $_GET['url'],
                    $ip_binary,
                    $date
                );
                $stmt->execute();
                $stmt->close();

                break;

            /* Getting the data from the email collector form */
            case 'collector':

                $_GET['notification_id'] = (int) $_GET['notification_id'];

                /* Determine if we have email or input keys */
                $collector_key = false;

                if(isset($_GET['email']) && !empty($_GET['email'])) {
                    $collector_key = 'email';

                    /* Make sure that what we got is an actual email */
                    if(!filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
                        die();
                    }
                }

                if(isset($_GET['input']) && !empty($_GET['input'])) {
                    $collector_key = 'input';
                }

                if(!$collector_key) {
                    die();
                }

                /* Make sure that the data is not already submitted and exists for this notification */
                $result = Database::$database->query("SELECT `id` FROM `track_conversions` WHERE `notification_id` = {$_GET['notification_id']} AND JSON_EXTRACT(`data`, '$.{$collector_key}') = '{$_GET[$collector_key]}'");

                if($result->num_rows) {
                    die();
                }

                /* Detect the location */
                $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-City.mmdb'))->get(get_ip());
                $country_code = $maxmind && $maxmind['country']['iso_code'] ? $maxmind['country']['iso_code'] : null;
                $city_name =  $maxmind && $maxmind['city']['names']['en'] ? $maxmind['city']['names']['en'] : null;

                $location_data = json_encode(
                    [
                        'city' => $city_name,
                        'country_code' => $country_code,
                        'country' => get_country_from_country_code($country_code)
                    ]
                );

                /* Data for the conversion */
                $data = json_encode([
                    $collector_key => $_GET[$collector_key]
                ]);

                /* Insert the conversion log */
                $stmt = Database::$database->prepare("
                    INSERT INTO
                        `track_conversions` (`notification_id`, `type`, `data`, `url`, `location`, `datetime`)
                    VALUES
                        (?, ?, ?, ?, ?, ?)
                ");
                $stmt->bind_param(
                    'ssssss',
                    $_GET['notification_id'],
                    $_GET['type'],
                    $data,
                    $_GET['url'],
                    $location_data,
                    $date
                );
                $stmt->execute();
                $stmt->close();

                /* Insert the log in the notification tracking table */
                /* Generate an id for the log */
                $type = 'form_submission';

                /* Insert or update the log */
                $stmt = Database::$database->prepare("
                    INSERT INTO
                        `track_notifications` (`notification_id`, `campaign_id`, `type`, `url`, `datetime`)
                    VALUES
                        (?, ?, ?, ?, ?)
                ");
                $stmt->bind_param(
                    'sssss',
                    $_GET['notification_id'],
                    $campaign->campaign_id,
                    $type,
                    $_GET['url'],
                    $date
                );
                $stmt->execute();
                $stmt->close();

                /* Make sure to send the webhook of the conversion */
                $notification = Database::$database->query("SELECT `notifications`.`name`, `notifications`.`settings`, `campaigns`.`name` AS `campaign_name` FROM `notifications` LEFT JOIN `campaigns` ON `campaigns`.`campaign_id` = `notifications`.`campaign_id`  WHERE `notification_id` = {$_GET['notification_id']}")->fetch_object();
                $notification->settings = json_decode($notification->settings);

                /* Only send if we need to */
                if($notification->settings->data_send_is_enabled) {

                    /* Webhook POST to the url the user specified */
                    if(!empty($notification->settings->data_send_webhook)) {

                        /* Send the webhook with the caught details */
                        $body = Request\Body::form([$collector_key => $_GET[$collector_key]]);

                        $response = Request::post($notification->settings->data_send_webhook, [], $body);
                    }

                    /* Send email to the url the user specified */
                    if(!empty($notification->settings->data_send_email)) {

                        /* Get the language for the user */
                        $language = Language::get($user->language);

                        /* Prepare the html for the email body */
                        $email_body = '<ul>';
                        foreach(array_merge(json_decode($location_data, true), json_decode($data, true), ['ip' => get_ip(), 'url' => $_GET['url']]) as $key => $value) {
                            $email_body .= '<li><strong>' . $key . ':</strong>' . ' ' . $value;
                        }
                        $email_body .= '</ul>';

                        send_mail(
                            $this->settings,
                            $notification->settings->data_send_email,
                            sprintf($language->global->emails->user_data_send->subject, $notification->campaign_name, $notification->name),
                            sprintf($language->global->emails->user_data_send->body, $notification->campaign_name, $notification->name, $email_body)
                        );

                    }

                }

                break;

            /* Auto Capturing data from forms */
            case 'auto_capture':

                $_GET['notification_id'] = (int) $_GET['notification_id'];

                /* Make sure to get only the needed data from the submission */
                $data = [];

                /* Save only parameters that start with "form_" */
                foreach($_GET as $key => $value) {
                    if(strpos($key, 'form_') === 0) {
                        $data[str_replace('form_', '', $key)] = $value;
                    }
                }

                /* Data for the conversion */
                $data = json_encode($data);

                /* Detect the location */
                $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-City.mmdb'))->get(get_ip());
                $country_code = $maxmind && $maxmind['country']['iso_code'] ? $maxmind['country']['iso_code'] : null;
                $city_name =  $maxmind && $maxmind['city']['names']['en'] ? $maxmind['city']['names']['en'] : null;

                $location_data = json_encode(
                    [
                        'city' => $city_name,
                        'country_code' => $country_code,
                        'country' => get_country_from_country_code($country_code)
                    ]
                );

                /* Insert the conversion log */
                $stmt = Database::$database->prepare("
                    INSERT INTO 
                        `track_conversions` (`notification_id`, `type`, `data`, `url`, `location`, `datetime`) 
                    VALUES 
                        (?, ?, ?, ?, ?, ?)
                ");
                $stmt->bind_param(
                    'ssssss',
                    $_GET['notification_id'],
                    $_GET['type'],
                    $data,
                    $_GET['url'],
                    $location_data,
                    $date
                );
                $stmt->execute();
                $stmt->close();

                /* Insert the log in the notification tracking table */
                /* Generate an id for the log */
                $type = 'auto_capture';

                /* Insert or update the log */
                $stmt = Database::$database->prepare("
                    INSERT INTO 
                        `track_notifications` (`notification_id`, `campaign_id`, `type`, `url`, `datetime`) 
                    VALUES 
                        (?, ?, ?, ?, ?)
                ");
                $stmt->bind_param(
                    'sssss',
                    $_GET['notification_id'],
                    $campaign->campaign_id,
                    $type,
                    $_GET['url'],
                    $date
                );
                $stmt->execute();
                $stmt->close();

                /* Update the notification with the last conversion date */
                $stmt = Database::$database->prepare("UPDATE `notifications` SET `last_action_date` = ? WHERE `notification_id` = ? ");
                $stmt->bind_param(
                    'ss',
                    $date,
                    $_GET['notification_id']
                );
                $stmt->execute();
                $stmt->close();

                break;
        }

    }

}
