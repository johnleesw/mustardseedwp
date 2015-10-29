<?php
/**
 * Template Name: Protected
 *
 * @package WordPress
 * @subpackage WPized_Light
 */

get_header();
the_post();
the_field('custom_title');
?>

<?php

get_template_part('partials/protected-part');

get_sidebar();
get_footer();