<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    Themes
 * @category   Garden
 * @version    1.0.1
 */
?>
<!doctype html>
<html <?= language_attributes(); ?>>
  <head>
    <meta charset="<?= pageinfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php pacmec_head(); ?>
  </head>
  <body>
    <?php get_template_part('template-parts/header/site-header'); ?>
