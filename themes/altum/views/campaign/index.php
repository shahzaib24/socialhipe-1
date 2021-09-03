<?php defined('ALTUMCODE') || die() ?>

<input type="hidden" id="base_controller_url" name="base_controller_url" value="<?= url('campaign/' . $data->campaign->campaign_id) ?>" />
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



<header class="header bg-white mb-0">
        
    <div class="container">
        
        
        

<!--
        <nav aria-label="breadcrumb">
            <small>
                <ol class="custom-breadcrumbs">
                    <li>
                        <a href="<?//= url('dashboard') ?>"><?//= $this->language->dashboard->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                    </li>
                    <li class="active" aria-current="page"><?//= $this->language->campaign->breadcrumb ?></li>
                </ol>
            </small>
        </nav>
-->

        <?php

        $icon = new \Jdenticon\Identicon([
            'value' => $data->campaign->domain,
            'size' => 100,
            'style' => [
                'hues' => [235],
                'backgroundColor' => '#86444400',
                'colorLightness' => [0.41, 0.80],
                'grayscaleLightness' => [0.30, 0.70],
                'colorSaturation' => 0.85,
                'grayscaleSaturation' => 0.40,
            ]
        ]);
        $data->campaign->icon = $icon->getImageDataUri();

        ?>

        <div class="d-flex">
<!--            <img src="<?//= $data->campaign->icon ?>" class="campaign-big-avatar rounded-circle mr-3" alt="" />-->

            <div class="d-flex flex-column flex-grow-1">
                <div class="align-items-md-center">
                    
                    <div class="d-flex justify-content-between">
            <div class="col-8 p-0">
                
                    <h1 class="h2 mr-3"><span class=""><?= $data->campaign->name ?></span></h1>

                        </div>
                    
                            
 <div class="col-4 p-0 lol">
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
                    
                    
                    
                    <div class="d-flex">
                        
                <div class="d-flex align-items-center red">
<!--                    <img src="https://external-content.duckduckgo.com/ip3/<?//= $data->campaign->domain ?>.ico" class="img-fluid icon-favicon mr-1" />-->
                    <u> <?= $data->campaign->domain ?></u>
                </div>
                        <div class="custom-control custom-switch mr-1 ml-3" data-toggle="tooltip" title="<?= $this->language->dashboard->campaigns->is_enabled_tooltip ?>">
                            <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    id="campaign_is_enabled_<?= $data->campaign->campaign_id ?>"
                                    data-row-id="<?= $data->campaign->campaign_id ?>"
                                    onchange="ajax_call_helper(event, 'campaigns-ajax', 'is_enabled_toggle')"
                                    <?= $data->campaign->is_enabled ? 'checked="checked"' : null ?>
                            >
                            <label class="custom-control-label clickable" for="campaign_is_enabled_<?= $data->campaign->campaign_id ?>"></label>
                        </div>

                        <div class="dropdown">
                            <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                                <i class="fa fa-ellipsis-v"></i>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" data-toggle="modal" data-target="#update_campaign" data-campaign-id="<?= $data->campaign->campaign_id ?>" data-name="<?= $data->campaign->name ?>" data-domain="<?= $data->campaign->domain ?>" data-include-subdomains="<?= (bool) $data->campaign->include_subdomains ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= $this->language->global->edit ?></a>

                                    <a
                                        href="#"
                                        data-toggle="modal"
                                        data-target="#campaign_pixel_key"
                                        data-pixel-key="<?= $data->campaign->pixel_key ?>"
                                        class="dropdown-item"
                                    ><i class="fa fa-fw fa-sm fa-code mr-1"></i> <?= $this->language->campaign->header->pixel_key ?></a>

                                    <?php if($this->user->plan_settings->custom_branding): ?>
                                    <a href="#" data-toggle="modal" data-target="#custom_branding_campaign" data-campaign-id="<?= $data->campaign->campaign_id ?>" data-branding-name="<?= $data->campaign->branding->name ?? '' ?>" data-branding-url="<?= $data->campaign->branding->url ?? '' ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-random mr-1"></i> <?= $this->language->campaign->header->custom_branding ?></a>
                                    <?php endif ?>

                                    <a href="#" data-toggle="modal" data-target="#campaign_delete_modal" data-campaign-id="<?= $data->campaign->campaign_id ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= \Altum\Language::get()->global->delete ?></a>
                                </div>
                            </a>
                        </div>
                

    </div>
                </div>
            </div>
        </div>

        <ul class="mt-5 nav nav-custom">
            <li class="nav-item">
                <a href="<?= url('campaign/' . $data->campaign->campaign_id) ?>" class="nav-link <?= $data->method == 'settings' ? 'active' : null ?>">
                    <i class="fa fa-fw fa-sm fa-bell mr-1"></i> <?= $this->language->campaign->notifications->link ?>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= url('campaign/' . $data->campaign->campaign_id . '/statistics') ?>" class="nav-link <?= $data->method == 'statistics' ? 'active' : null ?>">
                    <i class="fa fa-fw fa-sm fa-chart-bar mr-1"></i> <?= $this->language->campaign->statistics->link ?>
                </a>
            </li>
        </ul>
    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container">

    <?php display_notifications() ?>

    <?= $this->views['method'] ?>

</section>
    </div>
</div>
</div>
