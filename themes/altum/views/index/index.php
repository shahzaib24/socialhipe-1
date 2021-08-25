<?php defined('ALTUMCODE') || die() ?>
<div class="bg-pink">
<div class="index-background-container d-none d-lg-block">
    <div class="index-background-image"></div>
</div>

<div class="index-cover-container d-none d-lg-block">
    <div class="container container-disabled-simple">
        <div class="index-cover">
            
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/slider-image.svg'?>" class="zoomer">
            
        </div>
    </div>
</div>

<div class="index-container">
    <div class="container">
        <?php display_notifications() ?>

        <div class="row  mt-8">
            <div class="col">
                <div class="text-left">
                    <h1 class="index-header mb-4" data-aos="fade-down" data-lol="lol">
                        <?php echo $this->language->index->header ?>
                    </h1>
                    <p class="index-subheader mb-5" data-aos="fade-down" data-aos-delay="200">
                        <?= sprintf($this->language->index->subheader, $data->total_notifications) ?>
                    </p>

                    <div data-aos="fade-down" data-aos-delay="300">
                        <a href="<?= url('register') ?>" class="btn bg-red text-white index-button" ><?= $this->language->index->sign_up ?></a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
</div>

 <div class="container-fluid">
    <div class="container">
	<div class="row coupon-row">
	<div class="col-lg-6 col-md-6 col-sm-12">
	 <div class="coupon_outer">
	 <div class="coupon-left">
	 	   <img src="<?= SITE_URL . ASSETS_URL_PATH . '/images/off.png'?>" class="img-fluid">
	 </div>
	 <div class="coupon-right">
	 <h4><?= $this->language->notification->coupon->title_default ?></h4>
	 <p><?= $this->language->notification->coupon->description_default ?></p>
	 <h2><?= $this->language->notification->coupon->coupon_code_default ?></h2>
	 <div class="getCoupon">
	 <a href=""><?= $this->language->notification->coupon->button_text_default ?></a>
	 </div>
	 </div>
	 </div>
	</div>
        
<!--        <div data-coupon="coupon" ><?//= $this->views['coupon'] ?></div>-->
        
        
        
	<div class="col-lg-6 col-md-6 col-sm-12">
	 <div class="amazed-visitors">
	 <h3>
	<?= $this->language->notification->coupon_feature->title ?>
	 </h3>
	 </div>
        
	 <div class="amazed-outer">
	 <div class="amazed-left">
	  <img src="<?= SITE_URL . ASSETS_URL_PATH .'/images/setup.png'?>" class="img-fluid">
	 </div>
	 <div class="amazed-right">
	 <h4><?= $this->language->notification->coupon_feature->setup_title ?></h4>
	 <p><?= $this->language->notification->coupon_feature->setup_short_description ?></p>
	 </div>
	 </div>
        
	 	 <div class="amazed-outer">
	 <div class="amazed-left">
	  <img src="<?= SITE_URL . ASSETS_URL_PATH .'/images/power.png'?>" class="img-fluid">
	 </div>
	 <div class="amazed-right">
	 <h4><?= $this->language->notification->coupon_feature->conversion_title ?></h4>
	 <p><?= $this->language->notification->coupon_feature->conversion_short_description ?></p>
	 </div>
	 </div>
        
	 	 <div class="amazed-outer">
	 <div class="amazed-left">
	  <img src="<?= SITE_URL . ASSETS_URL_PATH .'images/honest.png'?>" class="img-fluid">
	 </div>
	 <div class="amazed-right">
	 <h4><?= $this->language->notification->coupon_feature->pricing_title ?></h4>
	 <p><?= $this->language->notification->coupon_feature->pricing_short_description ?></p>
	 </div>
	 </div>
	</div>
	</div>
	</div>
   </div>


 <div class="container">
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
		 <img src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/sale.png" class="img-fluid">
		 </div>
	  </div>
   </div>
   </div>
   <div class="container">
     <div class="row">
	  <div class="col-md-4">
	    <div class="services_upper">
		<div class="image_upper">
		<div class="image_outer">
		<img class="center-block image-center"  src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/i.png">
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
	  	  <div class="col-md-4">
	    <div class="services_upper">
		<div class="image_upper">
		<div class="image_outer">
		<img class="center-block image-center"  src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/coupns-1.png">
		 </div>
		 <div class="services_content">
		  <h3>Coupon</h3>
          <p>
		  The best way to let your users know that<br/> you are running a sale.

		  </p>
		 </div>
		 </div>
		</div>
	  </div>
	  	  <div class="col-md-4">
	    <div class="services_upper">
		<div class="image_upper">
		<div class="image_outer">
		<img class="center-block image-center"  src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/counter.png">
		 </div>
		 <div class="services_content">
		  <h3>Live Counter</h3>
          <p>
Show your visitors how many people are <br/>on your site to create more trust.
		  </p>
		 </div>
		 </div>
		</div>
	  </div>
	  	  <div class="col-md-4">
	    <div class="services_upper">
		<div class="image_upper">
		<div class="image_outer">
		<img src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/i.png" class="img-fluid">
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
	  	  <div class="col-md-4">
	    <div class="services_upper">
		<div class="image_upper">
		<div class="image_outer">
		<img src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/i.png" class="img-fluid">
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
	  	  <div class="col-md-4">
	    <div class="services_upper">
		<div class="image_upper">
		<div class="image_outer">
		<img src="<?= SITE_URL . ASSETS_URL_PATH ?>/images/coupns-1.png" class="img-fluid">
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

        <p class="text-muted mt-5"><?= $this->language->index->pricing->subheader ?></p>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            
          <label class="btn btn-secondary active px-5">
            <input type="radio" name="options" id="option1" autocomplete="off" checked> Monthly
          </label>
          <label class="btn btn-secondary px-5">
            <input type="radio" name="options" id="option2" autocomplete="off"> Annual
          </label>
</div>
        
    </div>
<div class="d-flex">
    <?= $this->views['plans'] ?>
     <?= $this->views['plans'] ?>
    </div>
    
    
    
</div>
    
    
    <div class="container mt-10">
    <div class="row p-5">
        
        <div class="shadow text-center rounded p-5 m-2">
        <h4 class="text-black px-5">50% Off Today</h4>
        <p>Lorem ipsum dolor sit amet, consectetur<br/> adipiscing elit, sed do</p>
            <a href="" class="btn bg-red shadow text-white py-2 px-5">shop now</a>
        </div>
        
          <div class="shadow text-center rounded p-5 m-2">
        <h4 class="text-black px-5">join us today!</h4>
        <p>Lorem ipsum dolor sit amet, consectetur<br/> adipiscing elit, sed do</p>
            <a href="" class="btn bg-red shadow text-white py-2 px-5">subscribe</a>
        </div>
        
          <div class="h-fc shadow text-start rounded px-6 py-4 m-2">
              <div class="row">
              <div class="col-2">
              <img src="" height="40px" width="40px">
              </div>
              <div class="col-10">
        <h4 class="text-black text-start">10 visitors online</h4>
        <p>Lorem ipsum dolor sit amet, consectetu</p>
           
        </div>
        </div>
        </div>
        
        
        <div class="shadow text-center rounded p-5 m-2">
        <h4 class="text-black px-5">How was our support?</h4>
        <p>Lorem ipsum dolor sit amet, consectetur<br/> adipiscing elit, sed do</p>
            <a href="" class="btn bg-red shadow text-white p4 mx-1">1</a>
            <a href="" class="btn bg-red shadow text-white p4 mx-1">2</a>
            <a href="" class="btn bg-red shadow text-white p4 mx-1">3</a>
            <a href="" class="btn bg-red shadow text-white p4 mx-1">4</a>
            <a href="" class="btn bg-red shadow text-white p4 mx-1">5</a>
            
        </div>
        
        
          <div class="shadow text-center rounded p-5 m-2">
        <h4 class="text-black px-5">How was our support?</h4>
        <p>Lorem ipsum dolor sit amet, consectetur<br/> adipiscing elit, sed do</p>
            <img href="" class="btn bg-red shadow text-white p4 mx-1">
            <img href="" class="btn bg-red shadow text-white p4 mx-1">
            <img href="" class="btn bg-red shadow text-white p4 mx-1">
            <img href="" class="btn bg-red shadow text-white p4 mx-1">
            <img href="" class="btn bg-red shadow text-white p4 mx-1">
            
        </div>
        
        
          <div class="shadow text-center rounded p-5 m-2">
        <h4 class="text-black px-5">Share now</h4>
        <p>Lorem ipsum dolor sit amet, consectetur<br/> adipiscing elit, sed do</p>
            <img href="" class="btn bg-red shadow text-white p4 mx-1">
            <img href="" class="btn bg-red shadow text-white p4 mx-1">
            <img href="" class="btn bg-red shadow text-white p4 mx-1">
            <img href="" class="btn bg-red shadow text-white p4 mx-1">
            <img href="" class="btn bg-red shadow text-white p4 mx-1">
            
        </div>
        
        
        <div class="bg-light-blue p-6 m-2">
         <div class="shadow bg-white text-center rounded px-9 py-5">
        <h2 class="text-black px-5">Hurry</h2>
             <div class="row text-center py-5">
                 <div class="col-4"><div class="bg-pink rounded "><h1>7</h1></div><h5>Days</h5></div>
                  <div class="col-4 "><div class="bg-pink rounded "><h1>15</h1></div><h5>Hour</h5></div>
                 <div class="col-4"><div class="bg-pink rounded"><h1>33</h1></div><h5>Min</h5></div>
                 
             </div>
             
        <p>Lorem ipsum dolor sit amet, consectetur<br/> adipiscing elit, sed do</p>
            <a href="" class="btn bg-red shadow text-white py-2 px-5">Add to cart</a>
        </div>
        </div>
        
        
          <div class="h-fc shadow text-start rounded px-4 py-4 m-2">
              <div class="bg-light-blue rounded-circle h-fc float-right">
                  <img src="" class="p-1">
                  </div>
              <div class="d-flex">
              <div class="col-2">
              <img src="" height="40px" width="40px">
              </div>
              <div class="col-10">
                  <h4 class="text-black text-start">Flash sale!<span class="dot"></span></h4>
        <p>Lorem ipsum dolor sit amet, consectetu</p>
        </div>      
        </div>
        </div>
        
        
                  <div class="h-fc shadow text-start rounded px-4 py-4 m-2">
              <div class="bg-light-blue rounded-circle h-fc float-right">
                  <img src="" class="p-1">
                  </div>
              <div class="d-flex">
              <div class="col-2">
              <img src="" height="40px" width="40px">
              </div>
              <div class="col-10">
                  <h4 class="text-black text-start">Flash sale!<span class="dot"></span></h4>
        <p>Lorem ipsum dolor sit amet, consectetu</p>
                    <div class="border-0">
                      <input type="email" name="email" class="py-1">
                          <input type="submit" name="newsletter_button" class="ml-n2 btn rounded-start bg-red text-white">
                      </div>
        </div>      
        </div>
                      
                    
        </div>
        
        <div class="shadow text-center rounded p-5 m-2">
        <h4 class="text-black px-5">Demo of the product</h4>
        <div class="ratio ratio-1x1 p-6">
            <div>1x1</div>
            </div>
            <a href="" class="btn bg-red shadow text-white py-2 px-5">Sign Up</a>
        </div>
        
        </div>
    </div>
    

<?php if($this->settings->register_is_enabled): ?>
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
<?php  endif ?>

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

