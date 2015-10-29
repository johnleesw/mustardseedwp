<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 6/22/15
 * Time: 12:54 PM
 */
?>

<?php
if( have_rows('custom_fields') ):
    // loop through the rows of data
    while ( have_rows('custom_fields') ) : the_row(); ?>

        <div class="clearfix"></div>

        <div class="full-text vertical-align">
            <div class="inner-title-container">
                <div class="image about-us-image">
                    <?php $img = get_sub_field('image');
                    if( !empty($img) ) : ?>
                        <img src="<?php echo $img['url']; ?>" >
                    <?php endif; ?>
                </div>
            </div>
            <div class="inner-text">
                <h2>
                    <?php the_sub_field('title'); ?>
                </h2>
                <p>
                    <?php the_sub_field('content'); ?>
                </p>
            </div>
        </div>

    <?php endwhile;
endif; ?>
