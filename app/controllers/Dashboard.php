<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Models\Plan;
use Altum\Routing\Router;

class Dashboard extends Controller {

    public function index() {

        Authentication::guard();

        /* Delete Campaign Modal */
        $view = new \Altum\Views\View('campaign/campaign_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Create Campaign Modal */
        $view = new \Altum\Views\View('campaign/create_campaign_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Update Campaign Modal */
        $view = new \Altum\Views\View('campaign/update_campaign_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Custom Branding Campaign Modal */
        if($this->user->plan_settings->custom_branding) {
            $view = new \Altum\Views\View('campaign/custom_branding_campaign_modal', (array)$this);
            \Altum\Event::add_content($view->run(), 'modals');
        }

        /* Pixel Modal */
        $view = new \Altum\Views\View('campaign/campaign_pixel_key_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled'], ['name', 'domain'], ['name', 'date']));

        /* Prepare the paginator */
        $total_rows = Database::$database->query("SELECT COUNT(*) AS `total` FROM `campaigns` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('dashboard?' . $filters->get_get() . '&page=%d')));

        /* Get the campaigns list for the user */
        $campaigns = [];
        $campaigns_result = Database::$database->query("SELECT * FROM `campaigns` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
        while($row = $campaigns_result->fetch_object()) $campaigns[] = $row;

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the View */
        $data = [
            'campaigns' => $campaigns,
            'campaigns_total' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Altum\Views\View('dashboard/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
