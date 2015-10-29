<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 6/24/15
 * Time: 2:51 PM
 */
?>

<div id="container" class="container-fluid">
    <div class="inner-container">

        <div class="glyph-table-container">
            <div class="glyph-table-inner-container">
                <div class="glyph-table-title">
                    <h1>
                        <?php the_field('impact_fields_label') ?>
                    </h1>
                </div>
                <div class="glyph-table">

                    <div class="table-header table-row">
                        <div class="table-col col1"></div>
                        <div class="table-col col2">
                            <?php the_field('header_1') ?>
                        </div>
                        <div class="table-col col1"></div>
                        <div class="table-col col2">
                            <?php the_field('header_2') ?>
                        </div>
                    </div>

                    <?php
                    if( have_rows('impact_fields') ):
                        // loop through the rows of data
                        while ( have_rows('impact_fields') ) : the_row(); ?>

                            <div class="table-row">
                                <div class="table-col col1">
                                    <?php $img = get_sub_field('image');
                                    if( !empty($img) ) : ?>
                                        <img
                                            alt="<?php echo $img['title'] ?>"
                                            src="<?php echo $img['url'] ?>"
                                            >
                                    <?php endif; ?>
                                </div>
                                <div class="table-col col2">
                                    <h1>
                                        <?php the_sub_field('title_column_1'); ?>
                                    </h1>
                                    <p>
                                        <?php the_sub_field('content_column_1'); ?>
                                    </p>
                                </div>
                                <div class="table-col col1">
                                    <?php $img = get_sub_field('image_2');
                                    if( !empty($img) ) : ?>
                                        <img
                                            alt="<?php echo $img['title'] ?>"
                                            src="<?php echo $img['url'] ?>"
                                            >
                                    <?php endif; ?>
                                </div>
                                <div class="table-col col2">
                                    <h1>
                                        <?php the_sub_field('title_column_2'); ?>
                                    </h1>
                                    <p>
                                        <?php the_sub_field('content_column_2'); ?>
                                    </p>
                                </div>
                            </div>

                        <?php endwhile;
                    endif; ?>

                </div>
            </div>
        </div>


    </div>
</div>
