/* ==========================================================================

    Project: Mustard Seed
    Author: XHTMLized
    Last updated: Wed Jun 24 2015 13:56:24

========================================================================== */

$.getScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js", function () {} );

(function($) {

    'use strict';

    var MustardSeed = {

        /**
        * Init Function
        */
        init: function() {
            MustardSeed.pluginsInit();
            MustardSeed.loadMore();
            MustardSeed.equalizeColumns();
        },

        pluginsInit: function() {
            $('.custom-select').chosen({
                disable_search_threshold: 10,
                width: '100%'
            });
        },

        loadMore: function() {
            $('[data-load-more]').on('click', function (event) {
                event.preventDefault();

                var target = $($(this).data('load-more'));
                target.show();

                $(this).hide();
            });
        },

        equalizeColumns: function() {
            if (Modernizr.mq('(min-width: 768px)')) {
                $('[data-equalize]').each(function () {
                    var target = $(this).data('equalize');
                    var maxHeight = 0;

                    $(this).find(target).each(function () {
                        if ($(this).outerHeight() > maxHeight) {
                            maxHeight = $(this).outerHeight();
                        }
                    }).height(maxHeight);
                });
            }
        }

    };

    $(function() {
        MustardSeed.init();
    });

})(jQuery);
