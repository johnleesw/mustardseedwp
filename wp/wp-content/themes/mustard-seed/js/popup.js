/**
 * Created by witoldklimczak on 8/26/15.
 */


$(document).ready(function() {

    $(".button-colorbox").colorbox({
        iframe:true,
        width:"80%",
        height:"80%",
        transition:"elastic",
        scrolling: false
    });

    $("button.button-colorbox-login").colorbox({
        iframe:true,
        width:"350px",
        height:"448px",
        transition:"elastic",
        scrolling: false
    });

});
