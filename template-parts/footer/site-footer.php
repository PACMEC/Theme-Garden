<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    Themes
 * @category   Garden
 * @version    1.0.1
 */
?>
<style>
#tree_bg {
  background-image: url('https://pacmec.monteverdeltda.com/uploads/2021/00/bg_footer.jpg');
  background-repeat: no-repeat;
}
</style>
<div>
  <footer id="footer">
      <div class="container">
          <div class="row">
              <div class="col-md-5 col-lg-3">
                  <div class="footer-logo">
                      <a href="index.html">
                        <?php if(siteinfo('sitelogo_alt') !== 'NaN'): ?>
                          <img src="<?= siteinfo('sitelogo_alt'); ?>" alt="<?= siteinfo('sitename'); ?>">
                        <?php else: ?>
                          <?= siteinfo('sitename'); ?>
                        <?php endif; ?>
                      </a>
                  </div>
                  <p><?= infosite('sitedescr'); ?></p>
              </div>

              <div class="col-md-3 col-lg-2">
                  <h5 class="footer-title"><?= _autoT('languajes'); ?></h5>
                  <ul class="me-list">
                      <?php
                        foreach ($GLOBALS['PACMEC']['glossary'] as $key => $lang) {
                          echo "<li><a href=\"?&lang={$key}\">"._autoT("glossary_{$lang['id']}")."</a></li>";
                        }
                      ?>
                  </ul>
                  <h5 class="footer-title"><?= _autoT('other_links'); ?></h5>
                  <?= do_shortcode('[pacmec-ul-no-list-style menu_slug="other_links" class="me-list"][/pacmec-ul-no-list-style]'); ?>
              </div>

              <div class="col-md-4 col-lg-3">
                  <h5 class="footer-title"><?= _autoT('contact_quick_link'); ?></h5>
                  <?= do_shortcode('[pacmec-ul-no-list-style menu_slug="contact_quick_link" target="_self" class="me-list"][/pacmec-ul-no-list-style]'); ?>
                  <!--//
                  <p>
                      <?php
                        $sales = explode(' ',  _autoT('about_sales')); $sales_name = $sales[0]; unset($sales[0]); $sales_phone = implode(' ', $sales);
                        $admon = explode(' ',  _autoT('about_admin')); $admon_name = $admon[0]; unset($admon[0]); $admon_phone = implode(' ', $admon);
                        $email = _autoT('about_email');
                      ?>
                      <strong><?= $sales_name; ?>:</strong> <br> <a class="text-color" href="#"> <?= $sales_phone; ?> </a><br>
                      <strong><?= $admon_name; ?>:</strong> <br> <a class="text-color" href="#"> <?= $admon_phone; ?> </a><br>
                      <strong></strong> <br> <a class="text-color" href="emailto:<?= $email; ?>"> <?= $email; ?> </a><br>
                  </p>
                -->
              </div>

              <div class="col-md-12 col-lg-4">
                  <h5 class="footer-title">Subscribe For Great Promo</h5>
                  <p>Join with our subscribers and get special price,<br>free garden magazine, promo product announcements and much more!</p>
                  <form class="subscribe-form">
                      <div class="input-group mb-3">
                          <input type="text" class="form-control" placeholder="Enter Email Address" aria-label="Enter Email Address" aria-describedby="button-subscribe">
                          <div class="input-group-append">
                              <button class="btn btn-default" type="button" id="button-subscribe">Subscribe</button>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
      <div class="footer-bottom">
          <div class="container">
              <div class="row">
                  <div class="col-md-12 text-center">
                      <div class="copyright pull-left">
                        <!--//&copy; All rights reserved 2016, <strong>Zengarden - Foundstrap Studio</strong> | -->
                        <?= PowBy(); ?>
                      </div>
                      <?php
                        $menu = \pacmec_load_menu('social_links');
                        if($menu !== false){
                          $r_html = "";
                          foreach ($menu->items as $key => $item1) {
                            $item1->class = [$item1->title."-color"];
                            $r_html .= pacmec_menu_item_to_li($item1, '_blank', false, false, false);
                          }
                          echo \PHPStrap\Util\Html::tag("ul", $r_html, ['footer-social social-icon pull-right'], []);
                        }
                        ?>
                  </div>
              </div>
          </div>
      </div>
  </footer>
</div>
