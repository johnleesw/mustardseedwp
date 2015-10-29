<?php

class WPAutoblog_Sweeper
{
    private $enabled;

    public function sweep()
    {
        $lastSweepTime = (int)get_option('wpab_last_sweep_time', 0);
        $sweepPeriod = DAY_IN_SECONDS;

        if ((time() - $lastSweepTime) < $sweepPeriod) {
            return;
        }

        update_option('wpab_last_sweep_time', time());
        set_time_limit(3600);

        $posts = $this->getPostsToRemove();

        foreach ($posts as $post) {
            $this->removeAttachments($post);
            wp_delete_post($post->ID, true);
        }

        $this->sweepLogs();
    }

    public static function remove($postsIds)
    {
        foreach ($postsIds as $post) {
            $attachments = get_posts(array(
                'post_type' => 'attachment',
                'posts_per_page' => -1,
                'post_parent' => $post,
            ));

            foreach ($attachments as $attachment) {
                wp_delete_attachment($attachment->ID, true);
            }

            wp_delete_post($post, true);
        }
    }

    private function getPostsToRemove()
    {
        $cats = get_categories(array(
            'orderby' => 'term_order',
            'hide_empty' => false,
            'title_li' => ''
        ));

        $excludedCategories = '';

        foreach ($cats as $cat) {
            $optionName = 'category_form_purge_posts_' . $cat->term_id;
            $optionValue = get_option($optionName);

            if ($optionValue == '1') {
                $excludedCategories .= '-' . $cat->term_id . ',';
            }
        }

        $excludedCategories = rtrim($excludedCategories, ',');

        add_filter('posts_where', array($this, 'postDateFilter'));

        $posts = get_posts(array(
            'post_type' => get_wpab_blog_feed_post_post_type(),
            'posts_per_page' => -1,
            'post_status'     => 'publish',
            'suppress_filters' => false,
            'cat' => $excludedCategories
        ));

        remove_filter('posts_where', array($this, 'postDateFilter'));

        return $posts;
    }

    public function postDateFilter($where = '')
    {
        $sweepPeriod = get_option('wpab_sweep_period');
        $where .= ' AND post_date < "'. date('Y-m-d', strtotime('-' . $sweepPeriod . ' days')) . '"';

        return $where;
    }

    private function removeAttachments($post)
    {
        $attachments = get_posts(array(
            'post_type' => 'attachment',
            'posts_per_page' => -1,
            'post_parent' => $post->ID,
        ));

        foreach ($attachments as $attachment) {
            wp_delete_attachment($attachment->ID, true);
        }
    }

    public function enable()
    {
        $this->enabled = true;
    }

    public function addSettings()
    {
        if ((int)get_option('wpab_sweep_period', 0) < 7) {
            update_option('wpab_sweep_period', 7);
        }

        register_setting('wpab-settings', 'wpab_sweep_period');
    }

    public function displaySettings()
    {
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th><label for="wpab_sweep_period">Sweep posts older than</label></th>';
        echo '<td><input id="wpab_sweep_period" class="regular-text code" type="text" value="' . get_option('wpab_sweep_period') . '" name="wpab_sweep_period" size="3" style="width: auto;" /> days</td>';
        echo '</tr>';
        echo '</table>';
    }

    private function sweepLogs()
    {
        global $wpdb;

        $sweepPeriod = get_option('wpab_sweep_period');
        $logsTableName = WPAutoblog_Log::tableName();

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM $logsTableName WHERE thrown_at < DATE_SUB(NOW(), INTERVAL %d DAY)",
                $sweepPeriod
            )
        );
    }
}
