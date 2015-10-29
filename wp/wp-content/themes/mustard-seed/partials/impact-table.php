<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 7/14/15
 * Time: 1:50 PM
 */
?>

<div class="inner-container">

    <div class="diagram-container diagram-horizontal">

        <?php if (get_field('long_bar_header')) : ?>
            <h2>
                <?php the_field('long_bar_header') ?>
            </h2>
        <?php endif; ?>
        <br>

        <div class="inner-diagram" data-equalize=".inner-diagram-item .title">

            <?php
            if( have_rows('long_bar') ):
                $counter = 0;
                // loop through the rows of data
                $field_object = get_field_object('long_bar');
                $total_rows = count($field_object['value']);
                while ( have_rows('long_bar') ) : the_row();
                    ?>

                    <div class="diagram-item <?php if ($counter == 0) ?>
                        "
                        >
                        <div class="inner-diagram-item">
                            <div class="info-container">
                                <div class="details">
                                    <h1 class="title">
                                        <?php the_sub_field('title'); ?>
                                    </h1>
                                </div>
                            </div>
                            <div class="image">
                                <?php $img = get_sub_field('image');
                                if( !empty($img) ) : ?>
                                    <img
                                        alt="<?php echo $img['title'] ?>"
                                        src="<?php echo $img['url'] ?>"
                                        >
                                <?php endif; ?>
                                <div class="vertical-border"></div>
                                <div class="horizontal-border"></div>
                            </div>
                            <div class="info-container">
                                <div class="details">
                                    <div class="text">
                                        <?php the_sub_field('content'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    $counter++;
                endwhile;
            endif; ?>
        </div>
    </div>

    <div class="clearfix"></div>

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
            $i = 0;

            while ( have_rows('entrepreneurs_fields') ) : the_row(); ?>

                <div class="glyph-point-container<?php if ($i % 2 == 0) echo ' glyph-odd'; ?>">
                    <?php $img = get_sub_field('image');
                    if( !empty($img) ) : ?>
                        <div class="glyph-image">
                            <img
                                alt="<?php echo $img['title'] ?>"
                                src="<?php echo $img['url'] ?>"
                                >
                        </div>
                    <?php endif; ?>
                    <h1 class="glyph-title">
                        <?php the_sub_field('title'); ?>
                    </h1>
                    <div class="glyph-text">
                        <?php the_sub_field('content'); ?>
                    </div>
                </div>
                <?php $i++; ?>

            <?php endwhile;
        endif; ?>

    </div>

    <?php if (get_field('entrepreneurs_button')) : ?>
        <button class="button place-center button-colorbox" href="<?php the_field('entrepreneurs_button_url'); ?>">
            <?php the_field('entrepreneurs_button') ?>
        </button>
    <?php endif; ?>

    <?php if (get_field('gray_background')) echo '</div>'; ?>

</div>

<?php $img = get_field('additional_image');
if( !empty($img) ) { ?>

    <div class="gallery-container">
        <div class="gallery-plugin">
            <div class="gallery-inner-container">
                    <img
                        alt="<?php echo $img['title'] ?>"
                        src="<?php echo $img['url'] ?>"
                        >
            </div>
        </div>
    </div>

<?php } else { ?>

    <br/><br/><br/>

<?php } ?>