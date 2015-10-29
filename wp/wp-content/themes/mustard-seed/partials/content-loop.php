<?php
/**
 * WPized Light: content-single
 *
 * The template for displaying content after all other content-{template}
 * Contains some basic HTML and WordPress functions that are quite common across
 * all projects
 *
 * For the moment equal to content-single
 */
?>

<div class="event-item">
    <div class="inner-event-item">
        <div class="event-container">
            <div class="event-item">

                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <a href="<?php echo esc_attr( get_permalink() ); ?>">
                        <?php the_title(); ?>
                    </a>

                    <div class="date">
                        <?php if ( $link = get_field('submissionvideo_link') ) { ?>
                            <iframe src="<?php echo $link; ?>" width="200" frameborder="0"></iframe>
                        <?php } else { ?>
                            &nbsp;
                        <?php } ?>
                    </div>

                    <div class="content">
                        <?php echo substr( get_the_content(''), 0, 160).'...' ?>
                    </div>

                </div>
                <!-- / post -->

                <div class="pagination">
                    <?php wp_link_pages(); ?>
                </div>

            </div>
        </div>
    </div>
</div>