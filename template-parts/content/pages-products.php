<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    Themes
 * @category   Destry
 * @version    0.1
 */
global $PACMEC;
$limit_filters = 15;
$prices_range = \PACMEC\System\Products::prices_min_max();
$limit         = 9;
$page          = isset($PACMEC['fullData']['page']) ? $PACMEC['fullData']['page'] : 1;
$order_by      = isset($PACMEC['fullData']['order_by']) ? $PACMEC['fullData']['order_by'] : "default";
$filter_text   = isset($PACMEC['fullData']['filter_text']) ? $PACMEC['fullData']['filter_text'] : [];
$price_min = isset($PACMEC['fullData']['price_min']) ? $PACMEC['fullData']['price_min'] : $prices_range->price_min;
$price_max = isset($PACMEC['fullData']['price_max']) ? $PACMEC['fullData']['price_max'] : $prices_range->price_max;
$inoffert   = isset($PACMEC['fullData']['in_promo']) ? " AND P.`price_normal` > P.`price_promo` AND P.`price_normal` >= '{$price_min}' AND P.`price_normal` <= '{$price_max}' " : " AND P.`price_normal` >= '{$price_min}' AND P.`price_normal` <= '{$price_max}' ";
$offset        = ($page-1)*$limit;
$limit_news    = 3;
$in_gallery_sql1 = "";
$in_gallery_sql2 = "";
$in_gallery_sql = "";
$with_stock_a_P = (infosite('with_stock')==true) ? " AND P.`available` > 0 " : "";
$with_stock_w_P = (infosite('with_stock')==true) ? " WHERE P.`available` > 0 " : "";

if(infosite('with_gallery')==true) {
  # $sql_gallery = "SELECT GROUP_CONCAT(`product`) as ids FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_pictures')}`";
  $sql_gallery = "SELECT GROUP_CONCAT(PP.`product`) as ids
    FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_pictures')}` PP
    INNER JOIN `{$GLOBALS['PACMEC']['DB']->getTableName('products')}` P
    WHERE P.is_active = 1 {$with_stock_a_P}";
  $in_gallery = $GLOBALS['PACMEC']['DB']->FetchObject($sql_gallery, []);
  $in_gallery_sql = " AND P.`id` IN ({$in_gallery->ids}0) ";
  $in_gallery_sql1 = " WHERE PF.`product` IN ({$in_gallery->ids}0)";
  $in_gallery_sql2 = " AND PF.`product` IN ({$in_gallery->ids}0)";
}
$menu_primary = \pacmec_load_menu('primary_destry');
$sql = "SELECT F.*, PF.`text`, COUNT(PF.`id`) AS `total_count`
  FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_filters')}` PF
  INNER JOIN 	`{$GLOBALS['PACMEC']['DB']->getTableName('products_features')}` F
  ON F.`id` = PF.`feature`
   $in_gallery_sql1
  GROUP BY PF.`feature`, PF.`text`
  ORDER BY `total_count` DESC, PF.`text` ASC
  LIMIT {$limit_filters}
  ";
$more_filters = $GLOBALS['PACMEC']['DB']->FetchAllObject($sql, []);
$features = [];
foreach ($GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_features')}`", []) as $feature) {
  if($feature->autot == 1) $feature->name = __a($feature->name);
  $feature->total = 0;
  // $feature->products = [];
  $feature->options = [];
   $sql_filts = "SELECT F.*, PF.`text`, PF.`feature`, PF.`feature_item`, COUNT(PF.`feature`) AS `total_count`
    FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_filters')}` PF
    INNER JOIN `{$GLOBALS['PACMEC']['DB']->getTableName('products_features')}` F
    ON F.`id` = PF.`feature`
      WHERE PF.`feature` IN ({$feature->id})
      {$in_gallery_sql2}
    GROUP BY PF.`feature`, PF.`feature_item`, PF.`text`
    ORDER BY `total_count` DESC, PF.`text` ASC
    LIMIT {$limit_filters}
    ";

  switch ($feature->input_type) {
    case 'text':
      foreach ($GLOBALS['PACMEC']['DB']->FetchAllObject($sql_filts, []) as $fil) {
        if($fil->autot == 1) $fil->name = __a($fil->name);
        $feature->total += (int) $fil->total_count;
        //$feature->total += 1;
        $feature->options[] = (object) [
          "value"  => urlencode($fil->text),
          "text"   => __a($fil->text) . " ({$fil->total_count})"
        ];
      };
      break;
    case 'color':
      foreach ($GLOBALS['PACMEC']['DB']->FetchAllObject($sql_filts, []) as $fil) {
        if($fil->autot == 1) $fil->name = __a($fil->name);
        $feature->total += (int) $fil->total_count;
        $a = unserialize($fil->text);
        $feature->options[] = (object) [
          "value"  => urlencode($a['color']),
          "text"   => \PHPStrap\Util\Html::tag('i', '', ["fa fa-circle"], ['style'=>"color:{$a['color']}"]) . " " . __a($a['label']) . " ({$fil->total_count})"
        ];
      }
      break;
    case 'icon':
      foreach ($GLOBALS['PACMEC']['DB']->FetchAllObject($sql_filts, []) as $fil) {
        if($fil->autot == 1) $fil->name = __a($fil->name);
        $feature->total += (int) $fil->total_count;
        $a = unserialize($fil->text);
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

        $feature->options[] = (object) [
          "value"  => urlencode($a['label']),
          "text"   => \PHPStrap\Util\Html::tag('i', '', [$icon]) . " " . __a($a['label']) . " ({$fil->total_count})"
        ];
      }
      break;
    default:
      break;
  }
  if(!in_array($feature, $features)) $features[] = $feature;
}
//echo json_encode($features)."\n";
$options_order_by = [
  "default" => "order_by_sku_asc",
  "price_asc" => "order_by_price_asc",
  "price_desc" => "order_by_price_desc",
];
switch ($order_by) {
  case 'price_asc':
    $orderby = " ORDER BY P.`price_normal` ASC ";
    break;
  case 'price_desc':
    $orderby = " ORDER BY P.`price_normal` DESC ";
    break;
  default:
    # $orderby = " ORDER BY P.`sku` ASC ";
    $orderby = " ORDER BY P.`updated` DESC ";
    break;
}
if(count($filter_text)==0){
  $sql_products = "SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products')}` P
  WHERE P.`is_active` IN ('1') {$with_stock_a_P} {$in_gallery_sql} {$inoffert} {$orderby} LIMIT {$limit} OFFSET {$offset}";
  $sql_products_total = "SELECT COUNT(P.`id`) as `total_products`
  FROM
    `{$GLOBALS['PACMEC']['DB']->getTableName('products')}` P
  WHERE
    P.`is_active` IN ('1')
    {$with_stock_a_P}
     {$in_gallery_sql}
     {$inoffert}
  ";
} else {
  $sql_products = "SELECT P.*
    FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_filters')}` PF
    INNER JOIN `{$GLOBALS['PACMEC']['DB']->getTableName('products')}` P
    ON P.`id`=PF.`product`
    	WHERE P.`is_active` IN ('1')
      {$with_stock_a_P}
       {$in_gallery_sql}
       {$inoffert}
      AND (";
  $sql_products_total = "SELECT COUNT(P.`id`) as `total_products`
    FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_filters')}` PF
    INNER JOIN `{$GLOBALS['PACMEC']['DB']->getTableName('products')}` P
    ON P.`id`=PF.`product`
    	WHERE P.`is_active` IN ('1')
      {$with_stock_a_P}
       {$in_gallery_sql}
       {$inoffert}
      AND (";
  $i = 0;
  foreach ($filter_text as $_txt) {
    if($i>0) $sql_products .= " $in_gallery_sql2 OR ";
    if($i>0) $sql_products_total .= " $in_gallery_sql2 OR ";
    $sql_products .= " PF.`text` LIKE '%".urldecode($_txt)."%' ";
    $sql_products_total .= " PF.`text` LIKE '%".urldecode($_txt)."%' ";
    $i++;
  }
  $sql_products_total .= ")";
  $sql_products .= ") {$orderby} LIMIT {$limit} OFFSET {$offset} ";
}
$_products_totals = $GLOBALS['PACMEC']['DB']->FetchObject($sql_products_total, []);
$products = [];
foreach ($GLOBALS['PACMEC']['DB']->FetchAllObject($sql_products, []) as $product) {
  $products[] = new \PACMEC\System\Product((object) ["id"=>$product->id]);
}
$total_result = $_products_totals->total_products;
$max_pages_float = (float) ($total_result/$limit);
$max_pages = (int) ($total_result/$limit);
if($max_pages<$max_pages_float) $max_pages += 1;
$_url_form = [];
if(isset($_url_form['page'])) $_url_form['page'] = $PACMEC['fullData']['page'];
if(isset($PACMEC['fullData']['filter_text'])) $PACMEC['fullData']['filter_text'] = $PACMEC['fullData']['filter_text'];
if(isset($PACMEC['fullData']['order_by']) && $PACMEC['fullData']['order_by'] !== 'default') $_url_form['order_by'] = $PACMEC['fullData']['order_by'];
$url_base_form = $PACMEC['path'].http_build_query(array_merge($_url_form, []));
$_url_pagination = $PACMEC['fullData'];
if(isset($_url_pagination['page'])) unset($_url_pagination['page']);
$url_pagination = $PACMEC['path'].http_build_query($_url_pagination);





function mt_destry_product_style1($_product, $class=[], $attrs=[])
{
 global $PACMEC;
 $url_product = $_product->link_view;
 $_img_box_a_img1 = \PHPStrap\Util\Html::tag('img', '', ['first-image'], ['src'=>$_product->thumb], true);
 $sec_img = (isset($_product->gallery[1])) ? $_product->gallery[1] : $_product->thumb;
 $_img_box_a_img2 = \PHPStrap\Util\Html::tag('img', '', ['second-image'], ['src'=>$sec_img], true);
 $_img_box_a = \PHPStrap\Util\Html::tag('a', $_img_box_a_img1.$_img_box_a_img2, ['image'], ["href"=>$url_product]);
 $btns = "";
 $btns .= \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['pe-7s-search'], []), ['action quickview'], ['data-bs-toggle'=>'modal', "data-bs-target"=>"#exampleModalCenter", "data-product"=>$_product->id]);
 $_box_actions = \PHPStrap\Util\Html::tag('div', $btns, ['actions'], []);
 $badges = "";
 if($_product->in_promo == true){
   $badges .= \PHPStrap\Util\Html::tag('span', \PHPStrap\Util\Html::tag('span', (100 - (int) (($_product->price_promo*100) / $_product->price_normal))."%", ['sale'], []), ['badges'], []);
 }
 if(!($_product->available>0)){
   $badges .= \PHPStrap\Util\Html::tag('span', \PHPStrap\Util\Html::tag('span', __a('product_not_available'), ['sale'], []), ['badges'], []);
 }
 $_img_box = \PHPStrap\Util\Html::tag('div', $_img_box_a.$_box_actions.$badges, ['thumb'], []);
 $_cont_box_subtitle = \PHPStrap\Util\Html::tag('h4', \PHPStrap\Util\Html::tag('a', implode(', ', $_product->common_names), [], ["href"=>$url_product]), ['sub-title'], []);
 $_cont_box_title = \PHPStrap\Util\Html::tag('h5', \PHPStrap\Util\Html::tag('a', $_product->name, [], ["href"=>$url_product]), ['title'], []);
 $ratings = "";
 if (infosite('comments_enabled')==true) {
   $ratings .= \PHPStrap\Util\Html::tag('span',
   \PHPStrap\Util\Html::tag('span',
   \PHPStrap\Util\Html::tag('span', '', ['star'], ["style"=>"width: {$_product->rating_porcen}%"])
   , ['rating-wrap'], [])
   . \PHPStrap\Util\Html::tag('span', "(".((int) $_product->rating_number).")", ['rating-num'], [])
   , ['ratings'], []);
 }

 $_prices = "";
 if($_product->in_promo == true){
   $_prices .= \PHPStrap\Util\Html::tag('span', formatMoney($_product->price_promo), ['new'], []);
   $_prices .= \PHPStrap\Util\Html::tag('span', formatMoney($_product->price_normal), ['old'], []);
 } else {
   $_prices .= \PHPStrap\Util\Html::tag('span', formatMoney($_product->price_normal), ['new'], []);
 }
 $prices = \PHPStrap\Util\Html::tag('span', $_prices, ['price'], []);

 $_cont_box = \PHPStrap\Util\Html::tag('div', $_cont_box_subtitle.$_cont_box_title.$ratings.$prices
 /*. (($_product->available>0)
 ? '<button class="btn btn-sm btn-outline-dark btn-hover-primary">'.__a('add_to_cart').'</button>'
 : '<button class="btn btn-sm btn-outline-dark btn-hover-primary disabled" disabled>'.__a('product_not_available').'</button>'
 )
 */
 , ['content'], []);
 return \PHPStrap\Util\Html::tag('div', $_img_box.$_cont_box, $class, $attrs);
}
?>
<div class="section">
  <div class="breadcrumb-area bg-light">
    <div class="container-fluid">
      <div class="breadcrumb-content text-center">
        <h1 class="title"><?= __a('shop_title'); ?></h1>
        <ul>
          <li><a href="<?= infosite('siteurl').infosite('homeurl'); ?>"><?= __a('home'); ?> </a></li>
          <li class="active"> <?= __a('shop'); ?></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="section section-margin">
  <div class="container">
    <div class="row flex-row-reverse">
      <div class="col-lg-9 col-12 col-custom">
        <div class="shop_toolbar_wrapper flex-column flex-md-row mb-10">
          <div class="shop-top-bar-left mb-md-0 mb-2">
            <div class="shop-top-show">
              <span><?= __a('showing_info_pagination'); ?>: <?= ($page); ?>–<?= ($max_pages); ?> of <?= $total_result; ?> <?= __a('products_results'); ?></span>
            </div>
          </div>
          <div class="shop-top-bar-right">
            <!--//
            <div class="shop-short-by mr-4">
              <select class="nice-select" aria-label=".form-select-sm example">
                <option selected>Show 24</option>
                <option value="1">Show 24</option>
                <option value="2">Show 12</option>
                <option value="3">Show 15</option>
                <option value="3">Show 30</option>
              </select>
            </div>
            -->

            <div class="shop-short-by mr-4">
                <select class="nice-select-product" aria-label=".form-select-sm example">
                  <!-- // <option selected>Short by Default</option> -->
                  <?php foreach ($options_order_by as $key => $value): ?>
                    <option value="<?= $key; ?>" <?= ($order_by==$key) ? 'selected':''; ?>><?= __a($value); ?></option>
                  <?php endforeach; ?>
                </select>
            </div>

            <div class="shop_toolbar_btn">
                <button data-role="grid_3" type="button" class="active btn-grid-4" title="Grid"><i class="fa fa-th"></i></button>
                <button data-role="grid_list" type="button" class="btn-list" title="List"><i class="fa fa-th-list"></i></button>
            </div>
          </div>
        </div>
        <div class="row shop_wrapper grid_3">
          <?php foreach ($products as $product): ?>
            <div class="col-lg-4 col-md-4 col-sm-6 product" data-aos="fade-up" data-aos-delay="200">
              <?= mt_destry_product_style1($product, ["product-inner"], []); ?>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="shop_toolbar_wrapper mt-10">
          <!--
          <div class="shop-top-bar-left">
              <div class="shop-short-by mr-4">
                  <select class="nice-select rounded-0" aria-label=".form-select-sm example">
                      <option selected>Show 12 Per Page</option>
                      <option value="1">Show 12 Per Page</option>
                      <option value="2">Show 24 Per Page</option>
                      <option value="3">Show 15 Per Page</option>
                      <option value="3">Show 30 Per Page</option>
                  </select>
              </div>
          </div>
          -->
          <div class="shop-top-bar-right">
            <nav>
              <ul class="pagination">
                <li class="page-item">
                  <a class="page-link" href="<?= $PACMEC['path'].'?'.http_build_query($_url_pagination); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>

                <?php if (($page-4)>0): ?>
                  <li class="page-item"><a class="page-link" href="<?= $PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ['page'=>($page-4)])); ?>"><?= ($page-4); ?></a></li>
                <?php endif; ?>

                <?php if (($page-3)>0): ?>
                  <li class="page-item"><a class="page-link" href="<?= $PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ['page'=>($page-3)])); ?>"><?= ($page-3); ?></a></li>
                <?php endif; ?>

                <?php if (($page-2)>0): ?>
                  <li class="page-item"><a class="page-link" href="<?= $PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ['page'=>($page-2)])); ?>"><?= ($page-2); ?></a></li>
                <?php endif; ?>

                <?php if (($page-1)>0): ?>
                  <li class="page-item"><a class="page-link" href="<?= $PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ['page'=>($page-1)])); ?>"><?= ($page-1); ?></a></li>
                <?php endif; ?>

                <li class="page-item"><a class="page-link active" href="#"><?= $page; ?></a></li>
                <?php if ($max_pages>=($page+1)): ?>
                  <li class="page-item"><a class="page-link" href="<?= $PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ['page'=>($page+1)])); ?>"><?= ($page+1); ?></a></li>
                <?php endif; ?>

                <?php if ($max_pages>=($page+2)): ?>
                  <li class="page-item"><a class="page-link" href="<?= $PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ['page'=>($page+2)])); ?>"><?= ($page+2); ?></a></li>
                <?php endif; ?>

                <?php if ($max_pages>=($page+3)): ?>
                  <li class="page-item"><a class="page-link" href="<?= $PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ['page'=>($page+3)])); ?>"><?= ($page+3); ?></a></li>
                <?php endif; ?>

                <?php if ($max_pages>=($page+4)): ?>
                  <li class="page-item"><a class="page-link" href="<?= $PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ['page'=>($page+4)])); ?>"><?= ($page+4); ?></a></li>
                <?php endif; ?>

                <li class="page-item">
                  <a class="page-link" href="<?= $PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ['page'=>($max_pages)])); ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>

              </ul>
            </nav>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-12 col-custom">
        <aside class="sidebar_widget mt-10 mt-lg-0">
          <div class="widget_inner" data-aos="fade-up" data-aos-delay="200">
            <!--//
            <div class="widget-list mb-10">
              <h3 class="widget-title mb-4">Search</h3>
              <div class="search-box">
                <input type="text" class="form-control" placeholder="Search Our Store" aria-label="Search Our Store">
                <button class="btn btn-dark btn-hover-primary" type="button">
                  <i class="fa fa-search"></i>
                </button>
              </div>
            </div>
            -->

            <form action="<?= $url_base_form; ?>" method="GET">
              <div class="widget-list mb-10">
                <h3 class="widget-title mb-5"><?= __a('filter_to'); ?></h3>
                <div id="slider-range-prices"></div>
                <input type="hidden" name="price_min" id="amount-min" />
                <input type="hidden" name="price_max" id="amount-max" />
                <button class="slider-range-submit" type="submit"><?= __a('filter_to'); ?></button>
                <input class="slider-range-amount" type="text" readonly="" id="amount-display" />
              </div>

              <?php foreach ($features as $feature): ?>
                <div class="widget-list mb-10">
                    <h3 class="widget-title">
                      <?= $feature->name; ?>
                    </h3>
                    <div class="sidebar-body">
                      <ul class="checkbox-container categories-list">
                        <?php foreach ($feature->options as $i => $option): ?>
                          <li>
                            <div class="custom-control custom-checkbox">
                              <input <?php if(in_array($option->value, $filter_text)) echo " checked=\"\" "; ?> name="filter_text[]" value="<?= $option->value; ?>" type="checkbox" class="custom-control-input" id="<?= "filter-{$feature->name}-text-{$i}"?>">
                              <label class="custom-control-label" for="<?= "filter-{$feature->name}-text-{$i}"?>"><?= $option->text; ?> </label>
                            </div>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                      <br/>
                      <button type="submit" class="btn btn-outline-dark btn-sm">
                        Filtrar
                      </button>
                    </div>
                </div>
              <?php endforeach; ?>
            </form>
            <script>
              window.addEventListener('load', function(){
                $( "#slider-range-prices" ).slider({
                  range: true,
                  min: <?= ($prices_range->price_min); ?>,
                  max: <?= ($prices_range->price_max); ?>,
                  values: [ <?= $price_min; ?>, <?= $price_max; ?> ],
                  slide: function( event, ui ) {
                    $( "#amount-min" ).val( ui.values[ 0 ] );
                    $( "#amount-max" ).val( ui.values[ 1 ] );
                    $( "#amount-display" ).val( "$" + $( "#slider-range-prices" ).slider( "values", 0 ) + " - " + "$" + $( "#slider-range-prices" ).slider( "values", 1 ) );
                  }
                });
                $( "#amount-min" ).val( <?= $price_min; ?> );
                $( "#amount-max" ).val( <?= $price_max; ?> );
                $( "#amount-display" ).val( "$<?= $price_min; ?>" + " - " + "$<?= $price_max; ?>" );

                setTimeout(function () {
                  $('.nice-select-product').niceSelect()
                  .on('change', function() {
                    var sortBy = $(".nice-select-product").val();
                    var url = '<?= $PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ['page'=>($page)])); ?>';
                    if (url.indexOf('?') > -1){ url += '&'; } else { url += '?'; }
                    url += 'order_by=' + sortBy;
                    window.location.href = url;
                  });
                }, 500);
              });
            </script>

            <?php
            /*
            <!--
            <div class="widget-list mb-10">
                <h3 class="widget-title">Categories</h3>
                <div class="sidebar-body">
                    <ul class="sidebar-list">
                        <li><a href="#">All Product</a></li>
                        <li><a href="#">Best Seller (5)</a></li>
                        <li><a href="#">Featured (4)</a></li>
                        <li><a href="#">New Products (6)</a></li>
                    </ul>
                </div>
            </div>

            <div class="widget-list mb-10">
                <h3 class="widget-title mb-4"><?= __a('others_filters'); ?></h3>
                <div class="sidebar-body">
                    <ul class="tags mb-n2">
                      <?php foreach ($more_filters as $more_filter): ?>
                        <?php if ($more_filter->input_type=='text'): ?>
                          <li><a href="#"><?= "{$more_filter->text} ({$more_filter->total_count})"; ?></a></li>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            -->
            */
            ?>

            <div class="widget-list mb-10">
              <h3 class="widget-title mb-4"><?= __a('new_arrivals'); ?></h3>
              <div class="sidebar-body product-list-wrapper mb-n6">
                <?php foreach (\PACMEC\System\Products::allNews($limit_news) as $new_prod): ?>
                  <div class="single-product-list product-hover mb-6">
                    <div class="thumb">
                      <a href="<?= $new_prod->link_view; ?>" class="image">
                        <img class="first-image" src="<?= $new_prod->thumb; ?>" alt="Product" />
                        <img class="second-image" src="<?= $new_prod->thumb; ?>" alt="Product" />
                      </a>
                    </div>
                    <div class="content">
                      <h5 class="title"><a href="<?= $new_prod->link_view; ?>"><?= $new_prod->name; ?></a></h5>
                      <span class="price">
                        <?php if ($new_prod->in_promo == true): ?>
                          <span class="new"><?= $new_prod->price_promo; ?></span>
                          <span class="old"><?= $new_prod->price; ?></span>
                        <?php else: ?>
                          <span class="new"><?= $new_prod->price; ?></span>
                        <?php endif; ?>
                      </span>
                      <span class="ratings">
                        <span class="rating-wrap">
                    	     <span class="star" style="width: <?= $new_prod->rating_porcen; ?>%"></span>
                        </span>
                        <span class="rating-num">(<?= $new_prod->rating_number; ?>)</span>
                      </span>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <?php
            /*
            <div class="widget-list mb-10">
              <h3 class="widget-title mb-4"><?= $menu_primary->name; ?></h3>
              <nav>
                <ul class="category-menu mb-n3">
                  <?php
                  if($menu_primary !== false){
                    foreach ($menu_primary->items as $key => $item) {
                      echo pacmec_menu_item_to_li($item, ['menu-item-has-children pb-4'], [], true, true, true, ['dropdown'], [], []);
                    }
                  }
                  ?>
                </ul>
              </nav>
            </div>
            */
            ?>
          </div>
        </aside>
      </div>
    </div>
  </div>
</div>
<!-- Shop Section End -->
