<?php
/*
 * WPIzed Light: Page
 *
 * @package WordPress
 * @subpackage WPized_Light
 */

get_header();
the_post();
the_field('custom_title')
?>

<?php get_template_part( 'partials/content' ); ?>

<?php get_sidebar(); ?>
<?php get_footer();
?>
