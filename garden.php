<?php
/**
 *
 * Theme Name: Garden
 * Text Domain: garden
 * Description: Tema simple
 * Version: 0.1
 * Author: FelipheGomez
 * Author URI: https://github.com/FelipheGomez
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    Garden
 * @category   Themes
 * @copyright  2021 FelipheGomez
 * @version    1.0.1
 *
 */
 
function pacmec_Theme_Garden_activation()
{
  try {
	/**
	* Head
	*/
	add_style_head(folder_theme("garden")."/assets/css/bootstrap.min.css", ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 0.8, true);
	add_style_head(folder_theme("garden")."/assets/css/foundstrap.css", ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 0.8, true);
	add_style_head(folder_theme("garden")."/assets/css/font-awesome.min.css", ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 0.8, true);
	add_style_head(folder_theme("garden")."/assets/css/iconcrafts.css", ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 0.8, true);

	add_style_head(folder_theme("garden")."/assets/css/flexslider.css", ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 0.8, true);
	add_style_head(folder_theme("garden")."/assets/css/animate.min.css", ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 0.8, true);
	add_style_head(folder_theme("garden")."/assets/css/owl-carousel.css", ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 0.8, true);
	add_style_head(folder_theme("garden")."/assets/css/smartmenu.min.css", ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 0.8, true);

	add_style_head(folder_theme("garden")."/assets/css/style.css", ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 0.8, true);
	add_style_head(folder_theme("garden")."/assets/css/responsive.css", ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 0.8, true);

	/**
	* Footer
	*/
	add_scripts_foot(folder_theme("garden")."/assets/js/jquery-3.5.1.min.js", ["type"=>"text/javascript", "charset"=>"UTF-8"], 0.8, true);
	add_scripts_foot("https://maps.google.com/maps/api/js?key=".infosite('ga_key_js')."&libraries=places&v=weekly", ["type"=>"text/javascript", "charset"=>"UTF-8"], 0.8, true);
	add_scripts_foot(folder_theme("garden")."/assets/js/bootstrap.bundle.min.js", ["type"=>"text/javascript", "charset"=>"UTF-8"], 0.8, true);
	add_scripts_foot(folder_theme("garden")."/assets/js/jquery.smartmenus.min.js", ["type"=>"text/javascript", "charset"=>"UTF-8"], 0.8, true);
	add_scripts_foot(folder_theme("garden")."/assets/js/jquery.waypoints.min.js", ["type"=>"text/javascript", "charset"=>"UTF-8"], 0.8, true);
	add_scripts_foot(folder_theme("garden")."/assets/js/jquery.scrollUp.min.js", ["type"=>"text/javascript", "charset"=>"UTF-8"], 0.8, true);

	add_scripts_foot(folder_theme("garden")."/assets/js/jquery.flexslider-min.js", ["type"=>"text/javascript", "charset"=>"UTF-8"], 0.8, true);
	add_scripts_foot(folder_theme("garden")."/assets/js/owl.carousel.min.js", ["type"=>"text/javascript", "charset"=>"UTF-8"], 0.8, true);
	add_scripts_foot(folder_theme("garden")."/assets/js/jquery.countTo.js", ["type"=>"text/javascript", "charset"=>"UTF-8"], 0.8, true);
	add_scripts_foot(folder_theme("garden")."/assets/js/jquery.parallax.js", ["type"=>"text/javascript", "charset"=>"UTF-8"], 0.8, true);
	add_scripts_foot(folder_theme("garden")."/assets/js/isotope.pkgd.min.js", ["type"=>"text/javascript", "charset"=>"UTF-8"], 0.8, true);
	add_scripts_foot(folder_theme("garden")."/assets/js/jquery.easypiechart.min.js", ["type"=>"text/javascript", "charset"=>"UTF-8"], 0.8, true);
	add_scripts_foot(folder_theme("garden")."/assets/js/jquery.maps.js", ["type"=>"text/javascript", "charset"=>"UTF-8"], 0.8, true);
	add_scripts_foot(folder_theme("garden")."/assets/js/config.js", ["type"=>"text/javascript", "charset"=>"UTF-8"], 0.8, true);
    $tbls = [];
    foreach ($tbls as $tbl) {
      if(!pacmec_tbl_exist($tbl)){
        throw new \Exception("Falta la tbl: {$tbl}", 1);
      }
    }
  } catch (\Exception $e) {
    echo $e->getMessage();
    exit;
  }
  
	function garden_footer_trees($atts, $content="")
	{
	  $args = \shortcode_atts([
		"id"      => "trees_bg",
		"bg"      => "https%3A%2F%2Fpacmec.monteverdeltda.com%2Fuploads%2F2021%2F00%2Fbg_footer.jpg",
		"class"   => "section section-parallax content-contrast",
		"title"  => false,
		"content"  => false,
		"btns"  => false,
	  ], $atts);
	  /*
	  <div id="trees_bg" class="section section-parallax content-contrast">
		<div class="overlay-background"></div>
		<div class="container">
			<div class="row">
				<div class="col-md-7">
					<div class="heading-title gap" data-gap-bottom="60">
						<h2>Ready to Choose your Plan?</h2>
						<p>To request a quote for any of our services please either phone or email and we will contact you to make an appointment</p>
					</div>
					<a href="#" class="btn btn-outline white">Get Free Quote</a>
				</div>
			</div>
		</div>
	  </div>
	  typeÞoutline%20white&textÞfooter_trees_btn1&hrefÞhttps%3A%2F%2Fpacmec.monteverdeltda.com%2Fuploads%2F2021%2F00%2Fbg-1.jpg
	  */
	  $btns_a = [];
	  if($args['btns'] !== false){
		foreach (explode(',', $args['btns'] ) as $z) {
		  $btn_b = [];
		  foreach (explode('&', $z) as $y) {
			$k_v = explode('Þ', $y);
			if(isset($k_v[1])){
			  if(in_array($k_v[0], [
				"title",
				"text"
				])){
				$btn_b[$k_v[0]] = (urldecode($k_v[1]));
			  } else {
				$btn_b[$k_v[0]] = urldecode($k_v[1]);
			  }
			}
		  }
		  $btns_a[] = $btn_b;
		}
	  }
	  $btns = "";
	  foreach ($btns_a as $btn) {
		$btn_a = \shortcode_atts([
		  "href"     => false,
		  "type"     => false,
		  "text"     => false,
		], $btn);
		if($btn_a['text'] !== false && $btn_a['href'] !== false){
		  $btns .= "\n".\PHPStrap\Util\Html::tag('a', _autoT($btn['text']), [($btn_a['type']!==false?"btn btn-{$btn_a['type']}":"btn btn-default")], ["href"=>$btn_a['href']]);
		}
	  }
	  $overlay = \PHPStrap\Util\Html::tag('div', '', ['overlay-background'],['style'=>"background-image:url(".urldecode($args['bg']).");background-size:cover;"]);
	  $title = ($args['title']==false) ? "" : \PHPStrap\Util\Html::tag('h2', _autoT(urldecode($args['title'])), [], ["style"=>"color: #FFF;text-shadow: 2px 2px 6px #000;"]);
	  $p = ($args['content']==false) ? "" : \PHPStrap\Util\Html::tag('p', _autoT(urldecode($args['content'])), [], ["style"=>"color: #FFF;text-shadow: 2px 2px 3px #333;"]);
	  $heading = \PHPStrap\Util\Html::tag('div', $title.$p, ['heading-title gap'], ["data-gap-bottom"=>"60"]);
	  $col = \PHPStrap\Util\Html::tag('div', $heading.$btns, ['col-md-7']);
	  $row = \PHPStrap\Util\Html::tag('div', $col, ['row']);
	  $container = \PHPStrap\Util\Html::tag('div', $row, ['container']);
	  return \PHPStrap\Util\Html::tag('div', $overlay.$container, ['section section-parallax content-contrast'], ['id'=>$args['id']]);
	}
	add_shortcode('footer-trees', 'garden_footer_trees');

}
\register_activation_plugin('garden', 'pacmec_Theme_Garden_activation');
