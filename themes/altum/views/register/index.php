<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">

    <div class="d-flex flex-column align-items-center">
        <div class="col-xs-12 col-md-12 col-lg-10">
            <?php display_notifications() ?>

            <div class="card border-0 flex-row">
                <div class="card-body shadow-md p-5">

                    <h4 class="card-title"><?= $this->language->register->header ?></h4>

                    <form action="" method="post" class="mt-4" role="form">
                        <div class="form-group">
                            <label for="name"><?= $this->language->register->form->name ?></label>
                            <input id="name" type="text" name="name" class="form-control" value="<?= $data->values['name'] ?>" placeholder="<?= $this->language->register->form->name_placeholder ?>" required="required" autofocus="autofocus" />
                        </div>

                        <div class="form-group">
                            <label for="email"><?= $this->language->register->form->email ?></label>
                            <input id="email" type="text" name="email" class="form-control" value="<?= $data->values['email'] ?>" placeholder="<?= $this->language->register->form->email_placeholder ?>" required="required" />
                        </div>

                        <div class="form-group">
                            <label for="password"><?= $this->language->register->form->password ?></label>
                            <input id="password" type="password" name="password" class="form-control" value="<?= $data->values['password'] ?>" placeholder="<?= $this->language->register->form->password_placeholder ?>" required="required" />
                        </div>

                        <?php if($this->settings->captcha->register_is_enabled): ?>
                            <div class="form-group">
                                <?php $data->captcha->display() ?>
                            </div>
                        <?php endif ?>

                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" name="accept" type="checkbox" required="required">
                                <small class="form-text text-muted">
                                    <?= sprintf(
                                        $this->language->register->form->accept,
                                        '<a href="' . $this->settings->terms_and_conditions_url . '" target="_blank">' . $this->language->global->terms_and_conditions . '</a>',
                                        '<a href="' . $this->settings->privacy_policy_url . '" target="_blank">' . $this->language->global->privacy_policy . '</a>'
                                    ) ?>
                                </small>
                            </label>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" name="submit" class="btn btn-primary btn-block"><?= $this->language->register->form->register ?></button>
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
                    </form>
                </div>

                <div class="card-image card-image-register shadow-md p-5">
                    <p class="h1 text-white mb-3"><?= nr($data->total_notifications) ?>+</p>
                    <p class="h4 text-gray-200"><?= $this->language->register->display->total_notifications ?></p>
                </div>
            </div>

            <div class="text-center mt-4">
                <small><a href="login" class="text-muted"><?= $this->language->register->login ?></a></small>
            </div>
        </div>
    </div>
</div>


