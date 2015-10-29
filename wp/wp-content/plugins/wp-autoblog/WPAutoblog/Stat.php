<?php
require_once 'StatTable.php';
class WPAutoblog_Stat
{
    public static function tableName()
    {
        global $wpdb;
        return $wpdb->prefix . WPAB_ID . '_stats';
    }

    public static function install()
    {
        global $wpdb;
        $tableName = self::tableName();

        $sql = "CREATE TABLE {$tableName} (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `date` date NOT NULL,
            `posts_count` smallint(6) NOT NULL DEFAULT '0',
            UNIQUE KEY `date` (`date`),
            PRIMARY KEY (`id`)
        )";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function incrementPostsCount($date, $incrementBy = 1)
    {
        global $wpdb;
        $tableName = self::tableName();

        $sql = "INSERT INTO {$tableName} VALUE (NULL, '" . $date . "', '" .  $incrementBy . "')
            ON DUPLICATE KEY UPDATE posts_count = posts_count + " . $incrementBy . ";";

        return $wpdb->query($sql);
    }

    public static function getStats($from = null, $to = null)
    {
        global $wpdb;

        if (!$from) {
            $from = date('Y-m-01');
        }

        if (!$to) {
            $to = date('Y-m-t');
        }

        return ($wpdb->get_results(
            $wpdb->prepare(
                'SELECT * FROM ' . self::tableName() .
                ' WHERE date >= %s AND date <= %s
                ORDER BY date DESC LIMIT %d OFFSET %d',
                $from,
                $to,
                100,
                0
            )
        ));
    }

    public static function displayMenu()
    {
        $statTable = new WPAutoblog_StatTable();

        $from = null;
        $to = null;

        if (isset($_GET['from']) && isset($_GET['to'])) {
            $from = date($_GET['from']);
            $to = date($_GET['to']);
        }

        $statTable->prepare_items($from, $to);

        $chartData = '';

        foreach (array_reverse($statTable->get_stats()) as $item) {
            $chartData .= sprintf("['%s', %d],", $item->date, $item->posts_count);
        }

        $chartData = rtrim($chartData, ',');

        echo "<script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>
            <script type=\"text/javascript\">
              google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});
              google.setOnLoadCallback(drawChart);
              function drawChart() {
                var data = google.visualization.arrayToDataTable([
                  ['Date', 'Posts'],
                  " . $chartData . "
                ]);

                var options = {
                  title: 'Fetched posts by date',
                  legend: 'none',
                  backgroundColor: { fill: 'transparent' }

                };

                var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                chart.draw(data, options);
              }
            </script>";
        echo '<script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>';
        echo '<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/cupertino/jquery-ui.css">';
        echo '<style>.datepicker { background-color: #ffffff !important; cursor: pointer; }</style>';
        echo "<script>
            (function ($) {
                $(document).ready(function () {
                    var from = $('.datepicker-from').val();
                    var to = $('.datepicker-to').val();

                    if (from.length && to.length) {
                        $('.submit').prop('disabled', false);
                    }

                    $('.datepicker-from').datepicker({
                        dateFormat: 'yy/mm/dd',
                        maxDate: 0,
                        onClose: function(selectedDate) {
                            $('.datepicker-to').datepicker('option', 'minDate', selectedDate);
                        }
                    });
                    $('.datepicker-to').datepicker({
                        dateFormat: 'yy/mm/dd',
                        maxDate: 0,
                        onClose: function(selectedDate) {
                            $('.datepicker-from').datepicker('option', 'maxDate', selectedDate);
                        }
                    });
                    $('input').change(function () {
                        var from = $('.datepicker-from').val();
                        var to = $('.datepicker-to').val();

                        if (from.length && to.length) {
                            $('.submit').prop('disabled', false);
                        } else {
                            $('.submit').prop('disabled', true);
                        }
                    });
                });
            }(jQuery));
        </script>";
        echo '<div class="wrap">';
        echo '<h2>' . __('Stats', WPAB_ID) . '</h2>';
        echo 'Posts between selected dates: ' . $statTable->get_stats_count();
        echo '<div style="margin-top: 30px;"><form method="get">';
        echo '<label for="from">From <input type="text" class="datepicker datepicker-from" name="from" id="from" value="' . (isset($_GET['from']) ? $_GET['from'] : '') . '" readonly="readonly" /></label>';
        echo '<label for="to" style="margin-left: 20px;">To <input class="datepicker datepicker-to" type="text" name="to" id="to" value="' . (isset($_GET['to']) ? $_GET['to'] : '') . '" readonly="readonly" ></label>';
        echo '<input type="submit" disabled="disabled" name="show" id="show" class="submit button button-primary button-large" style="margin-left: 20px;" value="Show" accesskey="p">';
        echo '<input type="hidden" name="page" value="' . WPAB_ID . '-stats"' . " />";
        echo '</form></div>';
        $statTable->display();
        if ($statTable->get_stats_count()) {
            echo '<div id="chart_div" style="width: 900px; height: 500px;"></div>';
        }
    }
}
