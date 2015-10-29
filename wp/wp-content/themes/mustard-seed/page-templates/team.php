<?php
/**
 * Template Name: Team
 *
 * @package WordPress
 * @subpackage WPized_Light
 */

get_header();
the_post();
the_field('custom_title');
?>

<?php

// No content on this page!
//get_template_part( 'partials/content' );
get_template_part( 'partials/additional-content' );
get_template_part( 'partials/about_us-team' );

get_sidebar();
get_footer();