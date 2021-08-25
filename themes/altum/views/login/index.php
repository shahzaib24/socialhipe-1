<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">

    <div class="d-flex flex-column align-items-center">
        <div class="col-xs-12 col-md-12 col-lg-10">
            <?php display_notifications() ?>

            <div class="card border-0 flex-row">
                <div class="card-body shadow-md p-5">

                    <h4 class="card-title"><?= $this->language->login->header ?></h4>

                    <form action="" method="post" class="mt-4" role="form">
                        <div class="form-group">
                            <label for="email"><?= $this->language->login->form->email ?></label>
                            <input id="email" type="text" name="email" class="form-control form-control-lg" placeholder="<?= $this->language->login->form->email_placeholder ?>" value="<?= $data->values['email'] ?>" required="required" autofocus="autofocus" />
                        </div>

                        <div class="form-group">
                            <label for="password"><?= $this->language->login->form->password ?></label>
                            <input id="password" type="password" name="password" class="form-control form-control-lg" placeholder="<?= $this->language->login->form->password_placeholder ?>" <?= $data->login_account ? 'value="' . $data->values['password'] . '"' : null ?> required="required" />
                        </div>

                        <?php if($data->login_account && $data->login_account->twofa_secret && $data->login_account->active): ?>
                            <div class="form-group">
                                <label for="twofa_token"><?= $this->language->login->form->twofa_token ?></label>
                                <input id="twofa_token" type="text" name="twofa_token" class="form-control form-control-lg" placeholder="<?= $this->language->login->form->twofa_token_placeholder ?>" required="required" autocomplete="off" />
                            </div>
                        <?php endif ?>

                        <?php if($this->settings->captcha->login_is_enabled): ?>
                        <div class="form-group">
                            <?php $data->captcha->display() ?>
                        </div>
                        <?php endif ?>

                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="rememberme">
                                <small class="form-text text-muted"><?= $this->language->login->form->remember_me ?></small>
                            </label>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" name="submit" class="btn btn-primary btn-block my-1"><?= $this->language->login->form->login ?></button>
                        </div>

                        <?php if($this->settings->facebook->is_enabled): ?>
                            <div class="d-flex align-items-center my-3">
                                <div class="line bg-gray-300"></div>
                                <div class="mx-3"><small class=""><?= $this->language->login->form->or ?></small></div>
                                <div class="line bg-gray-300"></div>
                            </div>

                            <div class="row">
                                <div class="col-sm mt-1">
                                    <a href="<?= $data->facebook_login_url ?>" class="btn btn-light btn-block text-gray-600"><?= sprintf($this->language->login->display->facebook, "<i class=\"fab fa-fw fa-facebook\"></i>") ?></a>
                                </div>
                            </div>
                        <?php endif ?>

                        <div class="mt-4 text-center">
                            <small><a href="lost-password" class="text-muted"><?= $this->language->login->display->lost_password ?></a> / <a href="resend-activation" class="text-muted" role="button"><?= $this->language->login->display->resend_activation ?></a></small>
                        </div>
                    </form>
                </div>

                <div class="card-image card-image-login shadow-md p-5">
                    <p class="h1 text-white mb-3"><?= nr($data->total_track_notifications) ?>+</p>
                    <p class="h4 text-gray-200"><?= $this->language->login->display->total_track_notifications ?></p>
                </div>
            </div>

            <?php if($this->settings->register_is_enabled): ?>
                <div class="text-center mt-4">
                    <?= sprintf($this->language->login->display->register, '<a href="' . url('register') . '" class="font-weight-bold">' . $this->language->login->display->register_help . '</a>') ?></a>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
