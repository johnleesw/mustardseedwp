<?php
/*
Plugin Name: WordPress AutoBlog
Plugin URI: http://github.com/ajgon/wp-autoblog
Description: This plugin will fetch given blog feeds and import them as posts, which can be used in the blog (autoblog).
Version: 0.1
Author: Igor Rzegocki
Author URI: http://www.rzegocki.pl/

License: This plugin is licensed under MIT.
*/
define('WPAB_ID', 'wpab');
require('WPAutoblog.php');

WPAutoblog::init();

register_activation_hook( __FILE__, array('WPAutoblog', 'install'));
