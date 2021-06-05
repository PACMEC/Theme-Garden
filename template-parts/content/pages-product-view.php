<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    Themes
 * @category   Destry
 * @version    0.1
 */
global $PACMEC;
$PRODUCT = ($PACMEC['route']->product);

$in_gallery_sql_p_w = "";
$in_gallery_sql_p_a = "";
if(\infosite('with_gallery')==true) {
  $sql_gallery = "SELECT GROUP_CONCAT(PP.`product`) as ids
    FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_pictures')}` PP
    INNER JOIN `{$GLOBALS['PACMEC']['DB']->getTableName('products')}` P
    WHERE P.is_active = 1";
  $in_gallery = $GLOBALS['PACMEC']['DB']->FetchObject($sql_gallery, []);
  $in_gallery_sql_p_w = " WHERE `id` IN ({$in_gallery->ids}0) ";
  $in_gallery_sql_p_a = " AND P.`id` IN ({$in_gallery->ids}0) ";
}
$sql_products = "SELECT P.*
  FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_filters')}` PF
  INNER JOIN `{$GLOBALS['PACMEC']['DB']->getTableName('products')}` P
  ON P.`id`=PF.`product`
  	WHERE P.`is_active` IN ('1')
    AND P.`available` >= '0'
    {$in_gallery_sql_p_a}
    ";
$i = 0;
$labels = [];
foreach ($PRODUCT->features as $feature){
  if ($feature->input_type == 'text'){
    foreach ($feature->items as $item){
      $labels[] = $item->text;
    }
  }
}
if(count($labels)>0){
  $sql_products .= "AND (";
  foreach ($labels as $_txt) {
    if($i>0) $sql_products .= " OR ";
    $sql_products .= " PF.`text` LIKE '%".urldecode($_txt)."%' ";
    $i++;
  }
  $sql_products .= ")  ";
}
$sql_products .= " {$in_gallery_sql_p_a} GROUP BY P.`id`  LIMIT 9 ";
//  OR P.`available`>0
$MORE_PRODUCTS = [];

foreach ($GLOBALS['PACMEC']['DB']->FetchAllObject($sql_products, []) as $a) {
  $MORE_PRODUCTS[] = new \PACMEC\System\Product((object) ["id"=>$a->id]);
}

#echo json_encode($PRODUCT, JSON_PRETTY_PRINT);
?>
<div class="section section-margin">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 offset-lg-0 col-md-8 offset-md-2 col-custom">
          <div class="product-details-img d-flex overflow-hidden flex-row">
              <div class="single-product-vertical-tab swiper-container gallery-top order-2">
                  <div class="swiper-wrapper popup-gallery">
                    <?php foreach ($PRODUCT->gallery as $picture): ?>
                      <a class="swiper-slide h-auto" href="<?= $picture; ?>">
                          <img class="w-100" src="<?= $picture; ?>" alt="Product">
                      </a>
                    <?php endforeach; ?>
                  </div>
                  <!-- Swiper Pagination Start -->
                  <!-- <div class="swiper-pagination d-none"></div> -->
                  <!-- Swiper Pagination End -->
                  <!-- Next Previous Button Start -->
                  <!-- <div class="swiper-product-tab-next swiper-button-next"><i class="pe-7s-angle-right"></i></div>
                      <div class="swiper-product-tab-prev swiper-button-prev"><i class="pe-7s-angle-left"></i></div> -->
                  <!-- Next Previous Button End -->
              </div>
              <div class="product-thumb-vertical overflow-hidden swiper-container gallery-thumbs order-1">
                <div class="swiper-wrapper">
                  <?php foreach ($PRODUCT->gallery as $picture): ?>
                    <div class="swiper-slide">
                        <img src="<?= $picture; ?>" alt="Product">
                    </div>
                  <?php endforeach; ?>
                </div>
                <!-- Swiper Pagination Start -->
                <!-- // <div class="swiper-pagination d-none"></div> -->
                <!-- Swiper Pagination End -->
                  <div class="swiper-button-vertical-next  swiper-button-next"><i class="pe-7s-angle-right"></i></div>
                  <div class="swiper-button-vertical-prev swiper-button-prev"><i class="pe-7s-angle-left"></i></div>
              </div>
          </div>
      </div>
      <div class="col-lg-6 col-custom">
        <div class="product-summery position-relative">
            <div class="product-head mb-3">
                <h2 class="product-title"><?= $PRODUCT->name; ?></h2>
            </div>
            <div class="price-box mb-2">
              <?php if ($PRODUCT->in_promo): ?>
                <span class="regular-price"><?= formatMoney($PRODUCT->price_promo); ?></span>
                <span class="old-price"><del><?= formatMoney($PRODUCT->price); ?></del></span>
              <?php else: ?>
                <span class="regular-price"><?= formatMoney($PRODUCT->price); ?></span>
              <?php endif; ?>
            </div>
            <?php if (infosite('comments_enabled')==true && $PACMEC['route']->comments_enabled): ?>
              <span class="ratings justify-content-start">
                <span class="rating-wrap">
                  <span class="star" style="width: <?= $PRODUCT->rating_porcen; ?>%"></span>
                </span>
                <span class="rating-num">(<?= (int) $PRODUCT->rating_number; ?>)</span>
              </span>
            <?php endif; ?>
            <div class="sku mb-3">
                <span><?=__a('sku_ref');?>: <?= $PRODUCT->sku; ?></span>
            </div>
            <?php if (!empty($PRODUCT->common_names)): ?>
              <div class="sku mb-3">
                <span><b><?=__a('common_names');?></b> <?= implode(', ', $PRODUCT->common_names); ?></span>
              </div>
            <?php endif; ?>
            <p class="desc-content mb-5">
                <?= $PRODUCT->description; ?>
            </p>

            <?php if ($PRODUCT->available>0): ?>
              <?php
              $msg_add_product = null;

              ?>
              <?php
              if ($msg_add_product!==null):
                echo $msg_add_product;
              endif;
              ?>
              <form method="POST" >
                <input name="product_id" value="<?= (int) $PRODUCT->id; ?>" type="hidden" readonly="" />
                <input name="type" value="product" type="hidden" readonly="" />
                <div class="quantity mb-5">
                  <label><?= ($PRODUCT->unid); ?></label>
                  <div class="cart-plus-minus">
                    <input name="quantity" class="cart-plus-minus-box" value="0" type="text" max="<?= (int) $PRODUCT->available; ?>" step="1" />
                    <div class="dec qtybutton"></div>
                    <div class="inc qtybutton"></div>
                  </div>
                </div>
                <div class="cart-wishlist-btn mb-4">
                  <div class="add-to_cart">
                    <button name="add-product-in-cart" type="submit" class="btn btn-outline-dark btn-hover-primary" href="#"><?= __a('add_to_cart'); ?></button>
                  </div>
                  <!--// <div class="add-to-wishlist"><a class="btn btn-outline-dark btn-hover-primary" href="wishlist.html">Add to Wishlist</a></div> -->
                </div>
              </form>
              <script>
                window.addEventListener('load', function(){
                  $('.pacmec-cart-plus-minus').append(
                    '<div class="dec qtybutton"><i class="fa fa-minus"></i></div><div class="inc qtybutton"><i class="fa fa-plus"></i></div>'
                  );

                });
              </script>
            <?php endif; ?>
            <?= do_shortcode('[mt_destry-social-share][/mt_destry-social-share]'); ?>
            <ul class="product-delivery-policy border-top pt-4 mt-4 border-bottom pb-4">
                <li> <i class="fa fa-check-square"></i> <span> <?= __a('total_available_from_site'); ?>: <?= (int) $PRODUCT->available; ?> <?= ($PRODUCT->unid); ?> </span></li>
                <li><i class="fa fa-truck"></i><span><?= __a('delivery_territory'); ?></span></li>
                <li><i class="fa fa-exclamation"></i><span><?= __a('unid_product_view'); ?>: <?= ($PRODUCT->unid); ?></span></li>
            </ul>
        </div>
      </div>
    </div>

  <?php if (!isset($GLOBALS['PACMEC']['route']->is_embeded) || $GLOBALS['PACMEC']['route']->is_embeded !== true): ?>
    <div class="row section-margin">
      <div class="col-lg-12 col-custom single-product-tab">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link text-uppercase" id="home-tab" data-bs-toggle="tab" href="#connect-1" role="tab" aria-selected="false"><?= __a('description'); ?></a>
            </li>
            <?php if (infosite('comments_enabled')==true && $PACMEC['route']->comments_enabled): ?>
              <li class="nav-item">
                <a class="nav-link text-uppercase" id="profile-tab" data-bs-toggle="tab" href="#connect-2" role="tab" aria-selected="false"><?= __a('reviews_or_comments'); ?></a>
              </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link text-uppercase" id="contact-tab" data-bs-toggle="tab" href="#connect-3" role="tab" aria-selected="false"><?= __a('shipping_policy'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link active text-uppercase" id="review-tab" data-bs-toggle="tab" href="#connect-4" role="tab" aria-selected="true"><?= __a('features'); ?></a>
            </li>
        </ul>
        <div class="tab-content mb-text" id="myTabContent">
          <div class="tab-pane fade" id="connect-1" role="tabpanel" aria-labelledby="home-tab">
            <div class="desc-content border p-3">
              <p class="mb-3"><?= (empty($PRODUCT->description_full) ? $PRODUCT->description : $PRODUCT->description_full); ?></p>
            </div>
          </div>
          <div class="tab-pane fade" id="connect-2" role="tabpanel" aria-labelledby="profile-tab">
            <?= do_shortcode("[mt_destry-widget-comments][/mt_destry-widget-comments]"); ?>
            <div class="product_tab_content  border p-3">
            </div>
          </div>
          <div class="tab-pane fade" id="connect-3" role="tabpanel" aria-labelledby="contact-tab">
            <div class="desc-content border p-3">
              <div class="shipping-policy mb-n2">
                <h4 class="title-3 mb-4"><?= __a('shipping_policy_title'); ?></h4>
                <?= __a('shipping_policy_content'); ?>
              </div>
            </div>
          </div>
          <div class="tab-pane fade show active" id="connect-4" role="tabpanel" aria-labelledby="review-tab">
            <div class="desc-content  p-3">
              <div class="size-tab table-responsive-lg">
                <h4 class="title-3 mb-4"><?= __a('features'); ?></h4>
                <table class="table border mb-0">
                    <tbody>
                      <?php
                        foreach ($PRODUCT->features as $feature): ?>
                        <tr>
                          <?php
                          echo "<td class=\"cun-name\"><span>".($feature->autot==1?__a($feature->name):$feature->name)."</span></td>";
                          echo "<td>";
                            switch ($feature->input_type) {
                              case 'text':
                                  $labels = [];
                                  foreach ($feature->items as $item){ $labels[] = $item->text; }
                                  echo implode(', ', $labels);
                                break;
                              case 'icon':
                                $labels = [];
                                foreach ($feature->items as $item){
                                  $a = unserialize($item->text);
                                  switch ($a['status']) {
                                    case 'check':
                                      $icon = "fa fa-check-circle";
                                      break;
                                    case 'uncheck':
                                      $icon = "fa fa-times-circle";
                                      break;
                                    case 'minus':
                                      $icon = "fa fa-minus-circle";
                                      break;
                                    case 'question':
                                      $icon = "fa fa-question-circle";
                                      break;
                                    case 'exclamation':
                                      $icon = "fa fa-exclamation-circle";
                                      break;
                                    case 'info':
                                      $icon = "fa fa-info-circle";
                                      break;
                                    default:
                                      $icon = "fa fa-circle-o";
                                      break;
                                  }
                                  $labels[] = \PHPStrap\Util\Html::tag('i', '', [$icon]) . " {$a['label']}";
                                }
                                echo implode(', ', $labels);
                                break;
                              case 'color':
                                $labels = [];
                                foreach ($feature->items as $item){
                                  $a = unserialize($item->text);
                                  $labels[] = \PHPStrap\Util\Html::tag('i', '', ["fa fa-circle"], ['style'=>"color:{$a['color']}"]) . " {$a['label']}";
                                }
                                echo implode(', ', $labels);
                                break;
                              default:
                                echo "invalid.";
                                break;
                            }
                          echo "</td>";
                          ?>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="section-title aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">
                <h2 class="title pb-3"><?= __a('you_might_also_like'); ?></h2>
                <span></span>
                <div class="title-border-bottom"></div>
            </div>
        </div>
        <div class="col">
            <div class="product-carousel">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php
                          $startdelay = 300;
                          $stepsdelay = 100;
                          foreach ($MORE_PRODUCTS as $product_m):
                            ?>
                            <div class="swiper-slide product-wrapper">
                              <?php
                              #mt_destry_product_style1($product_m, ["product product-border-left"], ["data-aos"=>"fade-up", "data-aos-delay"=>$startdelay]);
                              ?>
                            </div>
                          <?php
                        $startdelay += $stepsdelay;
                      endforeach; ?>
                    </div>
                    <div class="swiper-pagination d-md-none"></div>
                    <div class="swiper-product-button-next swiper-button-next swiper-button-white d-md-flex d-none"><i class="pe-7s-angle-right"></i></div>
                    <div class="swiper-product-button-prev swiper-button-prev swiper-button-white d-md-flex d-none"><i class="pe-7s-angle-left"></i></div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
  </div>
</div>
