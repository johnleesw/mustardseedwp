<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 6/22/15
 * Time: 3:48 PM
 */
?>


<div class="page-description">
    <div class="inner-description" style="border-bottom: 1px solid #EAEAEA; width:1020px; margin-left:-1px">
        <div class="description">
            <div class="title">
                <h1>
                    <a id="portfolio">
                        <?php the_field('publication_title'); ?>
                    </a>
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
            if( have_rows('portfolio') ):
                // loop through the rows of data
                while ( have_rows('portfolio') ) : the_row(); ?>

                    <div class="portfolio-item">
                        <div class="image-container">
                            <div class="image">
                                <?php $img = get_sub_field('image');
                                if( !empty($img) ) : ?>
                                    <a href="<?php the_sub_field('url') ?>">
                                        <img src="<?php echo $img['url']; ?>" >
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="portfolio-details">
                            <h1>
                                <?php the_sub_field('title'); ?>
                            </h1>
                            <p>
                                <?php the_sub_field('content'); ?>
                            </p>
                        </div>
                    </div>

                <?php endwhile;
            endif; ?>

        </div>
        <div class="portfolio-row"></div>
    </div>
    <div class="inner-tablet-portfolio-list">
        <div class="portfolio-row">


            <?php
            if( have_rows('portfolio') ):
                // loop through the rows of data
                while ( have_rows('portfolio') ) : the_row(); ?>

                    <div class="portfolio-item">
                        <div class="image-container">
                            <div class="image">
                                <?php $img = get_sub_field('image');
                                if( !empty($img) ) : ?>
                                    <img src="<?php echo $img['url']; ?>" >
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="portfolio-details">
                            <h1>
                                <?php the_sub_field('title'); ?>
                            </h1>
                            <p>
                                <?php the_sub_field('content'); ?>
                            </p>
                        </div>
                    </div>

                <?php endwhile;
            endif; ?>

        </div>
    </div>
</div>