<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 6/24/15
 * Time: 12:40 PM
 */
?>

<br>
<div class="diagram-container diagram-vertical">
    <div class="inner-diagram">

        <h1>
            <?php the_field('impact_bottom_title'); ?>
        </h1>

        <?php
        if( have_rows('impact_bottom_panel') ):
        // loop through the rows of data
        $field_object = get_field_object('impact_bottom_panel');
        $total_rows = count($field_object['value']);
        $counter = 0;
        while ( have_rows('impact_bottom_panel') ) : the_row();
        ?>
        <?php if ( get_sub_field("is_list") ) : ?>
        <div class="diagram-item <?php if ($counter == 0) { echo 'first'; } ?>
                                    <?php if ($counter == $total_rows - 1) { echo 'last'; } ?>"
            >
            <?php else: ?>
            <div class="diagram-item special">
                <?php endif; ?>

                <div class="inner-diagram-item">
                    <div class="image">
                        <?php $img = get_sub_field('image');
                        if( !empty($img) ) : ?>
                        <img
                            alt="<?php echo $img['title'] ?>"
                            src="<?php echo $img['url'] ?>"
                            >
                        <?php endif; ?>
                        <?php if ($counter == 0) : ?>
                            <div style="top: 25.5px; left: 41px; height: 341px; visibility: visible;" class="vertical-border"></div>
                        <?php endif; ?>
                        <div style="visibility: hidden;" class="horizontal-border"></div>
                    </div>
                    <div class="info-container">
                        <div class="details">
                            <h1 class="title">
                                <?php the_sub_field('header'); ?>
                            </h1>
                        </div>
                        <div class="children">

                            <?php
                            if( have_rows('rows') ):
                                // loop through the rows of data
                                while ( have_rows('rows') ) : the_row(); ?>

                                    <div class="child-row">
                                        <?php the_sub_field('content'); ?>
                                    </div>

                                <?php endwhile; ?>

                                <a class="button" href="<?php the_sub_field('button_url'); ?>" target="_blank">
                                    <?php the_sub_field('button'); ?>
                                </a>
                                <div class="vertical-border"></div>

                            <?php else: ?>

                                <div class="text">
                                    <?php the_sub_field('content'); ?>
                                </div>

                            <?php endif; ?>

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
</div>