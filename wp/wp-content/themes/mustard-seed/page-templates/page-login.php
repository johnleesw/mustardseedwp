<?php
/**
 * Template Name: Login
 *
 * @package WordPress
 * @subpackage WPized_Light
 */

$args = array(
    'redirect' => get_permalink(get_page_by_title('Protected')),
    'id_username' => 'user',
    'id_password' => 'pass',
);

?>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />


<div class="form-container">
    <div class="form form--tiny" data-width="small">

        <div class="form-fields-container">
            <div class="form-fields-row">
                <span id="form-close-button" class="glyphicon glyphicon-remove" onclick="top.submitAndClose()"></span>

                <div class="form-fields">
                    <h1> Login </h1>

                    <form name="loginform" id="loginform" action="wp-login.php" method="post" target="_blank">
                        <?php wp_login_form($args); ?>
                    </form>
                </div>

            </div>
        </div>

        <div id="extra-info-container" class="extra-info-container">
            <div id="extra-info" class="extra-info">
                <p>Are you new? Learn how you can join.</p>
                <div id="buttons" class="extra-info-buttons">

                        <a class="learn-more" href="<?php the_field('entrepreneur_url') ?>" target="_blank">
                            <?php the_field('entrepreneur') ?>
                        </a>

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
