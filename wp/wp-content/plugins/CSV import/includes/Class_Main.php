<?php

class Main
{
    protected $version;

    protected $loader;

    public $actions, $filters;

    public function __construct() {
        $this->version = '0.2.0';
        $this->load_dependencies();
        $this->define_hooks();
    }

    private function load_dependencies() {
        require_once plugin_dir_path( __FILE__ ) . 'Class_Loader.php';
        $this->loader = new Loader();
    }

    private function define_hooks() {
        require_once plugin_dir_path( __FILE__ ) . 'Class_Actions.php';
        $this->actions = new Actions();
        require_once plugin_dir_path( __FILE__ ) . 'Class_Filters.php';
        $this->filters = new Filters();

        $this->loader->add_action( 'admin_menu', $this->actions, 'add_to_menu' );

        $this->loader->add_action( 'init', $this->actions, 'create_venture_post_type' );

        $this->loader->add_action( 'init', $this->actions, 'create_venture_taxonomy' );
    }

    public function run() {
        $this->loader->run();
    }
}

