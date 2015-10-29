<?php
require_once 'WPAutoblog/Admin.php';
require_once 'WPAutoblog/BlogFeed.php';
require_once 'WPAutoblog/BlogFeedPost.php';
require_once 'WPAutoblog/Readibility.php';
require_once 'WPAutoblog/Log.php';
require_once 'WPAutoblog/Stat.php';
require_once 'WPAutoblog/Sweeper.php';

class WPAutoblog
{
    static public function init()
    {
        global $wpabAdmin;
        global $wpabBlogFeed;
        global $wpabBlogFeedPost;
        global $wpabSweeper;

        $wpabAdmin = new WPAutoblog_Admin();
        $wpabAdmin->init();

        $wpabBlogFeed = new WPAutoblog_BlogFeed();
        $wpabBlogFeed->init();

        $wpabBlogFeedPost = new WPAutoblog_BlogFeedPost();
        $wpabBlogFeedPost->init();

        $wpabSweeper = new WPAutoblog_Sweeper();

        $wpabAdmin->registerComponent($wpabBlogFeed);
        $wpabAdmin->registerComponent($wpabSweeper);

        //
        //$wpabAdmin->registerComponent(new WPAutoblog_Readibility());
    }

    static public function install()
    {
        WPAutoblog_Log::install();
        WPAutoblog_Stat::install();
    }
}
