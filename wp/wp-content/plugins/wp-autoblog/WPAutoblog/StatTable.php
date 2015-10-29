<?php
require_once 'Stat.php';

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WPAutoblog_StatTable extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct(array(
            'singular'  => __('stat', WPAB_ID),
            'plural'    => __('stats', WPAB_ID),
            'ajax'      => false
        ));
    }

    public function column_date($item)
    {
        return $item->date;
    }

    public function column_posts_count($item)
    {
        return $item->posts_count;
    }

    public function get_columns()
    {
        $columns = array(
            'date' => 'Date',
            'posts_count' => 'Fetched posts'
        );
        return $columns;
    }


    public function prepare_items($from = null, $to = null)
    {
        $columns = $this->get_columns();
        $this->_column_headers = array($columns, array(), array());

        $this->items = WPAutoblog_Stat::getStats($from ?: null, $to ?: null);
    }

    public function get_stats_count()
    {
        return array_sum(array_map(function ($item) {
            return $item->posts_count;
        }, $this->items));
    }

    public function get_stats()
    {
        return $this->items;
    }
}
