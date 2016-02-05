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
        var self = $(this);

        $('header, menu, main').addClass('blur');
        modals.removeClass('open');

        var id = self.attr('data-modal');
        var modal = modals.filter(id);
        modal.addClass('open');
        overlay.addClass('open');

        if(self.is('[data-autofocus]')) {
            modal.find('input').first().focus();
        }

        if(self.is('[data-callback]')) {
            var callback = window[self.attr('data-callback')];
            if(typeof callback == 'function') {
                callback(self.attr('href'));
            }
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

    var userSelect = $('#user-switch');
    if(userSelect.length) {
        var userRank = $('#user-rank');
        var userEmail = $('#user-email');
        userSelect.on('change', function () {
            var selected = userSelect.find('option[selected]');
            userRank.val(selected.attr('data-rank'));
            userEmail.val(selected.attr('data-email'));
        });
    }

});