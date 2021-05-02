<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    Themes
 * @category   Garden
 * @version    1.0.1
 */
?>

    <?php get_template_part('template-parts/footer/site-footer'); ?>

    <?php pacmec_foot(); ?>

    <script type="text/javascript">
        $(document).ready(function () {
            if ($(window).width() > 768) {
                $(".section-parallax").addClass('parallax').parallax("80%", 0.5);
            }
        });
    </script>
  </body>
</html>
