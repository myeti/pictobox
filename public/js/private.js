$(function()
{

    /**
     * Admin profil form
     */

    window.PictoboxUI.menu.controllers.profile = function(modal)
    {
        var userSelect = modal.find('#user-switch');
        if(userSelect.length) {
            var userName = modal.find('#user-name');
            var userRank = modal.find('#user-rank');
            var userEmail = modal.find('#user-email');
            userSelect.on('change', function () {
                var selected = userSelect.find('option:selected');
                userRank.val(selected.attr('data-rank'));
                userEmail.val(selected.attr('data-email'));
                userName.val(selected.attr('data-username'));
            });
        }

        return true;
    };



    /**
     * BeLazy image loading
     */

    window.beLazy = new Blazy();

});