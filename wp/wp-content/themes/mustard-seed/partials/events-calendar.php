<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 6/22/15
 * Time: 4:14 PM
 */

$img_slider = array();

if( have_rows('images_slider') ):
    // loop through the rows of data
    $z_index = 0;
    while ( have_rows('images_slider') ) : the_row();
        $img_slider[$z_index] = get_sub_field('image');
        $z_index++;
    endwhile;
endif;

$past_events_text = get_field('past_events');
?>

<div class="events">
    <div class="inner-events">
        <div class="inner-event-row">
            <div class="event-title">
                <h1 style="border-bottom: 1px solid #EAEAEA;">
                    <?php the_field('upcoming_events'); ?>
                </h1>
            </div>

            <?php
            $args = array( 'post_type' => 'Event',
                'posts_per_page'    => -1,
                'meta_key'			=> 'event_date',
                'orderby'			=> 'meta_value_num',
                'order'	            => 'ASC'
            );
            $loop = new WP_Query( $args );
            while ( $loop->have_posts() ) : $loop->the_post();

                if ( get_field("event_date") >= date('Ymd') ) :?>

                    <div class="event-item">
                        <div class="inner-event-item">
                            <div class="event-container">
                                <div class="event-item">
                                    <div class="date">
                                        <time><?php echo date("M d", strtotime(get_field("event_date"))); ?></time>
                                        <?php $img = get_field('event_image');
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
                                                <?php the_title(); ?>
                                            </h1>
                                            <div class="event-header">
                                                <div class="info">
                                                    <h2>
                                                        <?php the_field("header"); ?>
                                                    </h2>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="description">
                                                    <span>
                                                        <p>
                                                            <?php the_field('intro'); ?>
                                                        </p>
                                                    </span>
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


<div class="gallery-container">
    <div class="gallery-plugin" style="display: block;">
        <div class="gallery-inner-container">
            <div class="gallery-slideshow" data-cycle-center-horz="true" data-cycle-center-vert="true" data-cycle-prev="#prev" data-cycle-next="#next" data-cycle-swipe="true" data-cycle-swipe-fx="scrollHorz" data-cycle-fx="scrollHorz" data-cycle-timeout="0" data-cycle-log="false" data-cycle-speed="500" data-cycle-slides="&gt; .slide" data-cycle-caption=".slideshow-caption p" data-cycle-caption-template="{{cycleTitle}}" style="position: relative;">

                <?php
                $z_index = 0;
                foreach ($img_slider as $img) : ?>

                    <div class="slide cycle-slide" data-cycle-title="" style="position: absolute; top: 0px; left: 0px; z-index: <?php echo $z_index; ?>; visibility: hidden; margin-left: 0px; margin-top: 0px;">
                        <?php if( !empty($img) ) : ?>
                            <img src="<?php echo $img['url']; ?>" >
                        <?php endif; ?>
                    </div>

                    <?php
                    $z_index++;
                endforeach;
                ?>

            </div>
            <div class="navigation"><a id="prev" href="#">&nbsp;</a><a id="next" href="#">&nbsp;</a></div>
            <div class="caption slideshow-caption">
                <p></p>
            </div>
        </div>
    </div>
</div>


<div class="events-container">
    <div class="events past-events">
        <div class="inner-events">
            <div class="inner-event-row">
                <div class="event-title">
                    <h1 style="border-bottom: 1px solid #EAEAEA;">
                        <?php echo $past_events_text; ?>
                    </h1>
                </div>

                <?php
                $args = array( 'post_type' => 'Event',
                    'posts_per_page'    => -1,
                    'meta_key'			=> 'event_date',
                    'orderby'			=> 'meta_value_num',
                    'order'	            => 'DESC'
                );
                $loop = new WP_Query( $args );
                $count = 5;
                $i = 0;
                while ( $loop->have_posts() ) : $loop->the_post();

                    if ( get_field("event_date") < date('Ymd') ) :
                        $i++; ?>

                        <div class="event-item" <?php if ($i > $count) echo 'style="display: none;"'; ?>>
                            <div class="inner-event-item">
                                <div class="event-container">
                                    <div class="event-item">
                                        <div class="date">
                                            <?php echo date("d M Y", strtotime(get_field("event_date"))); ?>
                                        </div>
                                        <div class="details-container">
                                            <div class="details-inner-container">
                                                <h1>
                                                    <?php the_title(); ?>
                                                </h1>
                                                <div class="event-header">
                                                    <div class="info">
                                                        <h2>
                                                            <?php the_field("header"); ?>
                                                        </h2>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="description">
                                                    <span>
                                                        <p>
                                                            <?php the_field('intro'); ?>
                                                        </p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endif;
                endwhile; ?>

                <?php if ($loop->post_count > $count) : ?>
                    <a href="#" class="button" data-load-more=".event-item">Load more</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>