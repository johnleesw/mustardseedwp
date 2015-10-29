<?php

get_header();
the_post();
?>


    <div class="full-text">
        <div class="inner-title-container">
            <h1>
                <?php echo date("d M Y", strtotime(get_field("blog_date"))); ?>
            </h1>
            <h2>
                <?php the_title(); ?>
            </h2>
            <br>
            <?php $img = get_field('blog_image');
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
