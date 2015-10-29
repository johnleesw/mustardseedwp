<?php
/**
 * Template Name: Entrepreneurs
 *
 * @package WordPress
 * @subpackage WPized_Light
 */

get_header();
the_post();
the_field('custom_title');
?>

<?php
get_template_part( 'partials/content' );
get_template_part( 'partials/additional-content' );
get_template_part( 'partials/entrepreneurs-bar' );
get_template_part( 'partials/additional-content2' );
get_template_part( 'partials/entrepreneurs-fields' );
get_template_part( 'partials/portfolio-fields' );

get_sidebar();
get_footer();
