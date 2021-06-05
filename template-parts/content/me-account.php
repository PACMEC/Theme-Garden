<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    Themes
 * @category   Destry
 * @version    0.1
 */
global $PACMEC;
$page_section = isset($PACMEC['fullData']['tab']) ? $PACMEC['fullData']['tab'] : 'dashboard';
# the_content();
$ME = ($PACMEC['route']->user);
?>
<div class="section section-margin">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="myaccount-page-wrapper">
          <div class="row">
            <!--//
              <div class="col-lg-3 col-md-4">
                <div class="myaccount-tab-menu nav" role="tablist">
                  <a href="?tab=dashboard"          <?= $page_section=='dashboard'?          ' class="active" ' : ''; ?>><i class="fa fa-dashboard"></i> Dashboard</a>
                  <a href="?tab=notifications"          <?= $page_section=='notifications'?  ' class="active" ' : ''; ?>><i class="fa fa-bell-o"></i> <?= __a('me_notifications') . " (" . count(\PACMEC\System\Notifications::get_all_by_user_id(null, false)) . ")"; ?></a>
                  <a href="?tab=me_account_details" <?= $page_section=='me_account_details'? ' class="active" ' : ''; ?>><i class="pe-7s-news-paper"></i> <?= __a('me_account_details'); ?></a>
                  <a href="?tab=me_access_details"  <?= $page_section=='me_access_details'?  ' class="active" ' : ''; ?>><i class="pe-7s-news-paper"></i> <?= __a('me_access_details'); ?></a>
                  <a href="?tab=change_pass"        <?= $page_section=='change_pass'?        ' class="active" ' : ''; ?>><i class="pe-7s-news-paper"></i> <?= __a('change_pass'); ?></a>
                  <a href="?tab=me_orders"          <?= $page_section=='me_orders'?          ' class="active" ' : ''; ?>><i class="pe-7s-news-paper"></i> <?= __a('me_orders'); ?></a>
                  <a href="?tab=me_payments"        <?= $page_section=='me_payments'?        ' class="active" ' : ''; ?>><i class="pe-7s-news-paper"></i> <?= __a('me_payments'); ?></a>
                  <?php if (infosite('address_in_users')==true): ?>
                    <a href="?tab=me_addresses"        <?= $page_section=='me_addresses'?      ' class="active" ' : ''; ?>><i class="fa fa-map-marker"></i> <?= __a('me_addresses'); ?></a>
                  <?php endif; ?>
                  <a href="?pacmec_close=1"><i class="fa fa-sign-out"></i> <?= __a('signout'); ?></a>
                </div>
              </div>
            -->
            <div class="col-lg-12 col-md-12">
              <div class="tab-content" id="myaccountContent">
                <div class="tab-pane fade show active" id="<?= $page_section; ?>" role="tabpanel">
                  <div class="myaccount-content">
                    <h3 class="title"><?= __a($page_section); ?></h3>
                    <div class="account-details-form">
                      <?php
                        switch ($page_section) {
                          case 'me_account_details':
                            echo do_shortcode('[pacmec-form-me-info][/pacmec-form-me-info]');
                            break;
                          case 'me_access_details':
                            echo do_shortcode('[pacmec-form-me-change-access][/pacmec-form-me-change-access]');
                            break;
                          case 'change_pass':
                            echo do_shortcode('[pacmec-form-me-change-pass][/pacmec-form-me-change-pass]');
                            break;
                          case 'dashboard':
                            echo do_shortcode('[pacmec-me-welcome-small][/pacmec-me-welcome-small]');
                            break;
                          case 'me_orders':
                            echo do_shortcode('[pacmec-me-orders-table][/pacmec-me-orders-table]');
                            break;
                          case 'notifications':
                            echo do_shortcode('[pacmec-me-notifications-table][/pacmec-me-notifications-table]');
                            break;
                          case 'me_payments':
                            echo do_shortcode('[pacmec-me-payments-table][/pacmec-me-payments-table]');
                            break;
                          case 'me_addresses':
                            echo do_shortcode('[pacmec-me-addresses-table][/pacmec-me-addresses-table]');
                            break;
                          case 'add_address':
                            echo do_shortcode('[pacmec-me-add-address][/pacmec-me-add-address]');
                            break;
                          default:
                            break;
                        }
                      ?>
                    </div>
                  </div>
                  <br />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
