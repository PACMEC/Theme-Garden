<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @license    license.txt
 * @version    1.0.1
 */

/*
function pacmec_crm_services_in_categories($categories=['0'])
{
 $r = [];
 try {
   $cats = [];
   foreach ($categories as $a) {
     $cats[] = "'{$a}'";
   }
   $cats_sql = implode(',', $cats);
   $sql = "SELECT `service` FROM `{$GLOBALS['PACMEC']['DB']->getPrefix()}services_categories` WHERE `category` IN ({$cats_sql}) ";
   $services_ids = $GLOBALS['PACMEC']['DB']->FetchAllObject($sql, []);
   $result = [];
   $result = \PACMEC\CRM\Services::byIds($services_ids);

   return $result;
 } catch (\Exception $e) {
   return $r;
 }
 return $r;
}


function pacmec_section_services_for_category($atts, $content="")
{
  $merge = $GLOBALS['PACMEC']["fullData"];
  $data = array_merge(['args'=>$merge], $merge);
  $categories_ids_tmp = (isset($data['categories']) && !empty($data['categories'])) ? $data['categories'] : false;
  $args = \shortcode_atts([
    "class"       => "section gray-bg",
    "categories"  => "0",
    "limit"       => 3,
    "title"       => false,
    "content"     => false,
    "footer"      => false
  ], $atts);
  if($categories_ids_tmp!==false){
    $args['categories'] = $categories_ids_tmp;
    $args['title'] = ('home_services_in_categories_title');
    $args['content'] = ('home_services_in_categories_content');
  }

  $services = pacmec_crm_services_in_categories(explode(',', $args['categories']));
  $services_h = "";
  $permanent_link = infosite('services_page_permanent_link');
  foreach ($services as $service) {
    $service->slug = str_replace([
      '%slug%',
      '%autot_services%',
    ], [
      $service->slug,
      strtolower(_autoT('services')),
    ], $permanent_link);
    $icon = \PHPStrap\Util\Html::tag('i', '', [$service->icon], []);
    $icon_box = \PHPStrap\Util\Html::tag('div', $icon, ['icon-shape-circle'], []);
    $description = \PHPStrap\Util\Html::tag('p', $service->description_short, [], []);
    $title = \PHPStrap\Util\Html::tag('h5', $service->name, [], []);
    $content = \PHPStrap\Util\Html::tag('div', $icon_box.$title.$description, ['content-box left small'], ['onclick'=>"javascript:location.replace('{$service->slug}')", "style"=>"cursor:pointer;"]);
    $services_h .= \PHPStrap\Util\Html::tag('div', $content, ['col-md-6'], []);
  }

  $footer = ($args['footer'] !== false) ? \PHPStrap\Util\Html::tag('div', \PHPStrap\Util\Html::tag('p', _autoT($args['footer']), ['gap no-margin-bottom'], ["data-gap-top"=>"30"]), ['col-md-12'], []) : "";
  $content = ($args['content'] !== false) ? \PHPStrap\Util\Html::tag('p', _autoT($args['content']), ['lead'], []) : "";
  $title   = ($args['title'] !== false) ? \PHPStrap\Util\Html::tag('h3', _autoT($args['title']), [], []) : "";
  $header_title = \PHPStrap\Util\Html::tag('div', $title.$content, ['heading-title gap'], ["data-gap-bottom"=>"60"]);
  $header = \PHPStrap\Util\Html::tag('div', $header_title, ['col-md-12'], []);
  $row = \PHPStrap\Util\Html::tag('div', $header.$services_h.$footer, ['row'], []);
  $container = \PHPStrap\Util\Html::tag('div', $row, ['container'], []);
  $section = \PHPStrap\Util\Html::tag('section', $container, [$args['class']], []);
  return ($section)."\n";
}
add_shortcode('section-services-in-categories', 'pacmec_section_services_for_category');

function pacmec_section_services_table_simple($atts, $content="")
{
  $args = \shortcode_atts([
    "class"       => "section",
    "limit"       => null,
    "ids"         => false,
    "content"     => false,
    "title"       => false
  ], $atts);
  if($args['ids']!==false){
    $services = \PACMEC\CRM\Services::byIds(explode(',', $args['ids']), $args['limit']);
  } else {
    $services = \PACMEC\CRM\Services::allLoad($args['limit']);
  }
  $table = new \PHPStrap\Table('',0,0,['table table-striped me-table green']);
  $table->addHeaderRow([
    _autoT("ref"),
    _autoT("service"),
    // _autoT("description"),
    _autoT("price"),
    _autoT("time_execution_estimated"),
    "",
  ]);

  foreach ($services as $service) {
    $table->addRow([
      $service->id,
      $service->name,
      // $service->description_short,
      \formatMoney($service->price),
      "{$service->execution_time} "._autoT("period_d_{$service->execution_cycle}"),
      \PHPStrap\Util\Html::tag('a', _autoT('order_now'), ['btn btn-sm btn-default'])
    ]);
  }

  $table_responsive = \PHPStrap\Util\Html::tag('div', $table, ['table-responsive'], []);
  $content = ($args['content'] !== false) ? \PHPStrap\Util\Html::tag('p', _autoT($args['content']), ['lead'], []) : "";
  $title   = ($args['title'] !== false) ? \PHPStrap\Util\Html::tag('h3', _autoT($args['title']), ['no-margin'], []) : "";
  $header = \PHPStrap\Util\Html::tag('div', $title.$content, ['heading-title no-border text-center'], []);
  $col = \PHPStrap\Util\Html::tag('div', $header.$table_responsive, ['col-md-12'], []);
  $row = \PHPStrap\Util\Html::tag('div', $col, ['row'], []);
  $container = \PHPStrap\Util\Html::tag('div', $row, ['container'], []);
  $section = \PHPStrap\Util\Html::tag('section', $container, [$args['class']], []);

  return ($section)."\n";
}
add_shortcode('section-services-table-simple', 'pacmec_section_services_table_simple');

function pacmec_section_page_service($atts, $content="")
{
  $args = \shortcode_atts([
    "class"       => "section",
    "id"         => false,
    "title"       => false
  ], $atts);
  if($args['id']==false) return "";
  $args['title'] = ($args['title']!==false) ? $args['title'] : _autoT(pageinfo('title'));
  $service = \PACMEC\CRM\Services::byId($args['id']);
  $menu = \pacmec_load_menu('services');
  $more = "";
  if($menu !== false){
    foreach ($menu->items as $key => $item1) {
      $r_html = "";
      foreach ($item1->childs as $item2) {
        $is_active = ($GLOBALS['PACMEC']['req_url'] == $item2->tag_href) ? ' active' : '';
        $r_html .= \PHPStrap\Util\Html::tag("a", _autoT($item2->title), ["list-group-item{$is_active}"], ["href"=>$item2->tag_href]);
      }
      $sidebar_list = \PHPStrap\Util\Html::tag('div', $r_html, ['list-group sidebar-list'], []);
      $widget_title = \PHPStrap\Util\Html::tag('h5',_autoT($item1->title), [], []);
      $widget = \PHPStrap\Util\Html::tag('div', $widget_title, ['heading-title widget-title'], []);
      $more .= \PHPStrap\Util\Html::tag('div', $widget.$sidebar_list, ['widget'], []);
    }
  }

  $separator = \PHPStrap\Util\Html::tag('hr', '', ['separator'], [], true);
  $widget = \PHPStrap\Util\Html::tag('div', $more, ['widget'], []);
  $btns  = "";
  $btns .= \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-file-pdf-o']) . _autoT('download_pricelist'), ['btn btn-default btn-block'], []);
  $btns .= \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-file-pdf-o']) . _autoT('download_brochure'), ['btn btn-default btn-block'], []);
  $title_2 = \PHPStrap\Util\Html::tag('div', \PHPStrap\Util\Html::tag('h5', _autoT('download_brochures')), ['heading-title widget-title'], []);
  $widget2 = \PHPStrap\Util\Html::tag('div', $title_2.$btns, ['widget'], []);
  $sidebar = \PHPStrap\Util\Html::tag('div', $widget.$widget2, ['sidebar'], []);
  $col_1 = \PHPStrap\Util\Html::tag('div', $sidebar, ['col-md-12 col-lg-3'], []);

  // ITEM
  $characteristics = "";
  foreach ($service->characteristics as $characteristic) {
    $r_left_c_p = \PHPStrap\Util\Html::tag('p', $characteristic->description_short);
    $r_left_c_title = \PHPStrap\Util\Html::tag('h5', $characteristic->name);
    $r_left_c_icon = \PHPStrap\Util\Html::tag('i', '', [$characteristic->icon]);
    $r_left_c_icon_s = \PHPStrap\Util\Html::tag('div', $r_left_c_icon, ['icon-shape-disable']);
    $r_left_c_box = \PHPStrap\Util\Html::tag('div', $r_left_c_icon_s.$r_left_c_title.$r_left_c_p, ['content-box left small']);
    $characteristics .= $r_left_c_box;
  }
  // END ITEM

  $r_left_icon = \PHPStrap\Util\Html::tag('i', '', [$service->icon]);
  $r_left_p = \PHPStrap\Util\Html::tag('p', $service->description_short);
  $r_left_title = \PHPStrap\Util\Html::tag('h3', $r_left_icon.$service->name);
  $r_left = \PHPStrap\Util\Html::tag('div',
    $r_left_title
    . $r_left_p
    // . $separator
    . $characteristics
  , ['col-md-6'], []);



  $r_right_img = \PHPStrap\Util\Html::tag('img', '', ['img-radius img-shadow'], ["src"=>$service->thumb]);
  $r_right = \PHPStrap\Util\Html::tag('div', $r_right_img, ['col-md-6'], []);

  $promo_a = \PHPStrap\Util\Html::tag('a', _autoT(infosite('services_page_promo_btn')), ['btn btn-outline white btn-radius'], ['href'=>infosite('services_page_promo_page_link').'?service_id='.$service->id]);
  $promo_action = \PHPStrap\Util\Html::tag('div', $promo_a, ['promo-action']);
  $promo_c_p = \PHPStrap\Util\Html::tag('p', _autoT('services_page_promo_text'));
  $promo_c_h4 = \PHPStrap\Util\Html::tag('h4', _autoT('services_page_promo_title'));
  $promo_c = \PHPStrap\Util\Html::tag('div', $promo_c_h4.$promo_c_p, ['promo-content']);
  $promo = \PHPStrap\Util\Html::tag('div', $promo_c.$promo_action, ['promo-box green full-width content-contrast no-margin-bottom'], []);

  $row2 = \PHPStrap\Util\Html::tag('div',
    $r_left
    .$r_right
    .$separator
    .\PHPStrap\Util\Html::tag('div', \PHPStrap\Util\Html::tag('p', $separator.$service->description))

    .$promo
  , ['row'], []);
  $col_2 = \PHPStrap\Util\Html::tag('div', $row2, ['col-md-12 col-lg-9'], []);
  $row = \PHPStrap\Util\Html::tag('div', $col_1.$col_2, ['row'], []);
  $container = \PHPStrap\Util\Html::tag('div', $row, ['container'], []);
  $section = \PHPStrap\Util\Html::tag('section', $container, [$args['class']], []);
  return ($section)."\n";
}
add_shortcode('section-page-service', 'pacmec_section_page_service');

function pacmec_section_contact_form($atts, $content="")
{
  $args = \shortcode_atts([
    "rowl"       => "col-lg-4 col-md-12",
    "rowr"       => "col-lg-8 col-md-12",
    "content"    => pageinfo('description'),
    "title"      => pageinfo('title')
  ], $atts);
  $args['title'] = _autoT($args['title']);
  $args['content'] = _autoT($args['content']);
  get_part('components/contact-form', PACMEC_CRM_COMPONENTS_PATH, $args);
}
add_shortcode('section-contact-form', 'pacmec_section_contact_form');

function pacmec_section_contact_from_services_form($atts, $content="")
{
  $merge = $GLOBALS['PACMEC']["fullData"];
  $data = array_merge(['args'=>$merge], $merge);
  $service_id_tmp = (isset($data['service_id']) && !empty($data['service_id'])) ? $data['service_id'] : false;
  $args = \shortcode_atts([
    "rowl"       => "col-lg-4 col-md-12",
    "rowr"       => "col-lg-8 col-md-12",
    "content"    => pageinfo('description'),
    "title"      => pageinfo('title'),
    "service_id" => $service_id_tmp
  ], $atts);
  $args['title'] = _autoT($args['title']);
  $args['content'] = _autoT($args['content']);

  if($args['service_id']!==false){
    $args['service'] = \PACMEC\CRM\Services::byId($args['service_id']);
    get_part('components/contact-from-services-form', PACMEC_CRM_COMPONENTS_PATH, $args);
  } else {
    $args['services'] = \PACMEC\CRM\Services::allLoad();
    get_part('components/contact-from-services-form-select', PACMEC_CRM_COMPONENTS_PATH, $args);
  }

}
add_shortcode('section-contact-from-services-form', 'pacmec_section_contact_from_services_form');
*/
