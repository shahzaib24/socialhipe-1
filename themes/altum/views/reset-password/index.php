<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">

    <div class="d-flex flex-column align-items-center">
        <div class="col-xs-12 col-sm-10 col-md-7 col-lg-5">
            <?php display_notifications() ?>

            <div class="card border-0 shadow-md">
                <div class="card-body p-5">

                    <h4 class="card-title"><?= $this->language->reset_password->header ?></h4>

                    <form action="" method="post" class="mt-4" role="form">
                        <input type="hidden" name="email" value="<?= $data->values['email'] ?>" class="form-control" />

                        <div class="form-group">
                            <label for="new_password"><?= $this->language->reset_password->form->new_password ?></label>
                            <input id="new_password" type="password" name="new_password" class="form-control"  required="required" autofocus="autofocus" />
                        </div>

                        <div class="form-group">
                            <label for="repeat_password"><?= $this->language->reset_password->form->repeat_password ?></label>
                            <input id="repeat_password" type="password" name="repeat_password" class="form-control" required="required" />
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" name="submit" class="btn btn-primary btn-block my-1"><?= $this->language->global->submit ?></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <small><a href="login" class="text-muted"><?= $this->language->reset_password->return ?></a></small>
            </div>
        </div>
    </div>
</div>
