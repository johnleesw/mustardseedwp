<?php
/**
 * Template Name: Our mission
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
get_template_part( 'partials/our_mission-fields' );

get_sidebar();
get_footer();