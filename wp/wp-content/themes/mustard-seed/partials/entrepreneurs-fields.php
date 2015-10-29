<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 6/22/15
 * Time: 5:21 PM
 */
?>

<div class="inner-container">

    <?php if (get_field('gray_background')) echo '<div class="events-container">'; ?>

    <div class="glyph-container">

        <div class="glyph-info">
            <h1>
                <?php the_field('entrepreneurs_fields_label'); ?>
            </h1>
            <p>
                <?php the_field('entrepreneurs_fields_header'); ?>
            </p>
        </div>

        <?php
        if( have_rows('entrepreneurs_fields') ):
            // loop through the rows of data
            while ( have_rows('entrepreneurs_fields') ) : the_row(); ?>

                <div class="glyph-point-container glyph-odd">
                    <div class="glyph-inner-container">
                        <?php $img = get_sub_field('image');
                        if( !empty($img) ) : ?>
                            <div class="glyph-image">
                                <img
                                    alt="<?php echo $img['title'] ?>"
                                    src="<?php echo $img['url'] ?>"
                                    >
                            </div>
                        <?php endif; ?>
                        <div class="details">
                            <h1 class="glyph-title no-bottom-margin">
                                <?php the_sub_field('title'); ?>
                            </h1>
                            <div class="glyph-text">
                                <p>
                                    <?php the_sub_field('content'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endwhile;
        endif; ?>

    </div>

    <?php if (get_field('entrepreneurs_button')) : ?>
        <button class="button place-center button-colorbox" href="<?php the_field('entrepreneurs_button_url'); ?>">
            <?php the_field('entrepreneurs_button') ?>
        </button>
    <?php endif; ?>

    <br><br>

    <?php if (get_field('gray_background')) echo '</div>'; ?>

    <?php $img = get_field('additional_image');
    if( !empty($img) ) : ?>

        <div class="full-text">
            <div class="inner-title-container">
            </div>
            <div class="inner-text">
                    <img
                        alt="<?php echo $img['title'] ?>"
                        src="<?php echo $img['url'] ?>"
                        >
            </div>
        </div>

    <?php endif; ?>

</div>