<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 6/30/15
 * Time: 1:21 PM
 */
?>

<?php
$posts = array();
if( have_rows('publications_bar') ):
// loop through the rows of data
    $i=0;
    while ( have_rows('publications_bar') ) : the_row();
        $posts[$i++] = get_sub_field("post");
    endwhile;
endif;

$publications_title = get_field('thought-provoking_publications');
$blog_title = get_field('seeding_bloggers');

?>

<div class="events">
    <div class="inner-events">
        <div class="inner-event-row">
            <div class="event-title">
                <h1 style="border-bottom: 1px solid #EAEAEA;">
                    <?php the_field('mustard_seed_in_the_press'); ?>
                </h1>
            </div>

            <?php
            $args = array( 'post_type' => 'Press',
                'posts_per_page' => '3',
                'meta_key'			=> 'press_date',
                'orderby'			=> 'meta_value_num',
                'order'	            => 'DESC'
            );
            $loop = new WP_Query( $args );

            while ( $loop->have_posts() ) : $loop->the_post();
                if ( get_field("press_date") ) :?>

                    <div class="event-item">
                        <div class="inner-event-item">
                            <div class="event-container">
                                <div class="event-item vertical-align">
                                    <div class="date">
                                        <?php $img = get_field('press_image');
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
                                                        <?php the_field("header"); ?>
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
        <div class="load-more" style="float: right">
            <a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Press' ) ) ); ?>" style="color: orange">
                <b>Load more</b>
            </a>
        </div>
    </div>
</div>

<br><br><br><br><br><br>

<div class="events">
    <div class="inner-events">
        <div class="inner-event-row">
            <div class="event-title">
                <h1>
                    <?php echo $publications_title; ?>
                </h1>
            </div>
        </div>
    </div>
</div>


<div id="portfolio-list">
    <div class="inner-portfolio-list">
        <div class="portfolio-row-inner">
            <div class="portfolio-row js-flickity" data-flickity-options='{ "cellAlign": "left", "pageDots": false, "wrapAround": true }'>

                <?php

                foreach ( $posts as $post_id ) :

                    $args = array( 'post_type' => 'Publication', 'p' => $post_id);
                    $loop = new WP_Query( $args );
                    while ( $loop->have_posts() ) : $loop->the_post();
                        ?>

                        <div class="portfolio-item slider-item">
                            <div class="image-container">
                                <div class="image">
                                    <?php $img = get_field('publication_image');
                                    if( !empty($img) ) : ?>
                                        <a href="<?php the_field('publication_pdf') ?>">
                                            <img src="<?php echo $img['url']; ?>" >
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="portfolio-details">
                                <h1>
                                    <?php
                                    $title = get_the_title();
                                    echo $title;
                                    if (strlen($title) < 28) {
                                        echo "&nbsp;&nbsp;";
                                    }
                                    ?>
                                </h1>
                                <p>
                                    <?php echo substr( get_the_content(''), 0, 140).'...' ?>
                                </p>
                                <?php if (get_field('publication_pdf')) { ?>
                                    <a href="<?php the_field('publication_pdf') ?>" style="color: orange;">
                                        Read more
                                    </a>
                                <?php } else { ?>
                                    <a href="<?php echo get_permalink( get_the_ID() ); ?>" style="color: orange;">
                                        Read more
                                    </a>
                                <?php } ?>
                            </div>
                        </div>

                    <?php
                    endwhile;
                endforeach; ?>

            </div>
        </div>
        <div class="portfolio-row"></div>
    </div>
    <div class="inner-tablet-portfolio-list">
        <div class="portfolio-row js-flickity" data-flickity-options='{ "cellAlign": "left", "pageDots": false, "wrapAround": true }'>
            <div class="portfolio-row-inner">
                <?php
                $args = array( 'post_type' => 'Publication',
                );
                $loop = new WP_Query( $args );
                $count = 3;
                $i = 0;
                while ( $loop->have_posts() ) : $loop->the_post(); ?>

                    <div class="portfolio-item slider-item">
                        <div class="image-container">
                            <div class="image">
                                <?php $img = get_field('publication_image');
                                if( !empty($img) ) : ?>
                                    <img src="<?php echo $img['url']; ?>" >
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="portfolio-details">
                            <h1>
                                <?php
                                $title = get_the_title();
                                echo $title;
                                if (strlen($title) < 28) {
                                    echo "&nbsp;&nbsp;";
                                }
                                ?>
                            </h1>
                            <p>
                                <?php echo substr( get_the_content(''), 0, 140).'...' ?>
                            </p>
                            <?php if (get_field('publication_pdf')) { ?>
                                <a href="<?php the_field('publication_pdf') ?>" style="color: orange;">
                                    Read more
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo get_permalink( get_the_ID() ); ?>" style="color: orange;">
                                    Read more
                                </a>
                            <?php } ?>
                        </div>
                    </div>

                    <?php
                    $i++;

                    if ($i % $count == 0 && $i !== $loop->post_count) {
                        echo '</div><div class="portfolio-row-inner">';
                    }
                    ?>

                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <div class="load-more" style="float: right">
        <a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Resources' ) ) ); ?>" style="color: orange">
            <b>Load more</b>
        </a>
    </div>
</div>

<br><br><br><br>


<div class="events">
    <div class="inner-events">
        <div class="inner-event-row">
            <div class="event-title">
                <h1>
                    <?php echo $blog_title; ?>
                </h1>
            </div>

            <?php
            $args = array( 'post_type' => 'Blog',
                'posts_per_page' => '3',
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
                                <div class="event-item">
                                    <div class="date">
                                        <h4>
                                            <?php echo date("d M Y", strtotime(get_field("blog_date"))); ?>
                                        </h4>
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
        <div class="load-more" style="float: right">
            <a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Blog' ) ) ); ?>" style="color: orange">
                <b>Load more</b>
            </a>
        </div>
    </div>
</div>

<br><br><br><br>