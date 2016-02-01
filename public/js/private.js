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
        var modal = modals.filter(id);
        modal.addClass('open');
        overlay.addClass('open');

        if($(this).is('[data-autofocus]')) {
            modal.find('input').first().focus();
        }

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


    /**
     * Current grid
     */
    var grids = $('.grid');
    grids.each(function(index, element)
    {
        var grid = $(element);
        $(window).on('load scroll', function()
        {
            var scroll = $(this).scrollTop();
            if(grid.position().top <= scroll) {
                grids.removeClass('affix');
                grid.addClass('affix');
            }
        });
    });

});