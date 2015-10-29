<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 7/15/15
 * Time: 4:37 PM
 */


if(!empty($_POST["category"])) {
    $categories = explode('###', $_POST["category"]);
    foreach ($categories as $category) {
        $cat = explode('#', $category);
        echo '<option value="' . $cat[0] . '">' . $cat[1] . '</option>';
    }
}