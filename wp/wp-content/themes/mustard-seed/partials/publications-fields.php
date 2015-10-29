<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 7/2/15
 * Time: 2:07 PM
 */
?>

<?php
$NT_id = get_page_by_path('news-and-thoughts')->ID;
$posts = array();
if( have_rows('publications_bar', $NT_id) ):
// loop through the rows of data
    $i=0;
    while ( have_rows('publications_bar', $NT_id) ) : the_row();
        $posts[$i++] = get_sub_field("post");
    endwhile;
endif;

$publications_title = get_field('thought-provoking_publications');
$blog_title = get_field('seeding_bloggers');

?>

<div class="page-description">
    <div class="inner-description">
        <div class="description">
            <div class="title">
                <h1>
                    <?php the_field('publication_title'); ?>
                </h1>
            </div>
            <div class="desc"></div>
        </div>
    </div>
</div>

<div id="portfolio-list">
    <div class="inner-portfolio-list">
        <div class="portfolio-row">

            <?php

            $count = 4;
            $i = 0;

            foreach ( $posts as $post_id ) :

                $args = array( 'post_type' => 'Publication', 'p' => $post_id);
                $loop = new WP_Query( $args );
                while ( $loop->have_posts() ) : $loop->the_post();

                    ?>

                    <div class="portfolio-item">
                        <div class="image-container">
                            <div class="image">
                                <?php $img = get_field('publication_image');
                                if( !empty($img) ) : ?>
                                    <a href="<?php the_field('publication_pdf'); ?>">
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
                    $i++;

                    if ($i % $count == 0 && $i !== $loop->post_count) {
                        echo '</div><div class="portfolio-row-inner">';
                    }
                    ?>

                <?php
                endwhile;
            endforeach; ?>

        </div>
        <div class="portfolio-row"></div>
    </div>
    <div class="inner-tablet-portfolio-list">
        <div class="portfolio-row">


            <?php
            $args = array( 'post_type' => 'Publication' );
            $loop = new WP_Query( $args );
            while ( $loop->have_posts() ) : $loop->the_post(); ?>

                <div class="portfolio-item">
                    <div class="image-container">
                        <div class="image">
                            <?php $img = get_field('publication_image');
                            if( !empty($img) ) : ?>
                                <a href="<?php the_field('publication_pdf'); ?>">
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

            <?php endwhile; ?>

        </div>
    </div>
</div>