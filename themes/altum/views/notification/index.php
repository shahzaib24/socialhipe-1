<?php defined('ALTUMCODE') || die() ?>

<input type="hidden" id="base_controller_url" name="base_controller_url" value="<?= url('notification/' . $data->notification->notification_id) ?>" />

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


<header class="header pb-0 bg-white mb-0">
    
            
        <div class="d-flex justify-content-between">
            <div class="col-8 p-0">
             <h1 class="h2 mr-3"><?= $data->notification->name ?></h1>
                </div>
<!--
             <div class="">
            <span class="badge badge-success h-fc"><?//= sprintf($this->language->account->plan->header, $this->user->plan->name) ?></span>
                 <span class="h-fc red"><u><?//= sprintf($this->language->account->plan->renew, $this->user->plan->name) ?></u></span>
                 
            </div>
-->
        
        
        
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
    
    
    <div class="">



        <div class="d-flex align-items-center">
           

       
            <div class="d-flex align-items-center mr-3">
               <u class="red">
                <?= $data->notification->domain ?>
                </u>
            </div>

            <span class="">
                <i class="<?= $this->language->notification->{strtolower($data->notification->type)}->icon ?> fa-sm mr-1"></i> <?= $this->language->notification->{strtolower($data->notification->type)}->name ?>
            </span>
             <div class="custom-control custom-switch ml-2" data-toggle="tooltip" title="<?= $this->language->campaign->notifications->is_enabled_tooltip ?>">
                <input
                        type="checkbox"
                        class="custom-control-input"
                        id="campaign_is_enabled_<?= $data->notification->notification_id ?>"
                        data-row-id="<?= $data->notification->notification_id ?>"
                        onchange="ajax_call_helper(event, 'notifications-ajax', 'is_enabled_toggle')"
                    <?= $data->notification->is_enabled ? 'checked="checked"' : null ?>
                >
                <label class="custom-control-label clickable" for="campaign_is_enabled_<?= $data->notification->notification_id ?>"></label>
            </div>
        </div>

        <?= $this->views['method_menu'] ?>
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

<?php ob_start() ?>
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/pickr.min.css' ?>" rel="stylesheet" media="screen">
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/daterangepicker.min.css' ?>" rel="stylesheet" media="screen,print">
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/pixel.css' ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script>
    /* Delete handler for the notification */
    $('[data-delete]').on('click', event => {
        let message = $(event.currentTarget).attr('data-delete');

        if(!confirm(message)) return false;

        /* Continue with the deletion */
        ajax_call_helper(event, 'notifications-ajax', 'delete', (data) => {
            redirect(`campaign/${data.details.campaign_id}`);
        });

    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
