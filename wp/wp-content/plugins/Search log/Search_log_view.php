<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 7/21/15
 * Time: 6:01 PM
 */

function view () {
    global $wpdb;
    $post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'search_log'");
    if (isset($_POST['submit'])) {
        wp_delete_post( $post_id, true );
        $post_param = array(
            'post_content'  => 'Search log', // The full text of the post.
            'post_name'     => 'search_log', // The name (slug) for your post
            'post_title'    => 'Search log',
            'post_excerpt'  => 'Search log',
            'post_status'   => 'private'
        );
        wp_insert_post( $post_param );
    }
    ?>

    <form enctype='multipart/form-data' action='admin.php?page=search_log' method='post'>
        <input type='submit' name='submit' value='Reset Search Log'>
    </form>

    <?php
    echo '<h1>Search log:</h1>';
    $meta_data = get_post_meta($post_id);
    arsort($meta_data);
    echo '<table border="1"> <tr><th>Search query</th><th>Counter</th></tr>';
    foreach ($meta_data as $key => $val) {
        echo '<tr>';
        if ($key == ' ')
            echo '<td>empty search</td><td>' . $val[0] . '</td>';
        else if ($key != 'views')
            echo '<td>' . $key . '</td><td>' . $val[0] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
}