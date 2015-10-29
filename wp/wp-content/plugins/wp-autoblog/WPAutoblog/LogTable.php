<?php
require_once 'Log.php';

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WPAutoblog_LogTable extends WP_List_Table
{
    public function __construct(){
        global $status, $page;

        //Set parent defaults
        parent::__construct( array(
            'singular'  => __('log', WPAB_ID),
            'plural'    => __('logs', WPAB_ID),
            'ajax'      => false
        ) );
    }

    public function column_message($item)
    {
        return sprintf('<strong>%s</strong><div class="row-actions">%s</div>', $item->message, $item->exception_message);;
    }

    public function column_date($item)
    {
        $time = strtotime($item->thrown_at);
        $time_diff = time() - $time;
        $t_time = date('Y/m/d g:i:s A', $time);

        if ( $time_diff > 0 && $time_diff < DAY_IN_SECONDS )
            $h_time = sprintf( __( '%s ago' ), human_time_diff( $time ) );
        else
            $h_time = date('Y/m/d', $time);

        return sprintf('<abbr = title="%s">%s</abbr>', $t_time, $h_time);
    }

    public function get_columns(){
        $columns = array(
            'message' => 'Message',
            'date'    => 'Date'
        );
        return $columns;
    }

    public function get_sortable_columns() {
        return array(
            'date' => array('date',true)
        );
    }

    public function prepare_items()
    {
        $per_page = 20;
        $current_page = $this->get_pagenum();
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $total_items = WPAutoblog_Log::getLogsCount();

        $this->items = WPAutoblog_Log::getLogs($per_page, ($current_page - 1) * $per_page);

        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items/$per_page)
        ) );
    }

}
