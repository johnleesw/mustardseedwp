<?php
/*
Plugin Name: Search log
Author:      Witold Klimczak
*/

class search_log
{

    public function plugin_init() {
        $post_param = array(
            'post_content'  => 'Search log', // The full text of the post.
            'post_name'     => 'search_log', // The name (slug) for your post
            'post_title'    => 'Search log',
            'post_excerpt'  => 'Search log',
            'post_status'   => 'private'
        );
        wp_insert_post( $post_param );
    }

    public function search_filter($query) {
        global $wpdb;
        if ($query->is_search) {
            $meta_key = $query->query['s'];
            $post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'search_log'");
            if ($post_id) {
                $meta_value = get_post_meta($post_id, $meta_key, true);
                if ($meta_value) {
                    $meta_value++;
                    update_post_meta($post_id, $meta_key, $meta_value);
                }
                else {
                    $meta_value = 1;
                    add_post_meta($post_id, $meta_key, $meta_value, true);
                }
            }
            remove_action('get_header', array($this, 'add_record'));
        }
        return $query;
    }

    public function add_to_menu() {
        require_once plugin_dir_path( __FILE__ ) . 'Search_log_view.php';
        $import_class = new Import_Page();
        add_menu_page('Search log', 'Search log', 'manage_options', 'search_log', 'view');
    }
}

$search_log = new search_log();

register_activation_hook( __FILE__, array($search_log, 'plugin_init') );
add_action('pre_get_posts',array($search_log, 'search_filter') );
add_action( 'admin_menu', array($search_log, 'add_to_menu') );
