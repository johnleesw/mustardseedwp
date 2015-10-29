<?php
/**
 * WPized Light: Header
 *
 * Contains dummy HTML to show the default structure of WordPress header file
 *
 * Remember to always include the wp_head() call before the ending </head> tag
 *
 * Make sure that you include the <!DOCTYPE html> in the same line as ?> closing tag
 * otherwise ajax might not work properly
 *
 * @package WordPress
 * @subpackage WPized_Light
 */



?><!DOCTYPE html>
<!--[if IE 8]> <html class="no-js ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <meta name="description" content="<?php bloginfo( 'description' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="page-Enter" content="revealtrans(duration=0.0)">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta property="og:site_name" content="Mustard Seed">
    <meta property="og:title" content="Mustard Seed">
    <meta property="og:image" content="http://www.mustardseedimpact.com/static/site/images/og_logo.jpg">
    <meta property="og:url" content="http://www.mustardseedimpact.com/">
    <meta property="og:description" content="">

    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
    <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/flickity.css" type="text/css" />
    <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/colorbox.css" type="text/css" />
    <link rel="icon" type="image/ico" href="<?php bloginfo( 'stylesheet_directory' ); ?>/images/favicon.png">

    <!-- optional but recommended -->
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <!-- /optional -->

    <!--[if lt IE 9]>
        <?php wp_enqueue_script( 'HTML5', 'http://html5shiv.googlecode.com/svn/trunk/html5.js' ); ?>
        <?php wp_enqueue_script( 'library', get_template_directory_uri() . '/js/library.js' ); ?>
    <![endif]-->

    <script src="<?php echo get_template_directory_uri(); ?>/js/modernizr.min.js"></script>

    <script src="//google-analytics.com/ga.js" async="" type="text/javascript"></script>
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-43366133-26']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();
    </script>
    <?php wp_head(); ?>
  </head>

  <body>

  <div id="login-modal" class="fade modal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
              <div class="modal-body"><span id="form-close-button" class="glyphicon glyphicon-remove close" data-dismiss="modal" onclick="top.submitAndClose()"></span>
                  <div class="form-fields-container">
                      <div id="login-form" class="form-fields">
                          <h1>Login</h1>
                          <div id="errors">
                              <p><span class="glyphicon glyphicon-remove-circle"></span></p>
                          </div>
                          <form action "."="" method="POST">
                          <p>
                              <input id="id_name" name="email" placeholder="Email" type="text">
                          </p>
                          <p>
                              <input id="id_password" name="password" placeholder="Password" type="password">
                          </p>
                          <p class="submit-button">
                              <input id="form-submit-button" value="ENTER MUSTARD SEED" type="submit">
                          </p>
                          </form>
                      </div>
                  </div>
                  <div id="extra-info-container">
                      <div id="extra-info">
                          <p>Are you new? Learn how you can join.</p>
                          <p id="buttons"><a class="learn-more" href="/entrepreneurs/">ENTREPRENEUR</a><a class="learn-more" href="/membership/">INVESTOR</a></p>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <div class="mobile-check"></div>
  <div id="wrapper">
      <div id="navbar">
          <div class="container">
              <div class="inner-container">
                  <div id="logo">
                      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/top-logo.png"></a>
                  </div>

                      <?php

                      $menu_args = array(
                          'container'       => 'div',
                          'container_id'    => 'nav-menu',
                          'menu'            => 'ul',
                          'fallback_cb'     => 'wp_page_menu',
                          'items_wrap'      => '<li>%3$s</li>',
                      );
                      wp_nav_menu( $menu_args );
                      ?>

                  <div class="menu-toggle">
                      <a class="" href="#" >
                      </a>
                  </div>

              </div>
          </div>
          <div id="mobile-menu-container" style="display: none;" class="mobile-menu-container">

              <?php
              $menu_args = array(
                  'container'       => 'div',
                  'container_id'    => 'mobile-nav-menu',
                  'echo'            => true,
                  'fallback_cb'     => 'wp_page_menu',
                  'items_wrap'      => '<li class="child sibling">%3$s</li>',
              );
              wp_nav_menu( $menu_args );
              ?>

          </div>

      </div>

      <?php if ( get_field('place_upper_table') ) : ?>

          <div id="container" class="container-fluid">
              <div class="inner-container">

                  <div class="page_header orientation-<?php
                      if (!get_field('use_vertical_layout')) { echo "left"; }
                      else { echo "top"; } ?>
                  colour-mustard-yellow">
                      <div class="inner-page-header">

                          <div class="page-header-image">
                              <div class="image-container">
                                  <div class="image-container-cell">
                                      <?php $upper_img = get_field('upper_table_image');
                                      if( !empty($upper_img) ) : ?>
                                          <img
                                                  alt="<?php echo $upper_img['title'] ?>"
                                                  src="<?php echo $upper_img['url'] ?>"
                                              >
                                      <?php endif; ?>
                                  </div>
                              </div>
                          </div>

                          <div class="details">
                              <div class="inner-details">
                                  <div class="inner-details-cell">
                                      <h1 class="page-header-title">
                                          <?php the_field('upper_table_header') ?>
                                      </h1>
                                      <div class="page-header-details">
                                          <p>
                                              <?php the_field('upper_table_content') ?>
                                          </p>
                                      </div>
                                      <?php if (get_field('place_upper_table_button')) : ?>
                                          <button class="button place-left button-colorbox" href="<?php the_field('upper_table_button_url'); ?>">
                                              <?php the_field('upper_table_button_text') ?>
                                          </button>
                                      <?php endif; ?>

                                  </div>
                              </div>
                          </div>

                      </div>

                  </div>

              </div>
          </div>
      <?php endif; ?>
