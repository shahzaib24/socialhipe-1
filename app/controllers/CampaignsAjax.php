<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;

class CampaignsAjax extends Controller {

    public function index() {

        Authentication::guard();

        if(!empty($_POST) && (Csrf::check('token') || Csrf::check('global_token')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Status toggle */
                case 'is_enabled_toggle': $this->is_enabled_toggle(); break;

                /* Custom Branding Set */
                case 'custom_branding': $this->custom_branding(); break;

                /* Create */
                case 'create': $this->create(); break;

                /* Update */
                case 'update': $this->update(); break;

            }

        }

        die();
    }

    private function is_enabled_toggle() {
        $_POST['campaign_id'] = (int) $_POST['campaign_id'];

        /* Get the current status */
        $is_enabled = Database::simple_get('is_enabled', 'campaigns', ['campaign_id' => $_POST['campaign_id']]);

        if($is_enabled !== false) {

            $new_is_enabled = (int) !$is_enabled;

            Database::$database->query("UPDATE `campaigns` SET `is_enabled` = {$new_is_enabled} WHERE `user_id` = {$this->user->user_id} AND `campaign_id` = {$_POST['campaign_id']}");

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('campaign_id=' . $_POST['campaign_id']);

            Response::json('', 'success');

        }
    }

    private function custom_branding() {
        $_POST['campaign_id'] = (int) $_POST['campaign_id'];
        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $_POST['url'] = trim(Database::clean_string($_POST['url']));

        /* Check for possible errors */
        if(!isset($_POST['name'], $_POST['url'])) {
            $errors[] = $this->language->global->error_message->empty_fields;
        }

        /* Make sure the user has access to the custom branding method */
        if(!$this->user->plan_settings->custom_branding) {
            $errors[] = true;
        }

        if(empty($errors)) {

            $campaign_branding = json_encode([
                'name' => $_POST['name'],
                'url'   => $_POST['url']
            ]);

            /* Update data in database */
            $stmt = Database::$database->prepare("UPDATE `campaigns` SET `branding` = ? WHERE `campaign_id` = ?");
            $stmt->bind_param('ss', $campaign_branding, $_POST['campaign_id']);
            $stmt->execute();
            $stmt->close();

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('campaign_id=' . $_POST['campaign_id']);

            Response::json($this->language->global->success_message->basic, 'success');

        }
    }

    private function create() {
        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $_POST['include_subdomains'] = (int) isset($_POST['include_subdomains']);
        $is_enabled = 1;

        /* Domain checking */
        $pslManager = new \Pdp\PublicSuffixListManager();
        $parser = new \Pdp\Parser($pslManager->getList());
        $url = $parser->parseUrl($_POST['domain']);
        $punnnycode = new \TrueBV\Punycode();
        $_POST['domain'] = Database::clean_string($punnnycode->encode($url->getHost()));

        /* Check for possible errors */
        if(empty($_POST['name']) || empty($_POST['domain'])) {
            $errors[] = $this->language->global->error_message->empty_fields;
        }

        /* Make sure that the user didn't exceed the limit */
        $account_total_campaigns = Database::$database->query("SELECT COUNT(*) AS `total` FROM `campaigns` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total;
        if($this->user->plan_settings->campaigns_limit != -1 && $account_total_campaigns >= $this->user->plan_settings->campaigns_limit) {
            $errors[] = $this->language->create_campaign_modal->error_message->campaigns_limit;
        }

        /* Generate an unique pixel key for the website */
        $pixel_key = string_generate(32);
        while(Database::exists('pixel_key', 'campaigns', ['pixel_key' => $pixel_key])) {
            $pixel_key = string_generate(32);
        }

        if(empty($errors)) {

            /* Insert to database */
            $stmt = Database::$database->prepare("INSERT INTO `campaigns` (`user_id`, `pixel_key`, `name`, `domain`, `include_subdomains`, `is_enabled`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssss', $this->user->user_id, $pixel_key, $_POST['name'], $_POST['domain'], $_POST['include_subdomains'], $is_enabled, Date::$date);
            $stmt->execute();
            $campaign_id = $stmt->insert_id;
            $stmt->close();

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('campaign_id=' . $campaign_id);

            Response::json($this->language->create_campaign_modal->success_message->created, 'success', ['campaign_id' => $campaign_id]);

        }
    }

    private function update() {
        $_POST['campaign_id'] = (int) $_POST['campaign_id'];
        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $_POST['include_subdomains'] = (int) (bool) isset($_POST['include_subdomains']);

        /* Domain checking */
        $pslManager = new \Pdp\PublicSuffixListManager();
        $parser = new \Pdp\Parser($pslManager->getList());
        $url = $parser->parseUrl($_POST['domain']);
        $punnnycode = new \TrueBV\Punycode();
        $_POST['domain'] = Database::clean_string($punnnycode->encode($url->getHost()));

        /* Check for possible errors */
        if(empty($_POST['name']) || empty($_POST['domain'])) {
            Response::json($this->language->global->error_message->empty_fields, 'error');
        }

        if(empty($errors)) {

            /* Insert to database */
            $stmt = Database::$database->prepare("UPDATE `campaigns` SET `name` = ?, `domain` = ?, `include_subdomains` = ? WHERE `campaign_id` = ? AND `user_id` = ?");
            $stmt->bind_param('sssss', $_POST['name'], $_POST['domain'], $_POST['include_subdomains'], $_POST['campaign_id'], $this->user->user_id);
            $stmt->execute();
            $stmt->close();

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('campaign_id=' . $_POST['campaign_id']);

            Response::json($this->language->update_campaign_modal->success_message->updated, 'success');

        }
    }

}
