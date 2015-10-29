
/************************
/* main
/************************/

$(document).ready(function(){
    $('#login-form form').submit(function(e){
        e.preventDefault();
        $('#form-close-button').click();

    });

    $(".three-columns-container .title.mobile").on("click", function(e){
        $(this).next().stop().slideToggle();
        $(this).next().toggleClass("active");
        if($(this).next().hasClass("active")){
            $(this).find(".toggle-open").hide();
            $(this).find(".toggle-close").show();
        }else{
            $(this).find(".toggle-open").show();
            $(this).find(".toggle-close").hide();
        }
    });

    $("#navbar .menu-toggle a").on("click", function(e){
        $(".mobile-menu-container").slideToggle();
        $(".mobile-menu-container").toggleClass("active");
        $(this).toggleClass("active");
    });


    // try {
    // 	var hash = window.location.hash;
    // 	if (hash) $(hash).click();

    // 	$('a[href*="#"]').click(function(e){
    // 		e.preventDefault();
    // 		var linkId = $(this).attr('href').replace('/', '');
    // 		$(linkId).click();
    // 	});
    // } catch(err){}

});



/************************
/* gallery
/************************/

var VIMEO_THUMB_API_URL = 'http://vimeo.com/api/v2/video/%id.json',
    YOUTUBE_THUMB_API_URL = 'http://img.youtube.com/vi/%id/hqdefault.jpg';

$(window).load(function(){
    $('.gallery-slideshow').cycle();
    $(".gallery-plugin").show();

    $('.gallery-slideshow').on('cycle-before', function(event, options, outgoing, incoming) {
        $(".caption.slideshow-caption p").fadeOut();
    });

    $('.gallery-slideshow').on('cycle-after', function(event, options, outgoing, incoming) {
        $(".caption.slideshow-caption p").stop().fadeIn();
    });

    //getThumbsForVideo();

});



/************************
/* utils
/************************/

function getURLParameter(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
    );
}

function arraySize(myArray) {
    var size = 0, key;
    for (key in myArray) {
        if (myArray.hasOwnProperty(key)) size++;
    }
    return size;
};

function toArray(strg){
    strg = strg.replace(/left|top/g,'0px');
    strg = strg.replace(/right|bottom/g,'100%');
    strg = strg.replace(/([0-9\.]+)(\s|\)|$)/g,"$1px$2");
    var res = strg.match(/(-?[0-9\.]+)(px|\%|em|pt)\s(-?[0-9\.]+)(px|\%|em|pt)/);
    return [parseFloat(res[1],10),res[2],parseFloat(res[3],10),res[4]];
}

var slug = function(str) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();

    // remove accents, swap ñ for n, etc
    var from = "ãàáäâéèéëêìíïîõòóöôùúüûñç·/_,:;";
    var to   = "aaaaaeeeeeiiiiooooouuuunc------";
    for (var i=0, l=from.length ; i<l ; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-'); // collapse dashes

    return str;
};

function browserVersion(){
    var browser = {
        chrome: false,
        mozilla: false,
        opera: false,
        msie: false,
        safari: false
    };
    var sUsrAg = navigator.userAgent;
    if(sUsrAg.indexOf("Chrome") > -1) {
        browser.chrome = true;
    } else if (sUsrAg.indexOf("Safari") > -1) {
        browser.safari = true;
    } else if (sUsrAg.indexOf("Opera") > -1) {
        browser.opera = true;
    } else if (sUsrAg.indexOf("Firefox") > -1) {
        browser.mozilla = true;
    } else if (sUsrAg.indexOf("MSIE") > -1) {
        browser.msie = true;
    }

    return browser;
}

