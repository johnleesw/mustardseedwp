<?php
/**
 * WPized Light: homepage_fields
 *
 * @package WordPress
 * @subpackage WPized_Light
 */
?>

<div id="container" class="container-fluid">
    <div class="inner-container">
        <div class="clearfix"></div>
        <div class="glyph-container">
            <div class="glyph-info">
                <h1>
                    <?php the_field('homepage_fields_label') ?>
                </h1>
            </div>

            <?php
            if( have_rows('homepage_field') ):
                // loop through the rows of data
                while ( have_rows('homepage_field') ) : the_row(); ?>

                    <div class="glyph-point-container glyph-odd">
                        <div class="glyph-inner-container">
                            <div class="glyph-image">
                                <?php $img = get_sub_field('homepage_field_image');
                                if( !empty($img) ) : ?>
                                    <img
                                        alt="<?php echo $img['title'] ?>"
                                        src="<?php echo $img['url'] ?>"
                                        >
                                <?php endif; ?>
                            </div>
                            <div class="details">
                                <h1 class="glyph-title no-bottom-margin">
                                    <?php the_sub_field('homepage_field_title'); ?>
                                </h1>
                                <div class="glyph-text">
                                    <p>
                                        <?php the_sub_field('homepage_field_content'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endwhile;
            endif; ?>

        </div>
        <div class="join-container">
            <div class="inner-join-container">

                <?php
                if( have_rows('bottom_buttons') ):
                    // loop through the rows of data
                    $counter = 0;
                    while ( have_rows('bottom_buttons') ) : the_row();
                        $counter++;
                        ?>

                        <div class="join-block">
                            <div class="join-glyph-image">
                                <?php $img = get_sub_field('image');
                                if( !empty($img) ) : ?>
                                    <img src="<?php echo $img['url']; ?>" >
                                <?php endif; ?>
                            </div>
                            <h1>
                                <?php the_sub_field('title'); ?>
                            </h1>
                            <div class="join-description">
                                <?php the_sub_field('content'); ?>
                            </div>
                            <button class="button button-colorbox<?php if ($counter!=2 && $counter!=1) echo '-login'; ?>" href="<?php the_sub_field('button_url'); ?>">
                                <?php the_sub_field('button'); ?>
                            </button>
                        </div>

                    <?php endwhile;
                endif; ?>

            </div>
        </div>

    </div>
</div>

