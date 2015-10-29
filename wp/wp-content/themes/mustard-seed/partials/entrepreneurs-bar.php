<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 7/23/15
 * Time: 5:28 PM
 */
?>

<div id="long-bar">
    <div class="inner-container">

        <div class="diagram-container diagram-horizontal">

            <?php if (get_field('long_bar_header')) : ?>
                <h2>
                    <?php the_field('long_bar_header') ?>
                </h2>
            <?php endif; ?>
            <br>

            <div class="inner-diagram">

                <?php
                if( have_rows('long_bar') ):
                    $counter = 0;
                    // loop through the rows of data
                    $field_object = get_field_object('long_bar');
                    $total_rows = count($field_object['value']);
                    while ( have_rows('long_bar') ) : the_row();
                        ?>

                        <div class="diagram-item <?php if ($counter == 0) { echo 'first'; } ?>
                            <?php if ($counter == $total_rows - 1) { echo 'last'; } ?>
                            "
                            >
                            <div class="inner-diagram-item">
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
                                        <h1 class="title">
                                            <?php the_sub_field('title'); ?>
                                        </h1>
                                        <div class="text">
                                            <p>
                                                <?php the_sub_field('content'); ?>
                                            </p>
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
    </div>
</div>