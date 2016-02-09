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
     * Modals
     */

    var modals = new Modals();



    /**
     * Menu
     */

    var menu = $('menu');
    var menuSwitch = $('header .switch');
    menuSwitch.on('click', function()
    {
        if(menuSwitch.hasClass('open')) {
            modals.close();
        }

        menuSwitch.toggleClass('open');
        menu.toggleClass('open');
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