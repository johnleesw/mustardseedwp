<?php

Class Import_Page
{
    private $headers = array();

    private function err($err, $post_id) {
        echo '<br>Error while parsing row ' . get_field('title', $post_id) . ', at: <br>"';
        echo $err;
        wp_delete_post($post_id);
        die('"<br>terminating script...');
    }

    private function set_visible_columns ($post_id) {
        $field_key = 'field_55a7ae14bd011';
        $default_value = array('organization', 'company_website', 'action_areas', 'gics_sector', 'country', 'valuation_pre_money_gbp');
        update_field( $field_key, $default_value, $post_id );
    }

    private function remove_old( $title, $post_id ) {
        $old_post_id = post_exists($title);
        if ($old_post_id) {
            update_field('column_select', get_field('column_select', $old_post_id), $post_id);
            wp_delete_post($old_post_id);
            echo '<br>"' . $title . '" updated.';
        } else {
            $this->set_visible_columns($post_id);
            echo '<br>"' . $title . '" added.';
        }

    }

    private function set_content( $post_id ) {
        $post_data = array(
            'ID'           => $post_id,
            'post_title'   => get_field('title', $post_id),
            'post_content' => get_field('short_description', $post_id)
        );

        wp_update_post($post_data);
    }

    private function categorize( $post_id, $categories_str ) {
        $categories_arr = explode(',', $categories_str);
        foreach ( $categories_arr as $category ) {
            $categories_par = explode('>', $category);
            if ( ! isset($categories_par[0], $categories_par[1]) )
                $this->err($category, $post_id);

            $categories_par[0] = trim($categories_par[0]);
            $categories_par[1] = trim($categories_par[1]);
            $parent_term = term_exists($categories_par[0], 'venture_categories');

            if (!$parent_term) {
                $parent_term = wp_insert_term($categories_par[0], 'venture_categories');
            }

            $parent_term_id = intval($parent_term['term_id']);
            $term = term_exists($categories_par[1], 'venture_categories', $parent_term_id);

            if (!$term) {
                $term = wp_insert_term(
                    $categories_par[1],
                    'venture_categories',
                    array(
                        'parent'=> $parent_term_id
                    )
                );
            }

            if (is_wp_error($term))
                $this->err($category, $post_id);

            $term_id = intval($term['term_id']);
            wp_set_object_terms($post_id, $parent_term_id, 'venture_categories', true);
            wp_set_object_terms($post_id, $term_id, 'venture_categories',  true);
        }
    }

    private function add_post( $data ) {
        $post_param = array(
            'post_content'  => 'Venture post. Do not edit or remove!', // The full text of the post.
            'post_name'     => 'venture', // The name (slug) for your post
            'post_title'    => 'Venture post',
            'post_excerpt'  => 'Venture post',
            'post_type'     => 'venture',
            'post_status'   => 'publish'
        );

        $post_id = wp_insert_post( $post_param );

        // this loop takes lot of time
        $num = count($data);
        for ($c=0; $c < $num; $c++) {
            $data[$c] = mb_convert_encoding($data[$c], "UTF-8");
            update_field($this->headers[$c], $data[$c], $post_id);
        }

        $this->categorize($post_id, get_field('categories', $post_id));
        $this->remove_old(get_field('title',$post_id), $post_id);
        $this->set_content($post_id);
    }

    private function prepare_headers( $data ) {
        foreach ($data as $header) {
            $this->headers[] = str_replace('-', '_', sanitize_title($header));
        }
    }

    public function import_data()
    {
        $file = wp_upload_bits( $_FILES['csv-file']['name'], null, @file_get_contents( $_FILES['csv-file']['tmp_name'] ) );

        if ( true === $file['error'] ) {
            echo '<br>Import error!';
            exit;
        }

        $row = 1;
        if (($handle = fopen($file['file'], 'r')) !== false) {
            $data = fgetcsv($handle, 1000, ',');
            $this->prepare_headers($data);
            $row++;
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $row++;
                $this->add_post($data);
            }
            fclose($handle);
        }

        $row -= 2;
        echo "<br>$row rows saved to database.";
    }

    public function display_import_page() {
        if (isset($_POST['submit'])) {
            $this->import_data();
        }
        ?>
            <form enctype='multipart/form-data' action='admin.php?page=csv_import' method='post'>
                <p>
                    <label for='csv-file'>
                        Select File To Upload:
                    </label>
                    <input type='file' id='csv-file' name='csv-file' value='' />
                    <?php wp_nonce_field( plugin_basename( __FILE__ ), 'csv-nonce' ); ?>
                </p>
                <input type='submit' name='submit' value='Upload'>
            </form>
        <?php
    }
}