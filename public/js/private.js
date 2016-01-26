$(function() {

    /**
     * Menu
     */

    var menu = $('header menu');
    menu.find('.switch').on('click', function()
    {
        menu.toggleClass('active');
    });
    menu.find('.form .header').on('click', function()
    {
        var form = $(this).parents('.form');
        menu.find('.form').not(form).removeClass('open');
        form.toggleClass('open');
    });

});