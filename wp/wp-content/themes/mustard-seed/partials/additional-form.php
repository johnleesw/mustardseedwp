<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 8/4/15
 * Time: 11:32 AM
 */
?>

<?php if (get_field('add_form')) : ?>
    <div class="inner-container">
        <div class="full-text">
            <div class="inner-title-container">
                <h2>
                    <?php the_field('form_header'); ?>
                </h2>
            </div>
            <div class="inner-text">
                <?php the_field('form_text'); ?>
                <?php if (get_field('add_form')) : ?>
                    <a href="<?php the_field('form_url'); ?>/?title=<?php the_field('form_title'); ?>" class="button-colorbox">
                        <?php the_field('form_link_text'); ?>
                    </a>
                <?php endif; ?>
                <?php the_field('form_text_after'); ?>
            </div>
        </div>
    </div>
<?php endif; ?>