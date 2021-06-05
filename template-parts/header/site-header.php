<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    Themes
 * @category   Garden
 * @version    1.0.1
 */
global $PACMEC;
$page_section = isset($PACMEC['fullData']['tab']) ? $PACMEC['fullData']['tab'] : 'dashboard';
$meinfo = meinfo();
$notifications = \PACMEC\System\Notifications::get_all_by_user_id(null, false);
?>
<header id="header" class="header-version2">
  <div class="header-top">
      <div class="container">
          <div class="row">
              <div class="col-md-12">
                  <ul class="header-info pull-left">
                    <li><i class="fa fa-phone" aria-hidden="true"></i> <?= infosite('business_phone_number'); ?> </li>
                    <li><a href="mailto:<?= infosite('business_email'); ?>"><i class="fa fa-envelope" aria-hidden="true"></i> <?= infosite('business_email'); ?> </a></li>
                    <?= "<li><i class=\"fa fa-clock-o\" aria-hidden=\"true\"></i>".infosite('business_hours')."</li>"; ?>
                  </ul>
                  <div class="pull-right">
                    <?php
                      $menu = \pacmec_load_menu('socials_links');
                      if($menu !== false){
                        $r_html = "";
                        foreach ($menu->items as $key => $item) {
                          $r_html .= pacmec_menu_item_to_li($item, [], [], false, true, true, [], [], []);
                        }
                        echo \PHPStrap\Util\Html::tag("ul", $r_html, ['header-social social-icon'], []);
                      }
                      ?>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <div class="header-main">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="header-logo">
            <a href="<?= siteinfo('homeurl'); ?>">
              <?php if(siteinfo('sitelogo') !== 'NaN'): ?>
                <img src="<?= siteinfo('sitelogo'); ?>" alt="<?= siteinfo('sitename'); ?>">
                <img class="logo-responsive" src="<?= siteinfo('sitelogo'); ?>" alt="<?= siteinfo('sitename'); ?>">
                <img class="logo-sticky" src="<?= siteinfo('sitelogo'); ?>" alt="<?= siteinfo('sitename'); ?>">
              <?php else: ?>
                <?= siteinfo('sitename'); ?>
              <?php endif; ?>
            </a>
          </div>
          <nav class="menu-container">
            <ul id="menu" class="sm me-menu">
              <?php
                $menu = \pacmec_load_menu('primary_garden');
                if($menu !== false):
                  foreach ($menu->items as $key => $item) {
          					echo pacmec_menu_item_to_li($item, ['has-children'], [], true, true, true, ['sub-menu'], [], []);
          					//$r_html .= pacmec_menu_item_to_li($item1, NULL, true, true, false);
                  }
                ?>
                <li class="mega-menu">
                    <a href="#">
                      <i class="fa fa-question"></i>
                      <?= _autoT('help'); ?>
                    </a>
                    <ul class="mega-menu">
                        <li>
                            <div class="mega-menu-container">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h5 class="mega-menu-title"><?= _autoT('advisory'); ?></h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= do_shortcode('[pacmec-ul-no-list-style menu_slug="advisory" class="mega-menu-list responsive-no-border"][/pacmec-ul-no-list-style]'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h5 class="mega-menu-title"><?= _autoT('contact_quick_link'); ?></h5>
                                        <?= do_shortcode('[pacmec-ul-no-list-style menu_slug="contact_quick_link" target="_blank" class="mega-menu-list"][/pacmec-ul-no-list-style]'); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <h5 class="mega-menu-title"><?= _autoT('other_links'); ?></h5>
                                        <?= do_shortcode('[pacmec-ul-no-list-style menu_slug="other_links" class="mega-menu-list"][/pacmec-ul-no-list-style]'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <h5 class="mega-menu-title"><?= _autoT('contact_info_title'); ?></h5>
                                        <div class="mega-menu-content">
                                            <p>
                                                <?= _autoT('contact_info_short_content'); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <img class="mega-menu-img" src="<?= get_template_directory_uri(); ?>/assets/img/megamenu-bg.png" data-position="right bottom" alt="mega menu image">
                            </div>
                        </li>
                    </ul>
                </li>
                <?php if(isGuest()): ?>
                  <li>
                    <a href="<?= __url_s("/%pacmec_signin%"); ?>">
                      <i class="fa fa-sign-out" aria-hidden="true"></i> <?= _autoT('signin'); ?>
                    </a>
                  </li>
                <?php else: ?>
                  <!-- //
                  <li class="mega-menu"><a href="#"><i class="fal fa-user-hard-hat"></i><?= _autoT('employees'); ?></a>
                      <ul class="mega-menu">
                          <li>
                              <div class="mega-menu-container">
                                  <div class="row">
                                      <div class="col-md-5">
                                          <h5 class="mega-menu-title"><?= _autoT('me_account'); ?></h5>
                                          <div class="row">
                                              <div class="col-md-6">
                                                  <ul class="mega-menu-list">
                                                      <li><a href="YYYYYYYYYYY">Mis facturas</a></li>
                                                  </ul>
                                              </div>
                                              <div class="col-md-6">
                                                  <ul class="mega-menu-list responsive-no-border">
                                                      <li><a href="YYYYYYYYYYY">Mis direcciones</a></li>
                                                      <li><a href="YYYYYYYYYYY">Mis metodos de pago</a></li>
                                                  </ul>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <h5 class="mega-menu-title"></h5>
                                          <ul class="mega-menu-list me-list arrow-list">
                                              <li><a href="YYYYYYYYYYY">Actualizar datos</a></li>
                                              <li><a href="YYYYYYYYYYY">Cambiar contraseña</a></li>
                                          </ul>
                                      </div>
                                      <div class="col-md-3">
                                          <h5 class="mega-menu-title"><?= _autoT('other_links'); ?></h5>
                                          <ul class="mega-menu-list">
                                              <li><a href="/pacmec-staff"><i class="fa fa-users" aria-hidden="true"></i> Portal empleados </a></li>
                                              <li><a href="/pacmec-close-session"><i class="fa fa-sign-out" aria-hidden="true"></i> <?= _autoT('signout'); ?></a></li>
                                          </ul>
                                      </div>
                                  </div>
                                  <img class="mega-menu-img" src="img/megamenu-bg.png" data-position="right bottom" alt="mega menu image">
                              </div>
                          </li>
                      </ul>
                  </li>
                  -->
                  <li class="mega-menu"><a href="#"><i class="fal fa-user-circle" aria-hidden="true"></i> <?= _autoT('me_account'); ?></a>
                    <ul class="mega-menu">
                      <li>
                        <div class="mega-menu-container">
                          <div class="row">
                            <div class="col-md-5">
                              <h5 class="mega-menu-title"><?= _autoT('me_account'); ?></h5>
                              <div class="row">
                                <div class="col-md-6">
                                  <ul class="mega-menu-list">
                                    <li><a href="<?= __url_s("/%pacmec_meaccount%?tab="); ?>dashboard"          <?= $page_section=='dashboard'?          ' class="active" ' : ''; ?>><i class="fa fa-dashboard"></i> Dashboard</a></li>
                      	            <li><a href="<?= __url_s("/%pacmec_meaccount%?tab="); ?>notifications"          <?= $page_section=='notifications'?  ' class="active" ' : ''; ?>><i class="fa fa-bell-o"></i> <?= __a('me_notifications') . " (" . count(\PACMEC\System\Notifications::get_all_by_user_id(null, false)) . ")"; ?></a></li>
                                    <?php if (infosite('address_in_users')==true): ?>
                                      <li><a href="<?= __url_s("/%pacmec_meaccount%?tab="); ?>me_addresses"        <?= $page_section=='me_addresses'?      ' class="active" ' : ''; ?>><i class="fa fa-map-marker"></i> <?= __a('me_addresses'); ?></a></li>
                                    <?php endif; ?>
                                  </ul>
                                </div>
                                <div class="col-md-6">
                                  <ul class="mega-menu-list responsive-no-border">
                                    <li><a href="<?= __url_s("/%pacmec_meaccount%?tab="); ?>me_account_details" <?= $page_section=='me_account_details'? ' class="active" ' : ''; ?>><i class="pe-7s-news-paper"></i> <?= __a('me_account_details'); ?></a></li>
                                    <li><a href="<?= __url_s("/%pacmec_meaccount%?tab="); ?>change_pass"        <?= $page_section=='change_pass'?        ' class="active" ' : ''; ?>><i class="pe-7s-news-paper"></i> <?= __a('change_pass'); ?></a></li>
                                    <li><a href="<?= __url_s("/%pacmec_meaccount%?tab="); ?>me_access_details"  <?= $page_section=='me_access_details'?  ' class="active" ' : ''; ?>><i class="pe-7s-news-paper"></i> <?= __a('me_access_details'); ?></a></li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <h5 class="mega-menu-title">&nbsp;</h5>
                              <ul class="mega-menu-list me-list arrow-list">
                                <li><a href="<?= __url_s("/%pacmec_meaccount%?tab="); ?>me_orders"          <?= $page_section=='me_orders'?          ' class="active" ' : ''; ?>><i class="pe-7s-news-paper"></i> <?= __a('me_orders'); ?></a></li>
                                <li><a href="<?= __url_s("/%pacmec_meaccount%?tab="); ?>me_payments"        <?= $page_section=='me_payments'?        ' class="active" ' : ''; ?>><i class="pe-7s-news-paper"></i> <?= __a('me_payments'); ?></a></li>
                              </ul>
                            </div>
                            <div class="col-md-3">
                              <h5 class="mega-menu-title"></h5>
                              <h5 class="mega-menu-title"><?= _autoT('other_links'); ?></h5>
                              <ul class="mega-menu-list">
                                <li><a href="https://empleados.monteverdeltda.com/"><i class="fa fa-users" aria-hidden="true"></i> Portal empleados </a></li>
                                <li><a href="?pacmec_close=1"><i class="fa fa-sign-out" aria-hidden="true"></i> <?= _autoT('signout'); ?></a></li>
                              </ul>
                            </div>
                          </div>
                          <img class="mega-menu-img" src="img/megamenu-bg.png" data-position="right bottom" alt="mega menu image">
                        </div>
                      </li>
                    </ul>
                  </li>
                <?php endif; ?>
              </ul>
            <?php endif; ?>
            <div class="additional-menu">
              <a class="search-btn" href="#">
                <i class="fa fa-search" aria-hidden="true"></i>
              </a>
              <div class="search-panel">
                <form action="#" class="form-search form-search-rounded">
                  <div class="input-group-placeholder addon-right">
                      <input name="search" type="text" class="form-control" placeholder="Search here..">
                  </div>
                </form>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </div>
</header>
