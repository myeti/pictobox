$(function() {

    /**
     * Is mobile device
     */

    window.isMobile = (typeof window.orientation !== 'undefined');


    /**
     * Mark leaving user
     */

    window.isLeaving = false;
    $('a').on('click', function()
    {
        window.isLeaving = true;
    });


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


    /**
     * Page loader
     */

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
        $(window).on('beforeunload', function ()
        {
            if(window.isLeaving) {
                window.loader.show();
            }
        });
    }

});