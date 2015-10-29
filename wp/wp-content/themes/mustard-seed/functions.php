<?php
/**
 * WPized Light: Theme specific functionalities
 *
 * Do not close any of the php files included with ?> closing tag!
 *
 * @package WordPress
 * @subpackage WPized_Light
 */
define( 'WP_LIGHT', 'wp_light' ); // used in translation strings
define( 'WP_LIGHT_VERSION', 1.5 );

function wp_light_load_features() {

	$features = scandir( dirname( __FILE__ ) . '/features/' );

	foreach ( $features as $feature ) {

		if ( current_theme_supports( $feature ) ) {
			require_once dirname( __FILE__ ) . '/features/' . $feature . '/' . $feature . '.php';
		}
	}
}

add_action( 'init', 'wp_light_load_features' );

add_theme_support( 'seo-title' );
add_theme_support( 'threaded-comments' );
add_theme_support( 'comments' );

// add two navigation menus
add_theme_support( 'menus', array(
	'navigation-top' => __( 'Top Navigation Menu' ),
	'navigation-foot' => __( 'Footer Navigation Menu' ),
) );

// add 3 default sidebars
add_theme_support( 'sidebars', array(
	array(),
	array(),
	array(),
) );

add_theme_support( 'images', array(
	'400x500' => array(
	'width' => '400',
	'height' => '500',
	'crop' => true,
	),
) );

add_theme_support( 'cpt', array(
	// team post
	'wp-light-team' => array(
	'singular' => 'Team Member',
	'plural' => 'Team Members',
	'rewrite' => array( 'slug' => 'team', 'with_front' => true, 'publicly_queryable' => true ),
	),
) );

add_theme_support( 'custom-tax', array(
	// taxonomy like category
	'wp-light-team-tag' => array(
	'singular' => 'Member Category',
	'plural' => 'Member Categories',
	'rewrite' => array( 'slug' => 'category', 'with_front' => false ),
	'posts' => array( 'wp-light-team' ),
	),
) );

add_theme_support( 'settings', array(
	'opt1' => array(
	'type' => 'text',
	'name' => 'fb',
	'desc' => 'Facebook link',
	),
	'opt2' => array(
	'type' => 'dropdown_pages',
	'name' => 'dropdown-pages',
	'desc' => 'Testing dropdown pages',
	),
	'opt3' => array(
	'type' => 'wp_editor',
	'name' => 'wp-editor',
	'desc' => 'Testing WP Editor',
	),
) );

function fix_url($url) {
    return (substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://') ? $url : 'http://'.$url;
}


// add hook
add_filter( 'wp_nav_menu_objects', 'my_wp_nav_menu_objects_sub_menu', 10, 2 );

// filter_hook function to react on sub_menu flag
function my_wp_nav_menu_objects_sub_menu( $sorted_menu_items, $args ) {

    if ( isset( $args->sub_menu ) ) {
        $root_id = 0;

        // find the current menu item
        foreach ( $sorted_menu_items as $menu_item ) {
            if ( $menu_item->current ) {
                // set the root id based on whether the current menu item has a parent or not
                $root_id = ( $menu_item->menu_item_parent ) ? $menu_item->menu_item_parent : $menu_item->ID;
                break;
            }
        }

        // find the top level parent
        if ( ! isset( $args->direct_parent ) ) {
            $prev_root_id = $root_id;
            while ( $prev_root_id != 0 ) {
                foreach ( $sorted_menu_items as $menu_item ) {
                    if ( $menu_item->ID == $prev_root_id ) {
                        $prev_root_id = $menu_item->menu_item_parent;
                        // don't set the root_id to 0 if we've reached the top of the menu
                        if ( $prev_root_id != 0 ) $root_id = $menu_item->menu_item_parent;
                        break;
                    }
                }
            }
        }

        $menu_item_parents = array();
        foreach ( $sorted_menu_items as $key => $item ) {
            // init menu_item_parents
            if ( $item->ID == $root_id ) $menu_item_parents[] = $item->ID;

            if ( in_array( $item->menu_item_parent, $menu_item_parents ) ) {
                // part of sub-tree: keep!
                $menu_item_parents[] = $item->ID;
            } else if ( ! ( isset( $args->show_parent ) && in_array( $item->ID, $menu_item_parents ) ) ) {
                // not part of sub-tree: away with it!
                unset( $sorted_menu_items[$key] );
            }
        }

        return $sorted_menu_items;
    } else {
        return $sorted_menu_items;
    }
}

add_action( 'init', 'create_post_event_type' );
function create_post_event_type() {
    register_post_type( 'Event',
        array(
            'labels' => array(
                'name' => __( 'Events' ),
                'singular_name' => __( 'Event' ),
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array( 'title', 'editor', 'revisions' ),
        )
    );
}

add_action( 'init', 'create_post_press_type' );
function create_post_press_type() {
    register_post_type( 'Press',
        array(
            'labels' => array(
                'name' => __( 'Press' ),
                'singular_name' => __( 'Press post' ),
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array( 'title', 'editor', 'revisions' ),
        )
    );
}

add_action( 'init', 'create_post_blog_type' );
function create_post_blog_type() {
    register_post_type( 'Blog',
        array(
            'labels' => array(
                'name' => __( 'Blog' ),
                'singular_name' => __( 'Blog post' ),
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array( 'title', 'editor', 'revisions' ),
        )
    );
}

add_action( 'init', 'create_post_publication_type' );
function create_post_publication_type() {
    register_post_type( 'Publication',
        array(
            'labels' => array(
                'name' => __( 'Publications' ),
                'singular_name' => __( 'Publication' ),
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array( 'title', 'editor', 'revisions' ),
        )
    );
}

add_action( 'init', 'add_roles' );
function add_roles() {
    add_role( 'Member', 'Member' );
}

remove_filter ('acf_the_content', 'wpautop');

add_action('wp_footer', 'add_google_analytics');
function add_google_analytics() { ?>

<!--    UA-64729047-1-->

<?php }



/**
 * Extend WordPress search to include custom fields
 *
 * http://adambalee.com
 */

/**
 * Join posts and postmeta tables
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
 */
function cf_search_join( $join ) {
    global $wpdb;

    if ( is_search() ) {
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' as post_meta_ACF ON '. $wpdb->posts . '.ID = post_meta_ACF.post_id ';
    }

    return $join;
}
add_filter('posts_join', 'cf_search_join' );

/**
 * Modify the search query with posts_where
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */
function cf_search_where( $where ) {
    global $pagenow, $wpdb;

    if ( is_search() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (post_meta_ACF.meta_value LIKE $1)", $where );
    }

    return $where;
}
add_filter( 'posts_where', 'cf_search_where' );

/**
 * Prevent duplicates
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
 */
function cf_search_distinct( $where ) {
    global $wpdb;

    if ( is_search() ) {
        return "DISTINCT";
    }

    return $where;
}
add_filter( 'posts_distinct', 'cf_search_distinct' );


// empty search fix
add_filter( 'request', 'my_request_filter' );
function my_request_filter( $query_vars ) {
    if( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) {
        $query_vars['s'] = " ";
    }
    return $query_vars;
}

function my_post_count_queries( $query ) {
    if (!is_admin() && $query->is_main_query()){
        if(is_home()){
            $query->set('posts_per_page', 1);
        }
    }
}

add_action( 'pre_get_posts', 'my_post_count_queries' );

function add_views_to_post( $post_id ) {

    if ( wp_is_post_revision( $post_id ) )
        return;

    add_post_meta($post_id, 'views', '0', false);
}
add_action( 'save_post', 'add_views_to_post' );

remove_filter ('the_content', 'wpautop');


// change <p></p> to <br> fix
function content_add_br ($content) {
    $content = str_replace('<p>', '', $content);
    $content = str_replace('</p>', '<br>', $content);
    return $content;
}
add_filter('the_content', 'content_add_br');

function my_acf_load_value( $value, $post_id, $field )
{
    // run the_content filter on all textarea values
    $value = apply_filters('the_content',$value);
    return $value;
}
// acf/load_value - filter for every value load
add_filter('acf/format_value/type=wysiwyg', 'my_acf_load_value', 10, 3);


// fix - admin bar hidden for normal users
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

// fix - logouts redirects to homepage
add_action('wp_logout','go_home');
function go_home(){
    wp_redirect( home_url() );
    exit();
}

// fix - remove main text edit from Footer edit page
add_action( 'admin_init', 'hide_editor' );
function hide_editor() {
    if( !isset($_GET['post']) || isset($_POST['post_ID']) ) return;
    $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
    $pagename = get_the_title($post_id);
    if($pagename == 'Footer') {
        remove_post_type_support('page', 'editor');
    }
}


// Own styles to login page
function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_template_directory_uri() . '/css/login.css' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );


// Add more items to nav_menu
add_filter( 'wp_nav_menu_items', 'your_custom_menu_item', 10, 2 );
function your_custom_menu_item ( $items) {

    $search_link_format = '';
    $logout_link_format = '';
    if ( current_user_can( "Member" ) || current_user_can( "administrator" ) ) {
        $search_link = get_page_by_path('protected')->guid;
        $logout_link = wp_logout_url( );
        $search_link_format = '<li><a href="'. $search_link .'">Search Tool</a></li>';
        $logout_link_format = '<li><a href="'. $logout_link .'">Logout</a></li>';
    }

    $items .= $search_link_format . $logout_link_format;

    return $items;
}
