<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    Themes
 * @category   Garden
 * @version    1.0.1
 */

// echo json_encode($GLOBALS['PACMEC'], JSON_PRETTY_PRINT);

get_header();
  if(route_active())
  { get_template_part( 'template-parts/content/'.$GLOBALS['PACMEC']['route']->component ); }
  else
  { get_template_part( 'template-parts/content/content-error' ); }
get_footer();
