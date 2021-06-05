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



/**
 * FUNCTIONS
 **/

 function pacmec_repair_string_shortcode($string, $comuns_autoT=[]) : array
 {
   $r = [];
   $a = explode(';', $string);
   foreach ($a as $b => $c) {
     $item = [];
     $d = explode('|', $c);
     foreach ($d as $e => $f) {
       $column = explode(':', $f);
       if(isset($column[1])){
         if($column[0] == 'btns'){
           // $item[$column[0]] = $column[1];
           $btns = [];
           foreach (explode(',', $column[1] ) as $z) {
             $btn_b = [];
             foreach (explode('&', $z) as $y) {
               $k_v = explode('Þ', $y);
               if(isset($k_v[1])){
                 if(in_array($k_v[0], $comuns_autoT)){
                   $btn_b[$k_v[0]] = _autoT(urldecode($k_v[1]));
                 } else {
                   $btn_b[$k_v[0]] = urldecode($k_v[1]);
                 }
               }
             }
             $btns[] = $btn_b;
           }
           $item[$column[0]] = $btns;
         } else {
           if(in_array($column[0], $comuns_autoT)){
             $item[$column[0]] = _autoT(urldecode($column[1]));
           } else {
             $item[$column[0]] = urldecode($column[1]);
           }
         }
       }
     }
     $r[] = $item;
   }
   return $r;
 }

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






function pacmec_slideshow_hero($atts, $content="")
{
  $args = \shortcode_atts([
    "id"      => 'hero',
    "class"   => "slideshow-container",
    "gallery" => false
  ], $atts);
  $gallery = [];
  if($args['gallery'] !== false){
    $gallery = pacmec_repair_string_shortcode($args['gallery'], ["title", "subtitle", "text"]);
  }
  $lis = '';
  $br = \PHPStrap\Util\Html::tag('br', "", [], [], true);
  $separator = "\n".\PHPStrap\Util\Html::tag('hr', '', ["hero-separator flex-animate"], ["data-animate"=>"animate__fadeInDown","data-animate-delay"=>"500"], true);
  foreach ($gallery as $item) {
    if($item['title'] !== false) $item['title'] = (($item['title']));
    if($item['subtitle'] !== false) $item['subtitle'] = (($item['subtitle']));
    $bg = ($item['bg'] !== false) ? "\n".\PHPStrap\Util\Html::tag('img', "", [], ["src"=>(urldecode($item['bg'])),"data-position-y"=>"top","alt"=>"Slideshow Image"], true) : "";
    $title = ($item['title'] !== false) ? "\n".\PHPStrap\Util\Html::tag('p', $item['title'], ["hero-caption no-margin flex-animate"], ["data-animate"=>"animate__fadeInDown"]) : "";
    $subtitle = ($item['subtitle'] !== false) ? "\n".\PHPStrap\Util\Html::tag('h1', $item['subtitle'], ["hero-heading flex-animate"], ["data-animate"=>"animate__fadeInDown","data-animate-delay"=>"650"]) : "";
    $btns = "";
    foreach ($item['btns'] as $btn) {
      $btn_a = \shortcode_atts([
        "href"     => false,
        "type"     => false,
        "text"     => false,
      ], $btn);
      if($btn_a['text'] !== false && $btn_a['href'] !== false){
        $btns .= "\n".\PHPStrap\Util\Html::tag('a', ($btn['text']), [($btn_a['type']!==false?"btn btn-{$btn_a['type']} flex-animate":"btn btn-default flex-animate")], ["href"=>$btn_a['href'],"data-animate"=>"animate__fadeInLeft","data-animate-delay"=>"1000"]);
      }
    }
    $box = "\n".\PHPStrap\Util\Html::tag('div', $subtitle.$separator.$title.$br.$btns, ['col-md-12 text-center'], []);
    $row = "\n".\PHPStrap\Util\Html::tag('div', $box, ['row'], []);
    $container = "\n".\PHPStrap\Util\Html::tag('div', $row, ['container'], []);
    $flex_content = "\n".\PHPStrap\Util\Html::tag('div', $container, ['flex-content'], []);
    $flex_content_wrapper = "\n".\PHPStrap\Util\Html::tag('div', $flex_content, ['flex-content-wrapper'], []);
    $lis .= "\n".\PHPStrap\Util\Html::tag('li', $bg.$flex_content_wrapper, [], []);
  }

  $ul = \PHPStrap\Util\Html::tag('ul', $lis, ['slides'], []);
  $flex = \PHPStrap\Util\Html::tag('div', $ul, ['slideshow'], ['data-flex-fullscreen'=>'true']);
  return $container = \PHPStrap\Util\Html::tag('div', $flex, explode(' ', $args['class']), ['id'=>$args['id']]);
}
add_shortcode('slideshow-hero-section', 'pacmec_slideshow_hero');

function pacmec_section_categories($atts, $content="")
{
  $args = \shortcode_atts([
    // "id"      => ,
    "class"   => "section no-padding-top section-float",
    "parent"  => "categories",
    "limit"  => 3,
    "columns"  => 3,
    "parent"  => 0
  ], $atts);
  $categories = \PACMEC\Categories::allLoad(true, 0);
  switch ($args['columns']) {
    case 1:
      $row_column = 'col-md-12';
      break;
    case 2:
      $row_column = 'col-md-6';
      break;
    case 3:
      $row_column = 'col-md-4';
      break;
    case 4:
      $row_column = 'col-md-3';
      break;
    case 6:
      $row_column = 'col-md-2';
      break;
    case 12:
      $row_column = 'col-md-1';
      break;
    default:
      $row_column = 'col-md-4';
      break;
  }
  $columns = "";
  $i = 0;
  foreach ($categories as $category) {
    $i++;
    if($i <= $args['limit']){
      $cats = [];
      foreach ($category->childs as $cat) {
        $cats[] = $cat->id;
      }
      $img_src = $category->thumbnail !== null ? $category->thumbnail : infosite('default_picture');
      $img = \PHPStrap\Util\Html::tag('img', "", ["img-radius img-shadow"], ["src"=>$img_src, "style"=>"padding:1.2em;background-color:white;border:1px solid #CCC;"], true);
      $p = \PHPStrap\Util\Html::tag('p', _autoT("categories_{$category->id}_desc"), ['']);
      $a = \PHPStrap\Util\Html::tag('a', _autoT("categories_{$category->id}"), [], ['href'=>strtolower(_autoT('services'))."?categories=".implode(',', $cats)]);
      $h4 = \PHPStrap\Util\Html::tag('h4', $a, ['title']);
      $process_box = \PHPStrap\Util\Html::tag('div', $img.$h4.$p, ['process-box']);
      $columns .= \PHPStrap\Util\Html::tag('div', $process_box, [$row_column]);
    }
  }
  $row = \PHPStrap\Util\Html::tag('div', $columns, ['row']);
  $container = \PHPStrap\Util\Html::tag('div', $row, ['container']);
  return $section = \PHPStrap\Util\Html::tag('div', $container, [$args['class']]);
}
add_shortcode('section-categories', 'pacmec_section_categories');

function pacmec_section_faqs($atts, $content="")
{
  $args = \shortcode_atts([
    // "id"      => ,
    "title"   => false,
    "class"   => "section gray-bg border-section",
    "limit"   => 4,
    "columns" => 2,
  ], $atts);
  $faqs = \PACMEC\CMS\Faqs::allLoad($args['limit']);
  switch ($args['columns']) {
    case 1:
      $row_column = 'col-md-12';
      break;
    case 2:
      $row_column = 'col-md-6';
      break;
    case 3:
      $row_column = 'col-md-4';
      break;
    case 4:
      $row_column = 'col-md-3';
      break;
    case 6:
      $row_column = 'col-md-2';
      break;
    case 12:
      $row_column = 'col-md-1';
      break;
    default:
      $row_column = 'col-md-4';
      break;
  }
  $columns = "";
  $i = 0;
  foreach ($faqs as $faq) {
    $i++;
    if($i > $args['limit']) break;
    $iicon = \PHPStrap\Util\Html::tag('i', "", ['fa fa-check-circle mr-1']);
    $strong = \PHPStrap\Util\Html::tag('strong', $iicon.$faq->question, []);
    $p = \PHPStrap\Util\Html::tag('p', $faq->question_r, ['']);
    $columns .= \PHPStrap\Util\Html::tag('div', $strong.$p, [$row_column]);
  }
  $title   = ($args['title'] !== false) ? \PHPStrap\Util\Html::tag('h3', _autoT($args['title']), [], []) : "";
  $header_box = \PHPStrap\Util\Html::tag('div', $title, ['col-md-12 text-center gap'], ["data-gap-bottom"=>"40"]);
  $row = \PHPStrap\Util\Html::tag('div', $header_box . $columns, ['row']);
  $container = \PHPStrap\Util\Html::tag('div', $row, ['container']);
  return $section = \PHPStrap\Util\Html::tag('div', $container, [$args['class']]);
}
add_shortcode('section-faqs', 'pacmec_section_faqs');

function pacmec_section_single_text($atts, $content="")
{
  $args = \shortcode_atts([
    "title"   => false,
    "class"   => "section gray-bg border-section",
    "rleft-class"   => "col-lg-6 col-md-12",
    "rright-class"   => "col-lg-6 col-md-12",
    "content"   => false,
    "picture"   => false,
  ], $atts);
  $args['content'] = $args['content']==false && !empty($content) ? $content : ($args['content']==false) ? $args['content'] : _autoT($args['content']).do_shortcode($content);
  $args['rright-class'] = $args['picture']==false ? "col-lg-12 col-md-12" : $args['rright-class'];

  $title = $args['title']==false?"":\PHPStrap\Util\Html::tag('div', \PHPStrap\Util\Html::tag('div', \PHPStrap\Util\Html::tag('h3', _autoT($args['title']), [], []), ['heading-title'], []), ['col-md-12'], []);
  $img = $args['picture']==false?false:\PHPStrap\Util\Html::tag('img', '', ['img-radius img-border img-shadow img-margin no-margin-lg'], ["src"=>urldecode($args['picture'])]);

  $col_l = $img==false?"":\PHPStrap\Util\Html::tag('div', $img, [$args['rleft-class']], []);
  $col_r = $img==false?"":\PHPStrap\Util\Html::tag('div', $args['content'], [$args['rright-class']], []);


  $row = \PHPStrap\Util\Html::tag('div', $title.$col_l.$col_r, ['row'], []);
  $container = \PHPStrap\Util\Html::tag('div', $row, ['container'], []);
  return \PHPStrap\Util\Html::tag('div', $container, ['section'], []);
}
add_shortcode('section-single-text', 'pacmec_section_single_text');

function pacmec_section_cta_buttom_c($atts, $content="")
{
  $args = \shortcode_atts([
    "class"   => "section",
    "items"   => false,
    "rows_class"   => "col-lg-3 col-md-6",
  ], $atts);
  $items = [];
  if($args['items'] !== false){
    $items = pacmec_repair_string_shortcode($args['items'], ["title", "text"]);
  }
  $items_html = "";
  foreach ($items as $item) {
    $icon = !isset($item['icon'])?"":\PHPStrap\Util\Html::tag('i', '', [$item['icon']], []);
    $number = \PHPStrap\Util\Html::tag('span', (!isset($item['number'])?"":$item['number']), ['count-me number'], ["data-to"=>(!isset($item['number'])?"":$item['number']), "data-speed"=>"2500"]);
    $title = !isset($item['title'])?"":\PHPStrap\Util\Html::tag('h6', $item['title'], ['counter-title'], []);
    $counter = \PHPStrap\Util\Html::tag('div', $number.$title, ['counter-content'], []);
    $couter_box = \PHPStrap\Util\Html::tag('div', $icon.$counter, ['counter-box'], []);
    $items_html .= \PHPStrap\Util\Html::tag('div', $couter_box, [$args['rows_class']], []);
  }
  $row = \PHPStrap\Util\Html::tag('div', $items_html, ['row'], []);
  $container = \PHPStrap\Util\Html::tag('div', $row, ['container'], []);
  return \PHPStrap\Util\Html::tag('section', $container, [$args['class']], []);
}
add_shortcode('section-single-cta-buttom-container', 'pacmec_section_cta_buttom_c');

function pacmec_section_single_icons_text_full($atts, $content="")
{
  $args = \shortcode_atts([
    "class"   => "section",
    "title"   => false,
    "text"   => false,
    "icons"   => false,
    "rows_class"   => "col-lg-3 col-md-6",
  ], $atts);
  $icons = [];
  if($args['icons'] !== false){
    $icons = pacmec_repair_string_shortcode($args['icons'], ["title", "subtitle", "text"]);
  }
  $items_html = "";
  foreach ($icons as $item) {
    $content_box_left_subtitle = !isset($item['subtitle'])?"":\PHPStrap\Util\Html::tag('p', $item['subtitle'], [], []);
    $content_box_left_title = !isset($item['title'])?"":\PHPStrap\Util\Html::tag('h5', $item['title'], [], []);
    $content_box_left_icon = !isset($item['icon'])?"":\PHPStrap\Util\Html::tag('i', '', [$item['icon']], []);
    $content_box_left_i_s = \PHPStrap\Util\Html::tag('div', $content_box_left_icon, ['icon-shape-disable'], ['style'=>'color:#87ba45;']);
    $content_box_left = \PHPStrap\Util\Html::tag('div', $content_box_left_i_s.$content_box_left_title.$content_box_left_subtitle, ['content-box left'], []);
    $items_html .= \PHPStrap\Util\Html::tag('div', $content_box_left, [$args['rows_class']], []);
  }
  $title = $args['title']==false?"":\PHPStrap\Util\Html::tag('h3', _autoT($args['title']), [], []);
  $subtitle = $args['text']==false?"":\PHPStrap\Util\Html::tag('p', _autoT($args['text']), [], []);

  $row_2 = \PHPStrap\Util\Html::tag('div', $items_html, ['row'], []);
  $row = \PHPStrap\Util\Html::tag('div', $title.$subtitle.$row_2, ['row'], []);
  $container = \PHPStrap\Util\Html::tag('div', $row, ['container'], []);
  return \PHPStrap\Util\Html::tag('section', $container, [$args['class']], []);
}
add_shortcode('section-single-icons-text-full', 'pacmec_section_single_icons_text_full');

function pacmec_section_rows($atts, $content="")
{
  $args = \shortcode_atts([
    "class"   => "section cta-bottom-container content-contrast",
    "rows"   => false,
  ], $atts);
  $rows = [];
  if($args['rows'] !== false){
    $rows = pacmec_repair_string_shortcode($args['rows'], ["content"]);
  }
  $items_html = "";
  foreach ($rows as $item) {
    $item['class'] = !isset($item['class']) ? 'col-md-12' : $item['class'];
    $item['content'] = !isset($item['content']) ? '' : $item['content'];
    $items_html .= \PHPStrap\Util\Html::tag('div', $item['content'], [$item['class']], []);
  }
  $row = \PHPStrap\Util\Html::tag('div', $items_html, ['row'], []);
  $container = \PHPStrap\Util\Html::tag('div', $row, ['container'], []);
  return \PHPStrap\Util\Html::tag('section', $container, [$args['class']], []);
}
add_shortcode('section-rows', 'pacmec_section_rows');

function pacmec_section_sitemap_html($atts, $content="")
{
  return get_part('component/sitemap', PACMEC_CMS_COMPONENTS_PATH);
}
add_shortcode('section-sitemap-html', 'pacmec_section_sitemap_html');

function pacmec_section_services_for_category($atts, $content="")
{
  $raw_json = $GLOBALS['PACMEC']['fullData'];
  if($raw_json == null) $raw_json = [];
  $merge = array_merge($raw_json, $_POST, $_GET);
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
    /*_autoT("description"),*/
    _autoT("price"),
    _autoT("time_execution_estimated"),
    "",
  ]);

  foreach ($services as $service) {
    $table->addRow([
      $service->id,
      $service->name,
      /*$service->description_short,*/
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
  $raw_json = $GLOBALS['PACMEC']['fullData'];
  if($raw_json == null) $raw_json = [];
  $merge = array_merge($raw_json, $_POST, $_GET);
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
