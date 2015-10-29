<?php
/**
 * WPized Light: Search
 *
 * @package WordPress
 * @subpackage WPized_Light
 */

if ( ! ( current_user_can( "Member" ) || current_user_can( "administrator" ) ) ) {
    echo "Access denied";
    exit;
}

get_header();
?>

<?php if ( have_posts() ) : ?>

    <?php get_template_part('partials/protected-part') ?>

    <?php get_search_query();  ?>

    <?php
    if (isset($_GET['order_by'])) {
        if ($_GET['order_by']=='viewed')
            query_posts($query_string . '&meta_key=views&orderby=meta_value&order=DESC');
        elseif ($_GET['order_by']=='recent')
            query_posts($query_string . '&orderby=date&order=DESC');
    }
    ?>

    <div class="events">
        <div class="inner-events">
            <div class="inner-event-row">

                <?php while ( have_posts() ) : the_post(); ?>

                    <?php get_template_part( 'partials/content', 'loop' ); ?>

                <?php endwhile; ?>
                <?php previous_posts_link(); ?>
                &emsp;
                <?php next_posts_link(); ?>
                <br><br>

            </div>
        </div>
    </div>



<?php else : ?>

    <?php get_template_part('partials/protected-part') ?>

    <div class="not-found">
        <h2>
            <?php _e( 'Nothing Found', WP_LIGHT ); ?>
        </h2>
    </div>

<?php endif; ?>


<?php get_sidebar(); ?>
<?php get_footer();
