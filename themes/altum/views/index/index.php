<?php defined('ALTUMCODE') || die() ?>
<div class="bg-white">

<div class="bg-pink position-relative" style="height:777px">  
    <div class="container">
        <div class="index-cover">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/slider-image.png'?>" height="793px">
        </div>
        
    <div id="notification_preview" class="notification-info-home" style="">
                        <div class="altumcode-wrapper altumcode-wrapper-rounded altumcode-wrapper-shadow altumcode-informational-wrapper" style="background-color: #fff;border-width: 0px;border-color: #000;;">
    <div class="altumcode-informational-content">
                 <div class="position-absolute small-notification-icon">
             <img src="http://localhost/socialhipe/themes/altum/assets//images/small_notification_icon.svg" class="" loading="lazy">
        </div>
                         <img src="http://localhost/socialhipe/themes/altum/assets///images/flash_notification_icon.svg" class="altumcode-informational-image" loading="lazy">
        
        <div>
            <div class="altumcode-informational-header">
                <p class="altumcode-informational-title" style="color: #000">Flash sale!</p>

                <span class="altumcode-close"></span>
            </div>
            <p class="altumcode-informational-description" style="color: #000">Limited sale until tonight, right now!</p>


                                                <a href="http://localhost/socialhipe/" class="altumcode-site">by SocialHipe</a>
                                    </div>
    </div>
</div>
                    </div>
    <div id="notification_preview" class="notification-people-home" style="">
                        <div class="altumcode-wrapper altumcode-wrapper-rounded altumcode-wrapper-shadow altumcode-conversions-counter-wrapper" style="background-color: #fff;border-width: 0px;border-color: #000;;">
    <div class="altumcode-conversions-counter-content pr-2"><div class="row"><div class="col-10">
        <div class="altumcode-conversions-counter-header">
                            <img src="http://localhost/socialhipe/themes/altum/assets///images/conversion_counter_notification_icon.svg" class="altumcode-latest-conversion-image" loading="lazy"><div>
                <p class="altumcode-conversions-counter-title pb-2" style="color: #000">5 People bought</p>
                <p class="altumcode-conversions-counter-time">In the last 2 hours</p></div>
            <div class="altumcode-conversions-counter-close">
                <span class="altumcode-close"></span>
            </div></div></div>
        <div class="col-2">
             <img src="http://localhost/socialhipe/themes/altum/assets//images/small_notification_icon.svg" class="small-notification-icon" loading="lazy"></div></div>
        <a href="http://localhost/socialhipe/" class="altumcode-site">by SocialHipe</a>
</div></div></div>
    
        
        
<div class="index-container">
    <div class="px-5">
        <?php display_notifications() ?>

        <div class="row mt-3">
                <div class="text-left">
                    <h1 class="index-header mb-4" data-aos="fade-down" data-lol="lol">
                        <?php echo $this->language->index->header ?>
                    </h1>
                    <p class="index-subheader mb-5" data-aos="fade-down" data-aos-delay="200">
                        <?= sprintf($this->language->index->subheader, $data->total_notifications) ?>
                    </p>

                    <div data-aos="fade-down" data-aos-delay="300">
                        <a href="<?= url('register') ?>" class="btn bg-red text-white index-button" ><?= $this->language->index->get_started ?></a>
                    </div>
                </div>
            </div>


    </div>
</div>
        </div>
</div>


    
    
    
 <div class="container-notificaiton-box-mid">
    <div class="container">
	<div class="row coupon-row">
	<div class="col-lg-6 col-md-6 col-sm-12 position-relative">
        <div class="back-design-blue position-absolute">
        
        </div>
         <div class="back-design-pink position-absolute">
        
        </div>
	 <div id="notification_preview" class="notification-preview-coupon">
        




<div class="altumcode-wrapper altumcode-wrapper-rounded altumcode-wrapper-shadow altumcode-coupon-wrapper" style="background-color: #fff;border-width: 0px;border-color: #000; z-index:1" data-t="t">
    <div class="altumcode-coupon-content-custom">
                <img src="http://localhost/socialhipe/themes/altum/assets///images/coupon_notification_icon.svg" class="altumcode-coupon-image-custom" loading="lazy">
        
        <div>
            <div class="altumcode-coupon-header">
                <p class="altumcode-coupon-title" style="color: #000">35% OFF!</p>

                <span class="altumcode-close"></span>
            </div>
            <p class="altumcode-coupon-description fs-14" style="color: #000">Limited summer sale coupon code!</p>

            <div class="altumcode-coupon-coupon-code">COUPON20</div>

            <a href="" class="altumcode-coupon-button bg-red" style="color: #fff">Get Coupon</a>

<!--
            <div>
                <a href="#" class="altumcode-coupon-footer"><?//= $notification->settings->footer_text ?></a>
            </div>
-->

                                                <a href="http://localhost/socialhipe/" class="altumcode-site">by SocialHipe</a>
                                    </div>
    </div>
</div>
    </div>
        
       
        
	</div>
        
<!--        <div data-coupon="coupon" ><?//= $this->views['coupon'] ?></div>-->
        
        
        
	<div class="col-lg-6 col-md-6 col-sm-12 mt-n9">
	 <div class="amazed-visitors">
	 <h3>
	<?= $this->language->notification->coupon_feature->title ?>
	 </h3>
	 </div>
	 <div class="row">
	 <div class="col-2">
	  <img src="<?= SITE_URL . ASSETS_URL_PATH .'/images/quick_setup.svg'?>" class="img-fluid">
	 </div>
	 <div class="col-10">
	 <h4><?= $this->language->notification->coupon_feature->setup_title ?></h4>
	 <p><?= $this->language->notification->coupon_feature->setup_short_description ?></p>
	 </div>
	 </div>
        
     <div class="row">
	 <div class="col-2">
	  <img src="<?= SITE_URL . ASSETS_URL_PATH .'/images/powering_conversion.svg'?>" class="img-fluid">
	 </div>
	 <div class="col-10">
	 <h4><?= $this->language->notification->coupon_feature->conversion_title ?></h4>
	 <p><?= $this->language->notification->coupon_feature->conversion_short_description ?></p>
	 </div>
	 </div>
        
	 	 <div class="row">
	 <div class="col-2">
	  <img src="<?= SITE_URL . ASSETS_URL_PATH .'images/honest_pricing.svg'?>" class="img-fluid">
	 </div>
	 <div class="col-10">
	 <h4><?= $this->language->notification->coupon_feature->pricing_title ?></h4>
	 <p><?= $this->language->notification->coupon_feature->pricing_short_description ?></p>
	 </div>
	 </div>
            
        
	</div>
	</div>
	</div>
   </div>


 <div class="container pt-8">
   <div class="row">
   <div class="toolkit">
    <h2><?= $this->language->index->tools->header ?></h2>
   </div>
   </div>
   <div class="row" id='notification-row'>
    <div class="col-lg-4 col-md-4 col-sm-6">
	  <div class="notification-preview">
	    <h3><?= $this->language->index->tools->preview ?></h3>
		<p><?= $this->language->index->tools->preview_description ?></p>
	  </div>
	</div>
	 <div class="col-lg-4 col-md-4 col-sm-6">
	 <div class='symbole'>
	 	  <img src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/angle.png" class="img-fluid">
	 </div>
	 </div>
	  <div class="col-lg-4 col-md-4 col-sm-6">
	     <div class='flash-sale'>
		 <div class="altumcode-wrapper altumcode-wrapper-rounded altumcode-wrapper-shadow altumcode-informational-wrapper" style="background-color: #fff;border-width: 0px;border-color: #000;;">
    <div class="altumcode-informational-content">
                 <div class="position-absolute small-notification-icon">
             <img src="http://localhost/socialhipe/themes/altum/assets//images/small_notification_icon.svg" class="" loading="lazy">
        </div>
                         <img src="http://localhost/socialhipe/themes/altum/assets///images/flash_notification_icon.svg" class="altumcode-informational-image" loading="lazy">
        
        <div>
            <div class="altumcode-informational-header">
                <p class="altumcode-informational-title" style="color: #000">Flash sale!</p>

                <span class="altumcode-close"></span>
            </div>
            <p class="altumcode-informational-description" style="color: #000">Limited sale until tonight, right now!</p>


                                                <a href="http://localhost/socialhipe/" class="altumcode-site">by SocialHipe</a>
                                    </div>
    </div>
</div>
		 </div>
	  </div>
   </div>
   </div>
    
    
    
    
    
    
   <div class="container">
     <div class="row">
         
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

                    <div class="services_upper h-200px">
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

 
	 
	  	
	  	
	 </div>
   </div>




<div class="container mt-10">
    <div class="text-center">
        <h2 class="text-gray-700"><?= $this->language->index->setup->header ?></h2>
        <p class="text-muted mt-3"><?= $this->language->index->setup->description ?></p>
    </div>

    <div class="row mt-5 d-flex align-items-center">
        <div class="mb-5 mb-md-0 mx-1 text-center rounded pinkback box-wh position-relative">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/shopify_logo.svg' ?>" class="al-middle" width="140" height="46"  alt="<?= $this->language->index->setup->shopify ?>">
        </div>
        <div class="mb-5 mb-md-0 mx-1 text-center rounded blueback box-wh position-relative">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/worpress_logo.svg' ?>" class="al-middle" width="160" height="46" alt="<?= $this->language->index->setup->wordpress ?>">
        </div>
        <div class="mb-5 mb-md-0 mx-1 text-center rounded zapier box-wh position-relative">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/zapier_logo.svg' ?>" class="al-middle" width="80" height="49" alt="<?= $this->language->index->setup->zapier ?>">
        </div>
        <div class="mb-5 mb-md-0 mx-1 text-center rounded squarespace box-wh position-relative">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/squarespace_logo.svg' ?>" class="al-middle" width="180" height="31" alt="<?= $this->language->index->setup->squarespace ?>">
        </div>
    </div>

</div>

<!--
<div class="py-5 bg-gray-100 mt-10">
    <div class="container">
        <div class="text-center">
            <h2><?//= sprintf($this->language->index->tools->header, nr($data->total_track_notifications)) ?></h2>

            <p class="text-muted mt-5"><?//= $this->language->index->tools->subheader ?></p>
        </div>
    </div>
</div>
-->

<div class="container mt-8">

<!--
    <div class="mb-3 d-flex justify-content-between align-items-center flex-column flex-md-row">
        <div>
            <h3><span class="underline abc"><?//= $this->language->index->tools->preview ?></span></h3>
            <p class="text-muted"><?//= $this->language->index->tools->preview_description ?></p>
        </div>

        <div id="notification_preview" class="container-disabled-simple"></div>
    </div>
-->

<!--
    <div class="mt-8 row d-flex align-items-center">

        <?php //foreach($data->notifications as $notification_type => $notification_config): ?>

            <?php //$notification = \Altum\Notification::get($this->settings, $notification_type) ?>

            <label class="col-12 col-md-6 col-lg-4 mb-3 mb-md-4 custom-radio-box mb-3">

                <input type="radio" name="type" value="<?//= $notification_type ?>" class="custom-control-input" required="required">

                <div class="card shadow-lg zoomer h-100">
                    <div class="card-body">

                        <div class="mb-3 text-center">
                            <span class="custom-radio-box-main-icon"><i class="<?//= $this->language->notification->{strtolower($notification_type)}->icon ?>"></i></span>
                        </div>

                        <div class="card-title font-weight-bold text-center"><?//= $this->language->notification->{strtolower($notification_type)}->name ?></div>

                        <p class="text-muted text-center"><?//= $this->language->notification->{strtolower($notification_type)}->description ?></p>

                    </div>
                </div>

                <div class="preview" style="display: none">
                    <?//= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>

            </label>

            <?php //if($notification_type == 'ENGAGEMENT_LINKS'): ?>
            <?php //ob_start() ?>
                <script>
                    $('.altumcode-engagement-links-wrapper .altumcode-engagement-links-hidden').removeClass('altumcode-engagement-links-hidden').addClass('altumcode-engagement-links-shown');
                </script>
                <?php //\Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
            <?php //endif ?>

        <?php //endforeach ?>

    </div>
</div>
-->

<div class="container mt-10">
    <div class="text-center mb-8">
        <h2><?= $this->language->index->pricing->header ?></h2>

        <p class="text-muted mt-4"><?= $this->language->index->pricing->subheader ?></p>
        <div class="btn-group btn-group-toggle mt-5" data-toggle="buttons">
            
          <label class="btn plan-btn nav-link px-4 border-round-1 active">
            <input type="radio" name="options" id="option1" autocomplete="off" checked> Monthly
          </label>
          <label class="btn plan-btn nav-link px-4 border-round-2">
            <input type="radio" name="options" id="option2" autocomplete="off"> Annual
          </label>
</div>
        
    </div>
<div class="d-flex">
    <?= $this->views['plans'] ?>
     <?= $this->views['plans'] ?>
    </div>
    
    
    
</div>
    


<?php //if($this->settings->register_is_enabled): ?>
<!--
<div class="index-register-container mt-9">
    <div class="container">
        <div class="d-flex flex-row justify-content-around">
            <div>
                <h2><?//= $this->language->index->cta->header ?></h2>
                <p><?//= $this->language->index->cta->subheader ?></p>
            </div>

            <div>
                <a href="<?//= url('register') ?>" class="btn btn-outline-light index-button"><?//= $this->language->index->cta->sign_up ?></a>
            </div>
        </div>
    </div>
</div>
-->
<?php  //endif ?>

<?php ob_start() ?>
<link rel="stylesheet" href="<?= SITE_URL . ASSETS_URL_PATH . 'css/aos.min.css' ?>">
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/pixel.css' ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/aos.min.js' ?>"></script>

<script>
    AOS.init({
        delay: 100,
        duration: 600
    });

    /* Preview handler */
    $('input[name="type"]').on('change', (event, first_trigger = false) => {

        let preview_html = $(event.currentTarget).closest('label').find('.preview').html();

        $('#notification_preview').hide().html(preview_html).fadeIn();

        /* Make sure its not the first check */
        if(!first_trigger) {
            document.querySelector('#notification_preview').scrollIntoView();
        }

    });

    /* Select a default option */
    $('input[name="type"]:first').attr('checked', true).trigger('change', true);
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

