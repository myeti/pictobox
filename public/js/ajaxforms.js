function AjaxForms()
{
    var self = this;
    this.text = {
        error: 'Error !'
    };

    this.forms = $('form[data-ajax]');
    this.css = {
        button: 'fa',
        loading: 'fa-refresh',
        ok: 'fa-check'
    };


    /**
     * Submit form
     */
    this.submit = function(element)
    {
        var form = {
            redirect: element.attr('data-ajax'),
            action: element.attr('action'),
            method: element.attr('method'),
            button: {
                html: element.find('button[type=submit]'),
                text: element.find('button[type=submit]').html(),
                icon: $('<span></span>').addClass(self.css.button)
            }
        };

        form.button.icon.addClass(self.css.loading);
        form.button.html.empty().append(form.button.icon).attr('disabled');

        $.ajax({
            type: form.method,
            url: form.action,
            data: element.serialize()
        })
        .done(function(json)
        {
            self.done(json, form);
        })
        .fail(function(xhr, error)
        {
            self.fail(xhr, error, form);
        });
    };


    /**
     * Success
     *
     * @param json
     * @param form
     */
    this.done = function(json, form)
    {
        if(json.state == true) {
            form.button.icon.fadeOut(function () {
                // remove loading icon, show ok icon
                form.button.icon.removeClass(self.css.loading).addClass(self.css.ok).fadeIn(function () {
                    // redirect
                    if(json.redirect || form.redirect) {
                        window.redirect(json.redirect || form.redirect);
                    }
                    else {
                        // close modals if form is in
                        if(window.PictoboxUI) {
                            window.PictoboxUI.menu.close();
                        }
                        // show former text
                        setTimeout(function () {
                            form.button.icon.fadeOut(function () {
                                form.button.html.empty().html(form.button.text);
                            });
                        }, 2000);
                    }
                });
            });
        }
        else {
            form.button.html.empty().html(form.button.text);
            alert(json.message);
        }
    };


    /**
     * Failure
     *
     * @param xhr
     * @param error
     * @param form
     */
    this.fail = function(xhr, error, form)
    {
        form.button.html.empty().html(text);
        alert(self.text.error);
    };


    /**
     * Attach events
     */

    this.forms.on('submit', function(e)
    {
        e.stopPropagation();
        self.submit($(this));
        return false;
    });

}

$(function() {
    window.AjaxForms = new AjaxForms();
});