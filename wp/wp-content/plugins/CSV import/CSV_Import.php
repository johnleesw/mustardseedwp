<?php
/*
Plugin Name: CSV Import
Author:      Witold Klimczak
*/

require_once plugin_dir_path( __FILE__ ) . 'includes/Class_Main.php';

$main = new Main();
$main -> run();