<?php defined('ALTUMCODE') || die() ?>


<div class="container-fluid bg-light-grey pb-6">
    <div class="row mr-1">
        
          <div class="col-lg-1 bg-light-grey text-center fs-esm">
              
              <div class="my-3">
                  <div class="p-3 bg-red rounded-circle">
              <img src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/campaign_icon.svg" class="img-fluid">
                  </div>
                   <div class="text-uppercase mt-3 text-5b"><?= $this->language->admin_statistics->growth->campaigns->chart ?></div>
              </div>
              
              
              
               <div class="my-3">
                <img src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/new_campaign_icon.svg" class="img-fluid">
                   <div class="text-uppercase mt-3 text-5b"><?= $this->language->admin_statistics->growth->campaigns->header ?></div>
              </div>
             
              
             
                  <div class="my-3">
                      <img src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/support_icon.svg" class="img-fluid">
                   <div class="text-uppercase mt-3 text-5b"><?= $this->language->admin_statistics->growth->campaigns->support ?></div>
              </div>
                            
              
                      <a class="my-3 btn bg-white shadow p-2 fs-esm font-weight-bold"><?= $this->language->account->plan->upgrade ?></a>

               </div>
        
        <div class="col-lg-11 bg-white rounded px-4 pb-5">



<header class="header bg-white">
        
        
        <div class="d-flex justify-content-between">
            <div class="col-8 p-0">
            <h1 class="h2"><span class=""><?= $this->language->dashboard->header ?></span></h1>
                </div>
<!--
             <div class="">
            <span class="badge badge-success h-fc"><?//= sprintf($this->language->account->plan->header, $this->user->plan->name) ?></span>
                 <span class="h-fc red"><u><?//= sprintf($this->language->account->plan->renew, $this->user->plan->name) ?></u></span>
                 
            </div>
-->
        
        
        
 <div class="col-4 p-0">
        <div class="bg-FO rounded-pill p-2 pr-3 d-flex fs-esm justify-content-between modal-dialog-centered text-capitalize text-5b">
            <div class="">
                <img src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/right_tick.svg" class="bg-green p-1 rounded-circle">
                create campaign
            </div>
            <div class="">
                 <img src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/right_tick.svg" class="bg-green p-1 rounded-circle">
                create notifications
            </div>
            <div class="rounded-circle right-tick-border p-2">2/2</div>
            </div>
     
        </div>
            
            
            </div>
    
    <div class="d-flex align-items-center mr-3 red fw-normal">
            <u><?= $data->campaign->domain ?></u>
        </div>
    
    
        <p>
           

            <?php if($this->user->plan_id != 'free'): ?>
                <span><?= sprintf($this->language->account->plan->subheader, '<strong>' . \Altum\Date::get($this->user->plan_expiration_date, 2) . '</strong>') ?></span>
            <?php endif ?>

            <?php if($this->settings->payment->is_enabled): ?>
                <span>(<a href="<?= url('plan/upgrade') ?>"><?= $this->language->account->plan->renew ?></a>)</span>
            <?php endif ?>
        </p>

        <?php if($this->user->plan_settings->notifications_impressions_limit != -1): ?>
            <?php
            $progress_percentage = $this->user->plan_settings->notifications_impressions_limit == '0' ? 100 : ($this->user->current_month_notifications_impressions / $this->user->plan_settings->notifications_impressions_limit) * 100;
            $progress_class = $progress_percentage > 60 ? ($progress_percentage > 85 ? 'badge-danger' : 'badge-warning') : 'badge-success';
            ?>
            <p class="text-muted">
                <?=
                    sprintf($this->language->account->plan->notifications_impressions_limit,
                        '<span class="badge ' . $progress_class . '">' . nr($progress_percentage) . '%</span>',
                        nr($this->user->plan_settings->notifications_impressions_limit)
                    );
                ?>
            </p>
        <?php endif ?>

</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>





<section class="container-fluid p-0">

    <?php display_notifications() ?>

    <div class="mt-5 d-flex justify-content-between">
        <h2 class="h3"><?= $this->language->dashboard->campaigns->header ?></h2>

        <div class="col-auto p-0 d-flex">
            
            
             <div class="mr-3">
                <div class="dropdown">
                    <button type="button" class="btn <?= count($data->filters->get) ? 'shadow-red' : 'shadow-grey' ?> rounded filters-button dropdown-toggle-simple " data-toggle="dropdown"><i class="red fa fa-fw fa-sm fa-filter"></i></button>

                    <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                        <div class="dropdown-header d-flex justify-content-between">
                            <span class="h6 m-0"><?= $this->language->global->filters->header ?></span>

                            <?php if(count($data->filters->get)): ?>
                                <a href="<?= url('dashboard') ?>" class="text-muted"><?= $this->language->global->filters->reset ?></a>
                            <?php endif ?>
                        </div>

                        <div class="dropdown-divider"></div>

                        <form action="" method="get" role="form">
                            <div class="form-group px-4">
                                <label for="search" class="small"><?= $this->language->global->filters->search ?></label>
                                <input type="text" name="search" id="search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                            </div>

                            <div class="form-group px-4">
                                <label for="search_by" class="small"><?= $this->language->global->filters->search_by ?></label>
                                <select name="search_by" id="search_by" class="form-control form-control-sm">
                                    <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= $this->language->dashboard->filters->search_by_name ?></option>
                                    <option value="domain" <?= $data->filters->search_by == 'domain' ? 'selected="selected"' : null ?>><?= $this->language->dashboard->filters->search_by_domain ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="is_enabled" class="small"><?= $this->language->global->filters->status ?></label>
                                <select name="is_enabled" id="is_enabled" class="form-control form-control-sm">
                                    <option value=""><?= $this->language->global->filters->all ?></option>
                                    <option value="1" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '1' ? 'selected="selected"' : null ?>><?= $this->language->global->active ?></option>
                                    <option value="0" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '0' ? 'selected="selected"' : null ?>><?= $this->language->global->disabled ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="order_by" class="small"><?= $this->language->global->filters->order_by ?></label>
                                <select name="order_by" id="order_by" class="form-control form-control-sm">
                                    <option value="date" <?= $data->filters->order_by == 'date' ? 'selected="selected"' : null ?>><?= $this->language->global->filters->order_by_datetime ?></option>
                                    <option value="name" <?= $data->filters->order_by == 'name' ? 'selected="selected"' : null ?>><?= $this->language->dashboard->filters->order_by_name ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="order_type" class="small"><?= $this->language->global->filters->order_type ?></label>
                                <select name="order_type" id="order_type" class="form-control form-control-sm">
                                    <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= $this->language->global->filters->order_type_asc ?></option>
                                    <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= $this->language->global->filters->order_type_desc ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="results_per_page" class="small"><?= $this->language->global->filters->results_per_page ?></label>
                                <select name="results_per_page" id="results_per_page" class="form-control form-control-sm">
                                    <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                        <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4 mt-4">
                                <button type="submit" class="btn btn-sm btn-primary btn-block"><?= $this->language->global->submit ?></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            
            <div>
                <?php if($this->user->plan_settings->campaigns_limit != -1 && $data->campaigns_total >= $this->user->plan_settings->campaigns_limit): ?>
                    <button type="button" data-confirm="<?= $this->language->campaign->error_message->campaigns_limit ?>"  class="btn bg-pink rounded shadow-red"><i class="fa fa-plus-circle"></i> <?= $this->language->dashboard->campaigns->create ?></button>
                <?php else: ?>
                    <button type="button" data-toggle="modal" data-target="#create_campaign" class="btn bg-red text-white rounded shadow-red"><i class="fa fa-plus-circle"></i> <?= $this->language->dashboard->campaigns->create ?></button>
                <?php endif ?>
            </div>

           
        </div>
    </div>

    <?php if(count($data->campaigns)): ?>
        <div class="table-responsive table-custom-container mt-3">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= $this->language->dashboard->campaigns->name ?></th>
                    <th class="d-none d-md-table-cell"><?= $this->language->dashboard->campaigns->date ?></th>
                    <th><?= $this->language->dashboard->campaigns->is_enabled ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="tbody_campaign">

                <?php foreach($data->campaigns as $row): ?>
                    <?php
                    $row->branding = json_decode($row->branding);

                    $icon = new \Jdenticon\Identicon([
                        'value' => $row->domain,
                        'size' => 50,
                        'style' => [
                            'hues' => [235],
                            'backgroundColor' => '#86444400',
                            'colorLightness' => [0.41, 0.80],
                            'grayscaleLightness' => [0.30, 0.70],
                            'colorSaturation' => 0.85,
                            'grayscaleSaturation' => 0.40,
                        ]
                    ]);
                    $row->icon = $icon->getImageDataUri();

                    ?>
                    <tr>
                        <td class="clickable" data-href="<?= url('campaign/' . $row->campaign_id) ?>">
                            <div class="d-flex">
                                <img src="<?= $row->icon ?>" class="campaign-avatar rounded-circle mr-3" alt="" />

                                <div class="d-flex flex-column" data-this="this">
                                    <?= $row->name ?>

                                    <span class="red">
                                        <u> <?= $row->domain ?></u>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="clickable d-none d-md-table-cell text-muted" data-href="<?= url('campaign/' . $row->campaign_id) ?>"><span><?= \Altum\Date::get($row->date, 2) ?></span></td>
                        <td>
                            <div class="d-flex">
                                <div class="custom-control custom-switch" data-toggle="tooltip" title="<?= $this->language->dashboard->campaigns->is_enabled_tooltip ?>">
                                    <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            id="campaign_is_enabled_<?= $row->campaign_id ?>"
                                            data-row-id="<?= $row->campaign_id ?>"
                                            onchange="ajax_call_helper(event, 'campaigns-ajax', 'is_enabled_toggle')"
                                        <?= $row->is_enabled ? 'checked="checked"' : null ?>
                                    >
                                    <label class="custom-control-label clickable" for="campaign_is_enabled_<?= $row->campaign_id ?>"></label>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="dropdown">
                                <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                                    <i class="fa fa-ellipsis-v"></i>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" data-toggle="modal" data-target="#update_campaign" data-campaign-id="<?= $row->campaign_id ?>" data-name="<?= $row->name ?>" data-domain="<?= $row->domain ?>" data-include-subdomains="<?= (bool) $row->include_subdomains ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= $this->language->global->edit ?></a>

                                        <a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#campaign_pixel_key"
                                            data-pixel-key="<?= $row->pixel_key ?>"
                                            data-campaign-id="<?= $row->campaign_id ?>"
                                            class="dropdown-item"
                                        ><i class="fa fa-fw fa-sm fa-code mr-1"></i> <?= $this->language->campaign->header->pixel_key ?></a>

                                        <?php if($this->user->plan_settings->custom_branding): ?>
                                            <a href="#" data-toggle="modal" data-target="#custom_branding_campaign" data-campaign-id="<?= $row->campaign_id ?>" data-branding-name="<?= $row->branding->name ?? '' ?>" data-branding-url="<?= $row->branding->url ?? '' ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-random mr-1"></i> <?= $this->language->campaign->header->custom_branding ?></a>
                                        <?php endif ?>

                                        <a href="#" data-toggle="modal" data-target="#campaign_delete_modal" data-campaign-id="<?= $row->campaign_id ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= \Altum\Language::get()->global->delete ?></a>
                                    </div>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>

                </tbody>
            </table>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>

    <?php else: ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->global->no_data ?>" />
            <h2 class="h4 text-muted"><?= $this->language->global->no_data ?></h2>
            <p><?= $this->language->dashboard->campaigns->no_data ?></p>
        </div>

    <?php endif ?>

</section>

            
            </div>
        
        </div>
    </div> 
            
            
<?php ob_start() ?>
<script>
    $('[data-delete]').on('click', event => {
        let message = $(event.currentTarget).attr('data-delete');

        if(!confirm(message)) return false;

        /* Continue with the deletion */
        ajax_call_helper(event, 'campaigns-ajax', 'delete', () => {

            /* On success delete the actual row from the DOM */
            $(event.currentTarget).closest('tr').remove();

        });

        event.preventDefault();
    });

    <?php if(isset($_GET['pixel_key_modal'])): ?>

    /* Open the pixel key modal */
    $('[data-campaign-id="<?= (int) $_GET['pixel_key_modal'] ?>"][data-pixel-key]').trigger('click');

    <?php endif ?>

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
