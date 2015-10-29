<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 6/26/15
 * Time: 4:33 PM
 */
?>

<?php
if( have_rows('additional_content') ):
    // loop through the rows of data
    while ( have_rows('additional_content') ) : the_row(); ?>

        <div class="inner-container">
            <div class="full-text">
                <div class="inner-title-container">
                    <h2>
                        <?php the_sub_field('header'); ?>
                    </h2>
                </div>
                <div class="inner-text">

                        <?php the_sub_field('content'); ?>

                </div>
            </div>
        </div>

    <?php endwhile;
endif; ?>