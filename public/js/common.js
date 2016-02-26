$(function() {

    /**
     * Is mobile device
     */

    window.isMobile = (typeof window.orientation !== 'undefined');


    /**
     * Page loader
     */

    window.isLeaving = false;
    window.loader = {
        html: $('#page-loader'),
        show: function()
        {
            $('body').addClass('fixed');
            window.loader.html.addClass('active');
        },
        hide: function()
        {
            $('body').removeClass('fixed');
            window.loader.html.removeClass('active');
        }
    };

    if(window.isMobile) {

        // listen all outgoing link
        $('a').on('click', function()
        {
            var href = $(this).attr('href');
            if(href && href != '#') {
                window.isLeaving = true;
            }
        });

        // display loader when leaving
        $(window).on('beforeunload', function()
        {
            if(window.isLeaving) {
                window.loader.show();
            }
        });

    }


    /**
     * Redirect function
     */

    window.redirect = function(to)
    {
        window.isLeaving = true;
        window.location.replace(to);
    };


    /**
     * Reload function
     */

    window.reload = function()
    {
        window.isLeaving = true;
        window.location.reload();
    };

});