<?php
/**
 * Template Name: Events
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
get_template_part( 'partials/events-calendar' );

get_sidebar();
get_footer();
