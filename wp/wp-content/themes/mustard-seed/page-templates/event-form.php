<?php
/**
 * Template Name: Event form
 *
 * @package WordPress
 * @subpackage WPized_Light
 */

//get_header();
the_post();
the_field('custom_title');
?>

    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />


    <div class="form-container">
        <div class="form form--big">


            <div class="form-info">
                <div class="form-info-content">

                    <p>
                        <?php the_field('side_info'); ?>
                    </p>


                    <div class="addresses">

                <span class="plugin_link">
                    <a href="mailto:info@mustardseedimpact.com" target="_blank">
                        <?php the_field('email'); ?>
                    </a>
                </span>
                        <p class="social"></p>
                    </div>
                </div>
            </div>

            <div class="form-fields-container">
                <div class="form-fields-row">
                    <span id="form-close-button" class="glyphicon glyphicon-remove" onclick="top.submitAndClose()"></span>

                    <div class="form-fields">
                        <h1>
                            <?php if (isset($_GET['title']))
                                echo $_GET['title']; ?>
                        </h1>

                        <!-- form here -->
                        <?php the_content(); ?>
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

<?php
get_template_part( 'partials/additional-content' );

//get_sidebar();
//get_footer();
