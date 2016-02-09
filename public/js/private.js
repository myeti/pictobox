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
     * Menu and Modals
     */

    var menu = new MenuModals();



    /**
     * User admin form
     */

    var userSelect = $('#user-switch')
    if(userSelect.length) {
        var userName = $('#user-name');
        var userRank = $('#user-rank');
        var userEmail = $('#user-email');
        userSelect.on('change', function () {
            var selected = userSelect.find('option:selected');
            console.log(selected);
            userRank.val(selected.attr('data-rank'));
            userEmail.val(selected.attr('data-email'));
            userName.val(selected.attr('data-username'));
        });
    }


    /**
     * Mobile confirm
     */

    if(typeof window.orientation !== 'undefined') {
        $('a[data-confirm]').on('click', function()
        {
            var message = $(this).attr('data-confirm');
            return confirm(message);
        });
    }

});