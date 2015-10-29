<?php
require_once 'LogTable.php';
class WPAutoblog_Log
{
    static public function tableName()
    {
        global $wpdb;
        return $wpdb->prefix . WPAB_ID . '_logs';
    }

    static public function install()
    {
        global $wpdb;
        $table_name = self::tableName();

        $sql = "CREATE TABLE {$table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            message text NOT NULL,
            exception_message text NOT NULL,
            thrown_at DATETIME,
            PRIMARY KEY  id (id));";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    static public function log($exception, $message)
    {
        global $wpdb;
        if(is_object($exception)) {
            if(get_class($exception) == 'WP_Error') {
                $exception = $exception->get_error_message();
            } else if(get_class($exception) == 'Exception' || is_subclass_of($exception, 'Exception')) {
                $exception = $exception->getMessage();
            } else {
                $exception = 'Unknown exception';
            }
        } else if (!is_string($exception)) {
            $exception = 'Unknown exception';
        }
        $sql = $wpdb->prepare('INSERT INTO ' . self::tableName() . ' VALUES (NULL, \'%s\', \'%s\', \'%s\')', $message, $exception, date('Y-m-d H:i:s', time()));
        $wpdb->query($sql);
    }

    static public function getLogs($limit, $offset)
    {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . self::tableName() . ' ORDER BY id DESC LIMIT %d OFFSET %d', $limit, $offset));
    }

    static public function getLogsCount()
    {
        global $wpdb;
        return $wpdb->get_var('SELECT COUNT(*) FROM ' . self::tableName());
    }

    static public function displayMenu()
    {
        $logTable = new WPAutoblog_LogTable();;
        $logTable->prepare_items();

        echo '<div class="wrap">';
        echo '<h2>' . __('Logs', WPAB_ID) . '</h2>';
        $logTable->display();
        echo '<style type="text/css">#the-list .row-actions {visibility: visible;height:0;overflow:hidden;transition:all 0.5s;}#the-list tr:hover .row-actions {height:20px;transition:all 0.5s;}</style>';
    }
}
