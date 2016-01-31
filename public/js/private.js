$(function() {

    /**
     * User ping
     */
    setInterval(function()
    {
        $.get(routes.ping).done(function(json)
        {
            if(json.state == false) {
                window.location.replace(routes.login);
            }
        });
    }, 5000);

    /**
     * Menu
     */

    var menu = $('menu');
    menu.find('.switch').on('click', function()
    {
        menu.toggleClass('open');
    });


    /**
     * Modals
     */
    var overlay = $('#modals');
    var modals = overlay.find('.modal');
    $('a[data-modal]').on('click', function(e)
    {
        e.preventDefault();

        $('header, menu, main').addClass('blur');
        modals.removeClass('open');

        var id = $(this).attr('data-modal');
        modals.filter(id).addClass('open');
        overlay.addClass('open');

        return false;
    });
    modals.find('.cancel').on('click', function()
    {
        $('header, menu, main').removeClass('blur');
        overlay.removeClass('open');
        modals.removeClass('open');
    });


    /**
     * User admin form
     */
    var userSelect = $('#user-id');
    var userEmail = $('#user-email');
    userSelect.on('change', function()
    {
        var selected = userSelect.find('option[selected]');
        userEmail.val(selected.attr('data-email'));
    });

});