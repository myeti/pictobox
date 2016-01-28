$(function() {

    /**
     * Menu
     */

    var menu = $('menu');
    menu.find('.switch').on('click', function()
    {
        menu.toggleClass('open');
        menu.find('.form').removeClass('open');
    });
    menu.find('.form .header').on('click', function()
    {
        var form = $(this).parents('.form');
        menu.find('.form').not(form).removeClass('open');
        form.toggleClass('open');
    });


    /**
     * User form
     */
    var userSelect = $('#user-id');
    var userEmail = $('#user-email');
    userSelect.on('change', function()
    {
         var selected = userSelect.find('option[selected]');
        userEmail.val(selected.attr('data-email'));
    });

});