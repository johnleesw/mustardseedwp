<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 7/14/15
 * Time: 5:06 PM
 */
?>



<?php


if ( current_user_can( "Member" ) || current_user_can( "administrator" ) )
    get_template_part( 'partials/search' );
else
    get_template_part( 'partials/access_denied' );