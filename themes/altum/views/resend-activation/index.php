<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">

    <div class="d-flex flex-column align-items-center">
        <div class="col-xs-12 col-sm-10 col-md-7 col-lg-5">
            <?php display_notifications() ?>

            <div class="card border-0 shadow-md">
                <div class="card-body p-5">
                    <h4 class="card-title d-flex justify-content-between"><?= $this->language->resend_activation->header ?></h4>

                    <form action="" method="post" class="mt-4" role="form">
                        <div class="form-group">
                            <label for="email"><?= $this->language->resend_activation->form->email ?></label>
                            <input id="email" type="text" name="email" class="form-control" value="<?= $data->values['email'] ?>" placeholder="<?= $this->language->resend_activation->form->email_placeholder ?>" required="required" autofocus="autofocus" />
                        </div>

                        <?php if($this->settings->captcha->resend_activation_is_enabled): ?>
                            <div class="form-group">
                                <?php $data->captcha->display() ?>
                            </div>
                        <?php endif ?>

                        <div class="form-group mt-3">
                            <button type="submit" name="submit" class="btn btn-primary btn-block my-1"><?= $this->language->global->submit ?></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <small><a href="login" class="text-muted"><?= $this->language->resend_activation->return ?></a></small>
            </div>
        </div>
    </div>
</div>


