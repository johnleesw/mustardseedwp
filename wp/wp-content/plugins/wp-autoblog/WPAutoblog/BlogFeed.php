<?php
require_once 'Readibility.php';
require_once 'BlogFeedPost.php';
require_once 'Stat.php';
require_once ABSPATH . WPINC . '/class-simplepie.php';

class WPAutoblog_BlogFeed
{
    public static function postName()
    {
        return WPAB_ID . '_blog_feed';
    }

    public function init()
    {
        $this->initActions();
        $this->readability = new WPAutoblog_Readibility();
    }

    private function initActions()
    {
        add_action('init', array($this, 'registerPostType'));
        add_action('shutdown', array($this, 'fetchPosts'), 9999);
        add_action('save_post', array($this, 'savePostType'));
        add_filter('get_shortlink', array($this, 'disablePermalink'), 10, 2);
        add_action('get_sample_permalink_html', array($this, 'disablePermalink'));
    }

    public function registerPostType()
    {
        register_post_type(self::postName(), array(
            'label'                => __('Blog Feeds', WPAB_ID),
            'labels'               => array(
                'name'             => __('Blog Feeds',                   WPAB_ID),
                'singluar_name'    => __('Blog Feed',                    WPAB_ID),
                'menu_name'        => __('Blog Feeds',                   WPAB_ID),
                'name_admin_bar'   => __('Blog Feed',                    WPAB_ID),
                'all_items'        => __('Blog Feeds',                   WPAB_ID),
                'add_new'          => _x('Add new',                      self::postName()),
                'add_new_item'     => __('Add new Blog Feed',            WPAB_ID),
                'edit_item'        => __('Edit Blog Feed',               WPAB_ID),
                'new_item'         => __('New Blog Feed',                WPAB_ID),
                'view_item'        => __('View Blog Feed',               WPAB_ID),
                'search_items'     => __('Search Blog Feeds',            WPAB_ID),
                'not_found'        => __('No Blog Feeds found in Trash', WPAB_ID)
            ),
            'description'          => __('List of blog feeds which will be used as a base of autoblog', WPAB_ID),
            'public'               => true,
            'publicly_queryable'   => false,
            'show_in_menu'         => WPAB_ID,
            'show_in_nav_menus'    => false,
            'menu_position'        => 5,
            'menu_icon'            => 'dashicons-rss',
            'supports'             => array('title', 'custom-fields'),
            'register_meta_box_cb' => array($this, 'arrangeMetaBoxes')

        ));
        register_taxonomy_for_object_type('category', self::postName());
    }

    public function savePostType($post_id)
    {
        if ( !isset($_POST['post_type']) || self::postName() != $_POST['post_type'] ) {
            return;
        }

        update_post_meta($post_id, WPAB_ID . '-url', $_POST[self::postName() . '_url']);
        update_post_meta($post_id, WPAB_ID . '-feed', $_POST[self::postName() . '_feed']);
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

    public function arrangeMetaBoxes()
    {
        //remove_meta_box('submitdiv', self::postName(), 'side');
        remove_meta_box('slugdiv', self::postName(), 'normal');
        remove_meta_box('postcustom', self::postName(), 'normal');

        add_meta_box(self::postName() . '_url', 'Website Address', array($this, 'drawMetaBox'), self::postName(), 'normal', 'high');
        add_meta_box(self::postName() . '_feed', 'Feed Address', array($this, 'drawFeedMetaBox'), self::postName(), 'normal', 'high');
    }

    public function drawMetaBox()
    {
        global $post;
        $urlField = self::postName() . '_url';

        echo '<input type="hidden" name="' . $urlField . '_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
        echo '<input type="text" name="' . $urlField . '" id="' . $urlField . '" style="width: 100%" value="' . get_post_meta($post->ID, WPAB_ID . '-url', true) . '"/>';
        echo '<p>Example: <code>http://techcrunch.com/</code> &mdash; don’t forget the <code>http://</code></p>';
        echo '<style type="text/css">#minor-publishing { display: none; }</style>';
    }

    public function drawFeedMetaBox()
    {
        global $post;
        $urlField = self::postName() . '_feed';

        echo '<input type="hidden" name="' . $urlField . '_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
        echo '<input type="text" name="' . $urlField . '" id="' . $urlField . '" style="width: 100%" value="' . get_post_meta($post->ID, WPAB_ID . '-feed', true) . '"/>';
        echo '<p>It will be used in favor of feed found on the page</p>';
        echo '<style type="text/css">#minor-publishing { display: none; }</style>';
    }

    public function fetchPosts()
    {
        global $_wp_additional_image_sizes;
        global $wpdb;

        $lastRefreshTime = (int)get_option('wpab_last_refresh_time', 0);
        $fetchPeriod = (int)get_option('wpab_fetch_feed_period', 30) * MINUTE_IN_SECONDS;

        if ((time() - $lastRefreshTime) < $fetchPeriod) {
            return;
        }

        WPAutoblog_Log::log(null, 'Started updating feeds');

        set_time_limit($fetchPeriod);
        update_option('wpab_last_refresh_time', time());

        $body = null;
        $blogs = get_posts(array(
            'post_type' => self::postName(),
            'posts_per_page' => -1
        ));
        $cache_ids = array();
        foreach ($blogs as $k => $blog) {
            $url = get_post_meta($blog->ID, WPAB_ID . '-feed', true) ?: get_post_meta($blog->ID, WPAB_ID . '-url', true);

            $feed = new SimplePie();
            $feed->set_feed_url($url);
            $feed->set_cache_location('../cache/');
            $feed->set_cache_duration($fetchPeriod);
            $feed->set_timeout(15);

            $result = $feed->init();
            $feed->handle_content_type();

            if (!$result) {
                WPAutoblog_Log::log($feed->error(), 'An error occured while fetching the feed: <i>' . $blog->post_title . '</i>');
                continue;
            }

            $items = $feed->get_items();
            foreach ($items as $item) {
                if (!in_array($item->get_id(), $cache_ids)) {
                    $posts = get_posts(array(
                        'hierarchical' => false,
                        'meta_key' => WPAB_ID . '-uuid',
                        'meta_value' => $item->get_id(),
                        'post_type' => WPAutoblog_BlogFeedPost::postName()
                    ));

                    if (count($posts) === 0) {
                        $images = $this->extractImages($item->get_content());
                        $attachments = array();

                        $isFeaturedImageFeatured = get_field('featured_image_featured', $blog->ID);
                        $isFirstImageFeatured = get_field('first_image_featured', $blog->ID);
                        $featuredImageFetched = false;

                        if ($isFeaturedImageFeatured) {
                            $enclosure = $item->get_enclosure();
                            $featuredImageUrl = $enclosure->get_link();

                            if ($featuredImageUrl) {
                                $attachments[] = WPAutoblog_BlogFeedPost::prepareAttachment($featuredImageUrl);
                                $featuredImageFetched = true;
                            }
                        }

                        if ($isFirstImageFeatured && count($images) && !$featuredImageFetched) {
                            $attachments[] = WPAutoblog_BlogFeedPost::prepareAttachment($images[0]);
                        }

                        if ($this->readability->isAvailable()) {
                            $body = $this->readability->parse($item->get_permalink());
                        }

                        $cache_ids[] = $item->get_id();
                        $post_id = $this->createBlogPost($blog, $item, $body, $attachments);
                        $this->assignCategories($post_id, $blog->ID);
                        do_action(WPAB_ID . '_blog_post_created_from_feed', $post_id, $item, $attachments);

                        WPAutoblog_Stat::incrementPostsCount($item->get_date('Y/m/d'));
                    }
                }
            }
        }

        $postsToSweep = $wpdb->get_results(
            'SELECT post_id FROM `wp_postmeta`
            WHERE meta_key =  "' . WPAB_ID . '-uuid"
            GROUP BY meta_value
            HAVING COUNT(*) > 1
            LIMIT 0, 100',
            ARRAY_A
         );

        $postsToSweepIds = array_map(function ($item) {
            return $item['post_id'];
        }, $postsToSweep);

        WPAutoblog_Sweeper::remove($postsToSweepIds);

        WPAutoblog_Log::log($feed, 'Finished updating feeds');
    }

    private function createBlogPost($blog, $item, $body, $attachments)
    {
        $attachment_ids = array();
        $body = $body ? $body : $item->get_content();
        $post = array(
            'post_content' => $body,
            'post_name'    => sanitize_title($item->get_title()),
            'post_type'    => WPAutoblog_BlogFeedPost::postName(),
            'post_title'   => $item->get_title(),
            'post_status'  => 'publish',
            'post_excerpt' => strip_tags($item->get_description()),
            'post_date'    => $item->get_date('Y-m-d H:i:s'),
            'post_parent'  => $blog->ID
        );
        $post_id = wp_insert_post($post);
        add_post_meta($post_id, WPAB_ID . '-uuid', $item->get_id());
        add_post_meta($post_id, WPAB_ID . '-from-blog', $blog->ID);
        add_post_meta($post_id, WPAB_ID . '-original-url', $item->get_permalink());

        /* assign attachments */
        foreach ($attachments as $attachment) {
            $attach_id = wp_insert_attachment( $attachment['attachment'], $attachment['file'], $post_id );
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata( $attach_id, $attachment['file'] );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            $attachment_ids[] = $attach_id;
        }

        $isFeaturedImageFeatured = get_field('featured_image_featured', $blog->ID);
        $isFirstImageFeatured = get_field('first_image_featured', $blog->ID);

        if (($isFirstImageFeatured || $isFeaturedImageFeatured) && isset($attachment_ids[0])) {
            set_post_thumbnail( $post_id, $attachment_ids[0] );
        }

        return $post_id;
    }

    private function assignCategories($post_id, $blog_id)
    {
        $blogCategories = wp_get_post_categories( $blog_id );
        wp_set_post_categories($post_id, $blogCategories, true);
    }

    private function extractImages($html)
    {
        $imageLinks = array();
        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $dom->preserveWhiteSpace = false;
        $images = $dom->getElementsByTagName('img');
        foreach ($images as $image) {
            $imageLinks[] = $image->getAttribute('src');
        }
        return $imageLinks;
    }

    public function isAvailable()
    {
        return $this->enabled;
    }

    public function enable()
    {
        $this->enabled = true;
    }

    public function addSettings()
    {
        if((int)get_option('wpab_fetch_feed_period', 0) < 1) {
            update_option('wpab_fetch_feed_period', 30);
        }
        register_setting( 'wpab-settings', 'wpab_fetch_feed_period' );
    }

    public function displaySettings()
    {
        echo '<h3 class="title">' . __('Feeds', 'wp-autoblog') . '</h3>';
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th><label for="wpab_fetch_feed_period">Fetch feed every</label></th>';
        echo '<td><input id="wpab_fetch_feed_period" class="regular-text code" type="text" value="' . get_option('wpab_fetch_feed_period') . '" name="wpab_fetch_feed_period" size="3" style="width: auto;" /> minutes</td>';
        echo '</tr>';
        echo '</table>';
    }
}

function get_wpab_blog_feed_post_type() {
    return WPAutoblog_BlogFeed::postName();
}
