function PictoboxUI()
{
    var self = this;
    this.body = $('body');
    this.routes = {};
    this.text = {};


    /**
     * User manager
     */
    this.user = {

        /**
         * Is mobile device
         */
        mobile: function()
        {
            return typeof window.orientation !== 'undefined';
        },

        /**
         * Weither user is still logged in
         */
        ping: function()
        {
            if(self.routes.ping && self.routes.login) {
                $.get(self.routes.ping).done(function(json)
                {
                    if(json.state == false) {
                        window.location.replace(self.routes.login);
                    }
                });
            }
        }
    };


    /**
     * Confirm links
     */
    this.confirm = {
        links: $('a[data-confirm]'),
        action: function(link)
        {
            if(self.user.mobile()) {
                var message = link.attr('data-confirm');
                return confirm(message);
            }
        }
    };


    /**
     * Menu manager
     */
    this.menu = {

        switcher: $('header .switch'),
        container: $('menu'),

        /**
         * Toggle menu
         */
        toggle: function()
        {
            if(self.menu.switcher.hasClass('open')) {
                self.modals.close();
            }
            self.menu.container.toggleClass('open');
            self.menu.switcher.toggleClass('open');
        }
    };


    /**
     * Modals manager
     */
    this.modals = {

        overlay: $('#modals'),
        list: $('#modals').find('.modal'),
        links: $('a[data-modal]'),
        controller: {},

        /**
         * Open specific modal
         * @param id
         */
        open: function(id)
        {

            self.modals.list.removeClass('open');
            self.modals.links.removeClass('open');

            self.body.addClass('fixed');
            self.modals.overlay.addClass('open');

            var modal = self.modals.list.filter(id);
            modal.addClass('open');

            id = modal.attr('id');
            var controller = self.modals.controller[id];
            if(typeof controller == 'function') {
                if(controller(modal) === true) {
                    self.modals.controller[id] = null;
                }
            }
        },

        /**
         * Close all modals
         */
        close: function()
        {
            self.modals.overlay.removeClass('open');
            self.modals.list.removeClass('open');
            self.modals.links.removeClass('open');
            self.body.removeClass('fixed');
        }
    };


    /**
     * Attach interval
     */

    setInterval(function() {
        self.user.ping();
    }, 5000);


    /**
     * Attach events
     */

    this.confirm.links.on('click', function()
    {
        return self.confirm.action($(this));
    });

    this.menu.switcher.on('click', function()
    {
        self.menu.toggle();
    });

    this.modals.links.on('click', function(e)
    {
        var link = $(this);
        var id = link.attr('data-modal');

        self.modals.open(id);
        link.addClass('open');

        e.preventDefault();
        return false;
    });

    this.modals.list.find('.cancel').on('click', function()
    {
        self.modals.close();
        self.menu.toggle();
    });
}

$(function() {
    window.PictoboxUI = new PictoboxUI();
});