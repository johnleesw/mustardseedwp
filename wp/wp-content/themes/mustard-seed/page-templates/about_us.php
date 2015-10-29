<?php
/**
 * Template Name: About us
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

get_template_part( 'partials/about_us-fields' );

get_template_part( 'partials/additional-content2' );
get_template_part( 'partials/our_mission-fields' );

get_template_part( 'partials/about_us-team'  );

get_template_part( 'partials/about_us-footer' );

get_sidebar();
get_footer();
