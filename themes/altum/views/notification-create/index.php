<?php defined('ALTUMCODE') || die() ?>

<input type="hidden" id="base_controller_url" name="base_controller_url" value="<?= url('campaign/' . $data->campaign->campaign_id) ?>" />


<div class="container-fluid bg-light-grey pb-6">
    <div class="row mr-1">
        
    <div class="col-lg-1 bg-light-grey text-center fs-esm">
              
              <div class="my-3">
                  <div class="width-fit-content bg-red rounded-circle m-auto">
              <img src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/campaign_icon.svg" class="p-3">
                  </div>
                   <div class="text-uppercase mt-3 text-5b"><?= $this->language->admin_statistics->growth->campaigns->chart ?></div>
              </div>
              
              
              
               <div class="my-3">
                   <div class="width-fit-content rounded-circle m-auto">
                <img src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/new_campaign_icon.svg" class="p-3">
                   </div>
                   <div class="text-uppercase mt-3 text-5b"><?= $this->language->admin_statistics->growth->campaigns->header ?></div>
                   
              </div>
             
              
             
                  <div class="my-3">
                      <div class="width-fit-content rounded-circle m-auto">
                      <img src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/support_icon.svg" class="p-3">
                      </div>
                   <div class="text-uppercase mt-3 text-5b"><?= $this->language->admin_statistics->growth->campaigns->support ?></div>
              </div>
                            
              
                      <a class="my-3 btn bg-white shadow p-2 fs-esm font-weight-bold"><?= $this->language->account->plan->upgrade ?></a>

               </div>
        
        <div class="col-lg-11 bg-white rounded px-4 pb-5">
            
<header class="header bg-white">
    <div class="container-fluid">
<!--
        <nav aria-label="breadcrumb">
            <small>
                <ol class="custom-breadcrumbs">
                    <li>
                        <a href="<?//= url('dashboard') ?>"><?//= $this->language->dashboard->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                    </li>
                    <li>
                        <a href="<?//= url('campaign/' . $data->campaign->campaign_id) ?>"><?//= $this->language->campaign->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                    </li>
                    <li class="active" aria-current="page"><?//= $this->language->notification_create->breadcrumb ?></li>
                </ol>
            </small>
        </nav>
-->
        <div class="row">
<div class="col-8">
        <h1 class="h2 mr-3 my-0"><?= $this->language->notification_create->header ?></h1>
</div>
        <div class="col-4">
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
<!--            <img src="https://external-content.duckduckgo.com/ip3/<?//= $data->campaign->domain ?>.ico" class="img-fluid icon-favicon mr-1" />-->
            <u><?= $data->campaign->domain ?></u>
        </div>
    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>



  
  

<section class="container-fluid">
    
    <div class="row">
        <div class="col-4">
    <h1 class="h2 mr-3"><?= $this->language->index->tools->preview ?></h1>
    <p><?= $this->language->index->tools->preview_description ?></p>
            </div>
        
        <div class="col-4 symbole">
	 	  <img src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/angle.png" class="img-fluid">
	 </div>
        
        <div class="col-4"></div>
        
    </div>
    <button type="submit" name="submit" class="mt-3 btn bg-red text-white shadow-red p-3 fs-esm"><?= $this->language->global->create_selected ?></button>

    <?php display_notifications() ?>

    <div class="my-5 mb-lg-0 d-flex flex-column flex-md-row justify-content-center align-items-center">
        <div id="notification_preview"></div>
    </div>

<!--
    
    <div class="col-md-4">
	    <div class="services_upper">
		<div class="image_upper">
		<div class="image_outer">
		<img class="center-block image-center"  src="<?//= SITE_URL . ASSETS_URL_PATH ?>/images/i.png">
		 </div>
		 <div class="services_content">
		  <h3>Informational</h3>
          <p>
		  Fully customizable informational<br/> message for your users.

		  </p>
		 </div>
		 </div>
		</div>
	  </div>
-->
    
    
    
    
    <form name="create_notification" method="post" role="form">
        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
        <input type="hidden" name="request_type" value="create" />
        <input type="hidden" name="campaign_id" value="<?= $data->campaign->campaign_id ?>" />

        <div class="mt-5 row d-flex align-items-stretch">
            <?php foreach($data->notifications as $notification_type => $notification_config): ?>

                <?php

                /* Check for permission of usage of the notification */
                if(!$this->user->plan_settings->enabled_notifications->{$notification_type}) {
                    continue;
                }

                ?>

                <?php $notification = \Altum\Notification::get($this->settings, $notification_type) ?>

                <label class="col-12 col-md-6 col-lg-4 mb-3 mb-md-4 custom-radio-box mb-3">

                    <input type="radio" name="type" value="<?= $notification_type ?>" class="d-none" required="required">

                    <div class="services_upper shadow-lg h-200px">
                        <div class="px-5">

                            <div class="image_upper mb-3 text-center">
                                <h2> <span class="image_outer position-relative red"><i class="position-absolute transform-middle center-block image-center <?= $this->language->notification->{strtolower($notification_type)}->icon ?>"></i></span></h2>
                            </div>

                            <h4 class="card-title font-weight-bold text-center mt-4"><?= $this->language->notification->{strtolower($notification_type)}->name ?></h4>

                            <p class="text-muted text-center"><?= $this->language->notification->{strtolower($notification_type)}->description ?></p>

                        </div>
                    </div>

                    <div class="preview" style="display: none">
                        <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                    </div>

                </label>

                <?php if($notification_type == 'ENGAGEMENT_LINKS'): ?>
                    <?php ob_start() ?>
                    <script>
                        $('.altumcode-engagement-links-wrapper .altumcode-engagement-links-hidden').removeClass('altumcode-engagement-links-hidden').addClass('altumcode-engagement-links-shown');
                    </script>
                    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
                <?php endif ?>

            <?php endforeach ?>
        </div>

        <div class="mt-4">
            <button type="submit" name="submit" class="btn bg-red text-white shadow-red p-3 float-right fs-esm"><?= $this->language->global->create_selected ?></button>
        </div>
    </form>
</section>

   </div>
        
        </div>
    </div>            
            
<?php ob_start() ?>
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/pixel.css' ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script>
    /* Preview handler */
    $('input[name="type"]').on('change', event => {

        let preview_html = $(event.currentTarget).closest('label').find('.preview').html();
        let type = $(event.currentTarget).val();

        $('#notification_preview').hide().html(preview_html).fadeIn();

        if(type.includes('_BAR')) {
            $('#notification_preview').removeClass().addClass('notification-create-preview-bar');
        } else {
            $('#notification_preview').removeClass().addClass('notification-create-preview-normal');
        }
    });

    /* Select a default option */
    $('input[name="type"]:first').attr('checked', true).trigger('change');

    /* Form submission */
    $('form[name="create_notification"]').on('submit', event => {

        $.ajax({
            type: 'POST',
            url: 'notifications-ajax',
            data: $(event.currentTarget).serialize(),
            success: (data) => {
                if (data.status == 'error') {
                    notification_container.html('');

                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    /* Fade out refresh */
                    redirect(`notification/${data.details.notification_id}`);

                }
            },
            dataType: 'json'
        });

        event.preventDefault();
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
