<?php
/**
 * Template Name: Blog
 *
 * @package WordPress
 * @subpackage WPized_Light
 */

get_header();
the_post();
the_field('custom_title');
?>

<?php
get_template_part( 'partials/additional-content' );
get_template_part( 'partials/blog-fields' );

get_sidebar();
get_footer();
