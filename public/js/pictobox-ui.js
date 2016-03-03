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
         * Weither user is still logged in
         */
        ping: function()
        {
            if(self.routes.ping && self.routes.login) {
                $.get(self.routes.ping).done(function(json)
                {
                    if(json.state == false) {
                        window.redirect(self.routes.login);
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
            if(window.isMobile) {
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
        links: $('menu').find('a[data-panel]'),
        panels: $('menu').find('.panel'),
        controllers: {},

        /**
         * Toggle menu
         */
        toggle: function()
        {
            self.menu.container.toggleClass('open');
            self.menu.switcher.toggleClass('open');
            self.menu.panels.removeClass('open').find('form').trigger('reset');
            if(window.isMobile) {
                window.main.toggleClass('fixed');
            }
        },

        /**
         * Open specific panel
         */
        open: function(id)
        {
            self.menu.panels.removeClass('open');

            var panel = self.menu.panels.filter(id);
            panel.addClass('open');

            id = panel.attr('id');
            var controller = self.menu.controllers[id];
            if(typeof controller == 'function') {
                if(controller(panel) === true) {
                    self.menu.controllers[id] = null;
                }
            }
        },

        /**
         * Close all
         */
        close: function()
        {
            self.menu.container.removeClass('open');
            self.menu.switcher.removeClass('open');
            self.menu.panels.removeClass('open').find('form').trigger('reset');
            window.main.removeClass('fixed');
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

    this.menu.links.on('click', function(e)
    {
        var link = $(this);
        var id = link.attr('data-panel');
        self.menu.open(id);

        e.preventDefault();
        return false;
    });

    this.menu.panels.find('.cancel').on('click', function()
    {
        self.menu.close();
    });
}

$(function() {
    window.PictoboxUI = new PictoboxUI();
});