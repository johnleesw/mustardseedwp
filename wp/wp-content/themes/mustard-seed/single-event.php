<?php

get_header();
the_post();
?>


<div class="full-text">
    <div class="inner-title-container">
        <h1>
            <?php echo date("M d", strtotime(get_field("event_date"))); ?>
        </h1>
        <h2>
            <?php the_title(); ?>
        </h2>
        <br>
        <?php $img = get_field('event_image');
        if( !empty($img) ) : ?>
            <img
                <?php if($img['width'] > 225) echo 'width=225'; ?>
                src="<?php echo $img['url']; ?>"
                >
        <?php endif; ?>
    </div>
    <div class="inner-text">
        <?php the_field("event_location"); ?>
        <?php the_content(); ?>
    </div>
</div>


<?php
get_template_part( 'partials/additional-content' );
get_template_part( 'partials/additional-form' );
get_template_part( 'partials/about_us-team' );
?><br><br><br><br><?php
get_template_part( 'partials/additional-content2' );
get_template_part( 'partials/logos' );
get_sidebar();
get_footer();
