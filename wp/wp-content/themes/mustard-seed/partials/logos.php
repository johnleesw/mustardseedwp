<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 7/23/15
 * Time: 3:48 PM
 */
?>

<?php if( have_rows('logos') ) : ?>

    <div class="page-description">
        <div class="inner-description">
            <div class="description">
                <div class="title" style="width: auto">
                    <h1>
                        <a id="portfolio">
                            <?php the_field('logos_header'); ?>
                        </a>
                    </h1>
                </div>
                <div class="desc"></div>
            </div>
        </div>
    </div>

    <div id="portfolio-list">
        <div class="inner-portfolio-list logos">
            <?php if( have_rows('logos') ) :
                while ( have_rows('logos') ) : the_row(); ?>
                    <img src="<?php the_sub_field('image'); ?>">
                <?php endwhile;
            endif; ?>
        </div>
    </div>

<?php endif; ?>