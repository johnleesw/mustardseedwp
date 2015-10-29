<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 7/2/15
 * Time: 2:10 PM
 */
?>

<div class="events">
    <div class="inner-events">
        <div class="inner-event-row">
            <div class="event-title">
                <h1>
                    <?php the_field('publication_title'); ?>
                </h1>
            </div>

            <?php
            $args = array( 'post_type' => 'Blog',
                'meta_key'			=> 'blog_date',
                'orderby'			=> 'meta_value_num',
                'order'	            => 'DESC'
            );
            $loop = new WP_Query( $args );
            while ( $loop->have_posts() ) : $loop->the_post();

                if ( get_field("blog_date") ) :?>

                    <div class="event-item">
                        <div class="inner-event-item">
                            <div class="event-container">
                                <div class="event-item vertical-align">
                                    <div class="date">
                                        <h4><?php echo date("d M Y", strtotime(get_field("blog_date"))); ?></h4>
                                        <?php $img = get_field('blog_image');
                                        if( !empty($img) ) : ?>
                                            <img
                                                <?php if($img['width'] > 175) echo 'width=175'; ?>
                                                src="<?php echo $img['url']; ?>"
                                                >
                                        <?php endif; ?>
                                    </div>
                                    <div class="details-container">
                                        <div class="details-inner-container">
                                            <h1>
                                                <a href="<?php echo get_permalink( get_the_ID() ); ?>">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h1>
                                            <div class="event-header">
                                                <div class="info">
                                                    <h2>
                                                        <?php the_field('header'); ?> <br>
                                                    </h2>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="description">
                                                <p>
                                                <div  style="overflow: hidden; height: 82px">
                                                        <span>
                                                            <?php the_field('intro') ?>
                                                        </span>
                                                </div>
                                                <a href="<?php echo get_permalink( get_the_ID() ); ?>">
                                                    Read more
                                                </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif;
            endwhile; ?>

        </div>
    </div>
</div>