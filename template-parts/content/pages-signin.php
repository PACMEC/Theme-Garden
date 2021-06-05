<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    Themes
 * @category   System
 * @copyright  2020-2021 FelipheGomez
 * @version    1.0.1
*/
?>

<div class="section section-margin">
  <div class="container">
    <div class="row mb-n10">
      <div class="col-lg-6 col-md-8 m-auto m-lg-0 pb-10">
        <div class="login-wrapper">
          <div class="section-content text-center mb-5">
            <h2 class="title mb-2"><?= __a('signin'); ?></h2>
            <?= do_shortcode('[pacmec-form-signin][/pacmec-form-signin]'); ?>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-8 m-auto m-lg-0 pb-10">
        <div class="register-wrapper">
          <div class="section-content text-center mb-5">
            <h2 class="title mb-2"><?= __a('meregister'); ?></h2>
          </div>
          <?= do_shortcode('[pacmec-form-register][/pacmec-form-register]'); ?>

          <!-- Checkbox & Subscribe Label Start
          <div class="single-input-item mb-3">
              <div class="login-reg-form-meta d-flex align-items-center justify-content-between">
                  <div class="remember-meta mb-3">
                      <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="rememberMe-2">
                          <label class="custom-control-label" for="rememberMe-2">Subscribe Our Newsletter</label>
                      </div>
                  </div>
              </div>
          </div>-->
        </div>
      </div>
    </div>
  </div>
</div>
