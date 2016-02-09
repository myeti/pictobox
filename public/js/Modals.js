function Modals()
{
    var self = this;

    this.overlay = $('#modals');
    this.modals = this.overlay.find('.modal');
    this.links = $('a[data-modal]');


    /**
     * Open specific modal
     * @param id
     */
    this.open = function(id)
    {
        var modal = self.modals.filter(id);

        self.modals.removeClass('open');
        self.links.removeClass('open');

        self.overlay.addClass('open');
        modal.addClass('open');

        /*
        if(modal.is('[data-autofocus]')) {
            modal.find('input').first().focus();
        }

        if(modal.is('[data-callback]')) {
            var callback = window[modal.attr('data-callback')];
            if(typeof callback == 'function') {
                callback(modal);
            }
        }
        */
    };


    /**
     * Close modals
     */
    this.close = function()
    {
        self.overlay.removeClass('open');
        self.modals.removeClass('open');
        self.links.removeClass('open');
    };


    /**
     * Attach events
     */

    this.links.on('click', function(e)
    {
        e.preventDefault();

        var link = $(this);
        var id = link.attr('data-modal');

        self.open(id);
        link.addClass('open');

        return false;
    });

    this.modals.find('.cancel').on('click', function()
    {
        self.close();
    });
}