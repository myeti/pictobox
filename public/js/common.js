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

    if(window.isMobile) {
        $(window).on('beforeunload', function ()
        {
            if(window.isLeaving) {
                $('#page-loader').addClass('active');
            }
        });
    }
    else {
        $('#page-loader').remove();
    }

});