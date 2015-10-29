<?php

get_header();
the_post();
?>


    <div class="full-text">
        <div class="inner-title-container">
            <h2>
                <?php the_title(); ?>
            </h2>
            <br>
            <?php $img = get_field('publication_image');
            if( !empty($img) ) : ?>
                <img
                    <?php if($img['width'] > 225) echo 'width=225'; ?>
                    src="<?php echo $img['url']; ?>"
                    >
            <?php endif; ?>
        </div>
        <div class="inner-text">
            <?php the_content(); ?>
        </div>
    </div>


<?php
get_template_part( 'partials/additional-content' );
get_sidebar();
get_footer();
