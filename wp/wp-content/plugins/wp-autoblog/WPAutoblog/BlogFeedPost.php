<?php
class WPAutoblog_BlogFeedPost
{
    public static function postName()
    {
        return WPAB_ID . '_blog_feed_post';
    }

    public function init()
    {
        $this->initActions();
    }

    private function initActions()
    {
        add_action('init', array($this, 'registerPostType'));
        add_filter('get_shortlink', array($this, 'disablePermalink'), 10, 2);
        add_action('get_sample_permalink_html', array($this, 'disablePermalink'));
    }

    public function registerPostType()
    {
        register_post_type(self::postName(), array(
            'label'              => __('Blog Feed Posts', WPAB_ID),
            'labels'             => array(
                'name'           => __('Blog Feed Posts',                   WPAB_ID),
                'singluar_name'  => __('Blog Feed Post',                    WPAB_ID),
                'menu_name'      => __('Blog Feed Posts',                   WPAB_ID),
                'name_admin_bar' => __('Blog Feed Post',                    WPAB_ID),
                'all_items'      => __('Blog Feed Posts',                   WPAB_ID),
                'add_new'        => _x('Add new',                           self::postName()),
                'add_new_item'   => __('Add new Blog Feed Post',            WPAB_ID),
                'edit_item'      => __('Edit Blog Feed Post',               WPAB_ID),
                'new_item'       => __('New Blog Feed Post',                WPAB_ID),
                'view_item'      => __('View Blog Feed Post',               WPAB_ID),
                'search_items'   => __('Search Blog Feed Posts',            WPAB_ID),
                'not_found'      => __('No Blog Feed Posts found in Trash', WPAB_ID)
            ),
            'description'        => __('List of blog feed posts which will be used in autoblog', WPAB_ID),
            'public'             => true,
            // 'publicly_queryable' => false,
            'show_in_menu'       => WPAB_ID,
            // 'show_in_nav_menus'  => false,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-rss',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt')
        ));
        register_taxonomy_for_object_type('category', self::postName());
    }

    public function disablePermalink($shortlink = '', $id = '')
    {
        if((isset($_GET['post']) && $post = get_post($_GET['post'])) || (isset($_POST['post_id']) && ($post = get_post($_POST['post_id'])))) {
            if($post->post_type == self::postName()) {
                return '';
            }
        }
        return $shortlink;
    }

    public function prepareAttachment($image_url = null) {
        if (!$image_url) {
            return;
        }

        $upload_dir = wp_upload_dir();
        $image_data = file_get_contents($image_url);

        if (!$image_data) {
            return;
        }

        $wp_filetype = wp_check_filetype(basename($image_url), null );
        $filename = sha1($image_url) . '.' . $wp_filetype['ext'];
        if(wp_mkdir_p($upload_dir['path'])) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }
        file_put_contents($file, $image_data);

        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        return array('attachment' => $attachment, 'file' => $file);
    }
}

function get_wpab_blog_feed_post_post_type() {
    return WPAutoblog_BlogFeedPost::postName();
}
