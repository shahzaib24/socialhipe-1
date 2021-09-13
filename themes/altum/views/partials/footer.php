<?php defined('ALTUMCODE') || die() ?>

<footer class="d-print-none footer <?= \Altum\Routing\Router::$controller_key == 'index' ? 'm-0' : null ?>">
    <div class="container">
        <div class="row">

            
            <div class="container border-bottom border-dark pb-4">
                <div class="row align-items-center">
    <div class="text-left col-md-6">
        <h2 class="text-white">Start growing</h2>
    </div>
    <div data-aos="fade-down" data-aos-delay="300" class="col-md-6">
                        <a href="<?= url('register') ?>" class="btn bg-red index-button float-right text-white shadow-red fs-12" ><?= $this->language->index->sign_up ?></a>
                    </div>
                </div>
                
</div>
            
            <div class="container py-5">
            <div class="row hover-red">
                
                <div class="col-4">
                    <div class="d-flex align-items-center mb-4">
                    <div class="footer-logo p-3 d-flex">
                 <img src='<?= SITE_URL . ASSETS_URL_PATH ?>/images/footer_logo.svg'> 
                    </div>
                       <h4 class="text-white ml-3 mb-0"><?= $this->language->footer_content->title ?></h4>
                        </div>
               <?= $this->language->footer_content->content ?>
                        
                </div>
                 <div class="col-2">
                     <div class="text-white fs-6 pb-3"><?= $this->language->footer_content->menu_products ?></div>
                <ul class="list-unstyled">
                  <?= $this->language->footer_content->menu_products_li ?>
                     </ul>
                </div>
                <div class="col-2">
                    <div class="text-white fs-6 pb-3"><?= $this->language->footer_content->menu_socialhipe ?></div>
                <ul class="list-unstyled">
                  <?= $this->language->footer_content->menu_socialhipe_li ?>
                    
                    </ul>
                </div>
                <div class="col-2">
                    <div class="text-white fs-6 pb-3"><?= $this->language->footer_content->menu_support ?></div>
               <ul class="list-unstyled">
                    <?= $this->language->footer_content->menu_socialhipe_li ?>
                   </ul>
                    
                </div>
                <div class="col-2">
                    <div class="text-white fs-6 pb-3"><?= $this->language->footer_content->menu_contact ?></div>
                <p><?= $this->language->footer_content->menu_contact_content ?></p>
                    <ul class="list-unstyled">
                        <li><img src='<?= SITE_URL . ASSETS_URL_PATH ?>/images/footer_mail.svg' class="mr-2" ><?= $this->language->footer_content->menu_contact_mail ?></li>
                        <li><img src='<?= SITE_URL . ASSETS_URL_PATH ?>/images/footer_phone.svg' class="mr-2" ><?= $this->language->footer_content->menu_contact_number ?></li>
                    </ul>
                    <div class="d-flex">
                        <div class="footer-social-icon mx-1">
                        <img src='<?= SITE_URL . ASSETS_URL_PATH ?>/images/instagram.svg' class="p-2" >
                            </div>
                        <div class="footer-social-icon mx-1">
                        <img src='<?= SITE_URL . ASSETS_URL_PATH ?>/images/facebook.svg' class="p-2" >
                            </div>
                        <div class="footer-social-icon mx-1">
                        <img src='<?= SITE_URL . ASSETS_URL_PATH ?>/images/twitter.svg' class="p-2" >
                             </div>
                        <div class="footer-social-icon mx-1">
                        <img src='<?= SITE_URL . ASSETS_URL_PATH ?>/images/youtube.svg' class="p-2" >
                            </div>
                    </div>
                </div>
                </div>
            </div>
            
            
            
            <div class="container border-top pt-3 border-dark">
                <div class="row">
                <ul class="list-unstyled list-inline m-auto">
                <?= $this->language->footer_content->copy_right_li ?>
                </ul>
                </div>
                  <div class="mx-1 float-right">
                        <img src='<?= SITE_URL . ASSETS_URL_PATH ?>/images/go_up.svg' class="go-up">
                  </div>
                 
            </div>
            
            
            
<!--
            <div class="col-12 col-sm-4 mb-4 mb-sm-0">
                <div class="mb-2">
                    <a class="h5 p-0" href="<?//= url() ?>">
                        <? //= $this->settings->title ?>
                    </a>
                </div>

                <div><?//= sprintf($this->language->global->footer->copyright, date('Y'), $this->settings->title) ?></div>
            </div>
-->

<!--
            <div class="col-12 col-sm-4 mb-4 mb-sm-0">

                    <div class="mb-2">
                        <?php //foreach(require APP_PATH . 'includes/admin_socials.php' as $key => $value): ?>

                            <?php //if(isset($this->settings->socials->{$key}) && !empty($this->settings->socials->{$key})): ?>
                            <span class="mr-2">
                                <a target="_blank" href="<?//= sprintf($value['format'], $this->settings->socials->{$key}) ?>" title="<?//= $value['name'] ?>" class="no-underline">
                                    <i class="<?//= $value['icon'] ?> fa-fw fa-lg"></i>
                                </a>
                            </span>
                            <?php //endif ?>

                        <?php //endforeach ?>
                    </div>

                    <?php //if(count(\Altum\Language::$languages) > 1): ?>
                        <div class="dropdown mb-2">
                            <a class="dropdown-toggle clickable" id="language_switch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-fw fa-language text-muted"></i> <?//= $this->language->global->language ?></a>

                            <div class="dropdown-menu" aria-labelledby="language_switch">
                                <h6 class="dropdown-header"><?//= $this->language->global->choose_language ?></h6>
                                <?php //foreach(\Altum\Language::$languages as $language_name): ?>
                                    <a class="dropdown-item" href="<?//= url((\Altum\Routing\Router::$controller_key == 'index' ? 'index' : $_GET['altum']) . '?language=' . $language_name) ?>">
                                        <?php //if($language_name == \Altum\Language::$language): ?>
                                            <i class="fa fa-fw fa-sm fa-check mr-1 text-success"></i>
                                        <?php //else: ?>
                                            <i class="fa fa-fw fa-sm fa-circle-notch mr-1 text-muted"></i>
                                        <?php //endif ?>

                                        <?//= $language_name ?>
                                    </a>
                                <?php //endforeach ?>
                            </div>
                        </div>
                    <?php //endif ?>

                    <?php //if(count(\Altum\ThemeStyle::$themes) > 1): ?>
                        <div class="mb-2">
                            <a href="#" data-choose-theme-style="dark" class="<?//= \Altum\ThemeStyle::get() == 'dark' ? 'd-none' : null ?>">
                                <i class="fa fa-fw fa-sm fa-moon text-muted mr-1"></i> <?//= sprintf($this->language->global->theme_style, $this->language->global->theme_style_dark) ?>
                            </a>
                            <a href="#" data-choose-theme-style="light" class="<?//= \Altum\ThemeStyle::get() == 'light' ? 'd-none' : null ?>">
                                <i class="fa fa-fw fa-sm fa-sun text-muted mr-1"></i> <?//= sprintf($this->language->global->theme_style, $this->language->global->theme_style_light) ?>
                            </a>
                        </div>

                    <?php //ob_start() ?>
                        <script>
                            document.querySelectorAll('[data-choose-theme-style]').forEach(theme => {

                                theme.addEventListener('click', event => {

                                    let chosen_theme_style = event.currentTarget.getAttribute('data-choose-theme-style');

                                    /* Set a cookie with the new theme style */
                                    set_cookie('theme_style', chosen_theme_style, 30, <?//= json_encode(COOKIE_PATH) ?>);

                                    /* Change the css and button on the page */
                                    let css = document.querySelector(`#css_theme_style`);

                                    document.querySelector(`[data-theme-style]`).setAttribute('data-theme-style', chosen_theme_style);

                                    switch(chosen_theme_style) {
                                        case 'dark':
                                            css.setAttribute('href', <?//= json_encode(SITE_URL . ASSETS_URL_PATH . 'css/' . \Altum\ThemeStyle::$themes['dark']['file'] . '?v=' . PRODUCT_CODE) ?>);
                                            document.querySelector(`[data-choose-theme-style="dark"]`).classList.add('d-none');
                                            document.querySelector(`[data-choose-theme-style="light"]`).classList.remove('d-none');
                                            break;

                                        case 'light':
                                            css.setAttribute('href', <?//= json_encode(SITE_URL . ASSETS_URL_PATH . 'css/' . \Altum\ThemeStyle::$themes['light']['file'] . '?v=' . PRODUCT_CODE) ?>);
                                            document.querySelector(`[data-choose-theme-style="dark"]`).classList.remove('d-none');
                                            document.querySelector(`[data-choose-theme-style="light"]`).classList.add('d-none');
                                            break;
                                    }

                                    event.preventDefault();
                                });

                            })
                        </script>
                        <?php //\Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

                    <?php //endif ?>

                </div>
-->

            <div class="col-12 col-sm-4 mb-4 mb-sm-0">
                <?php foreach($data->pages as $data): ?>
                   <a href="<?= $data->url ?>" target="<?= $data->target ?>"><?= $data->title ?></a><br />
                <?php endforeach ?>
            </div>

        </div>
    </div>
</footer>
