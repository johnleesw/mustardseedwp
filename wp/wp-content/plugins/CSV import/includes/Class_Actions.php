<?php

class Actions
{
    public function __construct() {}

    public function add_to_menu() {
        require_once plugin_dir_path( __FILE__ ) . 'Class_Import_Page.php';
        $import_class = new Import_Page();
        add_menu_page('CSV Import', 'CSV Import', 'manage_options', 'csv_import', array($import_class, 'display_import_page'));
    }

    public function create_venture_post_type() {
        register_post_type(
            'venture',
            array(
                'labels' => array(
                    'name' => __( 'Ventures' ),
                    'singular_name' => __( 'Venture' )
                ),
                'public' => true,
                'has_archive' => true,
                'taxonomies' => array('venture_categories')
            )
        );
    }

    public function create_venture_taxonomy() {

        register_taxonomy(
            'venture_categories',
            'venture',
            array(
                'label' => __( 'All categories' ),
                'rewrite' => array( 'slug' => 'category' ),
                'hierarchical' => true,
                'update_count_callback' => '_update_post_term_count',
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'hierarchical' => true
            )
        );
        register_taxonomy_for_object_type('venture_categories', 'venture');

    }
}