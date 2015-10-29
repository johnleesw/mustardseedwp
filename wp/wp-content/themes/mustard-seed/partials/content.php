<?php
/**
 * WPized Light: content-single
 *
 * The template for displaying content after all other content-{template} files
 * were either not used or not found, see:
 * http://codex.wordpress.org/Function_Reference/get_template_part
 *
 * @package WordPress
 * @subpackage WPized_Light
 */
?>

<div id="container" class="container-fluid">
    <div class="inner-container">
        <div class="full-text">
            <div class="inner-title-container">
                <h2>
                    <?php the_field('header'); ?>
                </h2>
            </div>
            <div class="inner-text">

                    <?php the_content(); ?>

                <?php if ( get_field('add_custom_button') ) : ?>
                    <p>
                        <a href="<?php the_field('custom_button_url'); ?>" class="button">
                            <?php the_field('custom_button'); ?>
                        </a>
                    </p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
