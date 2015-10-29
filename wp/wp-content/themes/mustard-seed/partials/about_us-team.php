<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 6/22/15
 * Time: 1:33 PM
 */
?>

<?php
if( have_rows('group') ):
    // loop through the rows of data
    while ( have_rows('group') ) : the_row(); ?>

        <?php the_sub_field('menu_marker'); ?>

        <div class="three-columns-container">
            <h1 class="title">
                <?php the_sub_field('group_name'); ?>
            </h1>
            <h1 class="title mobile">
                <span class="container-toggle toggle-open">+</span>
                <span class="container-toggle toggle-close">-</span>
                <?php the_sub_field('group_name'); ?>
            </h1>
            <div class="three-columns-inner-container">
                <div class="column-row">

                    <?php
                    // Hide and show OUR FELLOWS
                    $hideDesc = false;
                    if (trim(strip_tags(get_sub_field('group_name'))) == "Our Fellows" || trim(strip_tags(get_sub_field('group_name'))) == "The Team") {
                        $hideDesc = true;
                    }

                    if( have_rows('members') ):
                        // loop through the rows of data
                        while ( have_rows('members') ) : the_row(); ?>

                            <div class="column">
                                <div class="inner-column">
                                    <div class="image">
                                        <?php $img = get_sub_field('image'); ?>
                                        <?php if( !empty($img) ) : ?>
                                            <img alt="" src="<?php echo $img['sizes']['thumbnail']; ?>" >
                                        <?php endif; ?>
                                    </div>
                                    <div class="details">
                                        <div class="title-container">
                                            <h1>
                                                <?php the_sub_field('name'); ?>
                                            </h1>
                                            <h2>
                                                <?php the_sub_field('position'); ?>
                                            </h2>
                                        </div>
                                        <div align="center">
                                            <?php if ($hideDesc) { ?>
                                                <a href="#" class="js-readMoreButton">Read More</a>
                                                <p class="js-readMoreContent" style="display:none"><?php the_sub_field('description'); ?></p>
                                            <?php } else { ?>
                                                <p><?php the_sub_field('description'); ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile;
                    endif; ?>

                </div>
                <div class="column-row"></div>
            </div>
        </div>
        <br>
    <?php endwhile;
endif; ?>

<script>
    (function ($) {
        $(".js-readMoreButton").click(function (e) {
            e.preventDefault();
            $(this).parent().find(".js-readMoreContent").fadeToggle();
            var buttonText = $(this).text();
            if (buttonText === "Read More") {
                $(this).text("Close");
            } else {
                $(this).text("Read More");
            }
        });
    })(jQuery);
</script>