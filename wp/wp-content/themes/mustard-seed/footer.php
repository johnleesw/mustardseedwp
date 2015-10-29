<?php
/**
 * WPized Light: Footer
 *
 * Remember to always include the wp_footer() call before the </body> tag
 *
 * @package WordPress
 * @subpackage WPized_Light
 */

$footer_id = get_page_by_path('footer')->ID;
?>

<!--warpper close-->
</div>

<div id="footer">
    <div class="container">
        <div class="inner-container">
            <div class="top-footer"><span class="partners"></span></div>
            <div class="bottom-footer">
                <div class="footer-brand">
                    <a href="#"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/images/footer-logo.png"></a>
                </div>
                <div class="left-bottom-footer">
                    <span class="plugin_link">
                        <a href="mailto:info@mustardseedimpact.com" target="_blank">
                            <?php
                            the_field('e_mail', $footer_id); ?>
                        </a>
                    </span>
                </div>
                <div class="right-bottom-footer">
                    <div class="social">

                        <?php if( have_rows('icons', $footer_id) ) :
                            while ( have_rows('icons', $footer_id) ) : the_row();
                                ?>
                                <div class="filer_image">
                                    <a href="<?php the_sub_field('link'); ?>" class="filer_image_link">
                                        <img alt="Twitter" src="<?php the_sub_field('icon'); ?>">
                                    </a>
                                </div>
                        <?php endwhile;
                        endif; ?>

                    </div>
                </div>
                <div class="copyright">
                    <div class="text">
                        <p>
                            <?php the_field('copyright', $footer_id) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--<script src="/static/site/js/jquery/jquery.1.9.js"></script>-->
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/jquery.min.js"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/main.js"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/flickity.pkgd.min.js"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/chosen.jquery.min.js"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/mustard_script.js"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/jquery.colorbox-min.js"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/popup.js"></script>

<?php
// do not remove
wp_footer();
?>

</body>
</html>
