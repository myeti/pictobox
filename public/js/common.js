$(function() {

    /**
     * Ajax form
     */
    $('form[data-ajax]').on('submit', function(e)
    {
        e.stopPropagation();

        var classLoading = 'glyphicon-option-horizontal';
        var classOk = 'glyphicon-ok';

        var form = $(this);
        var button = form.find('button[type=submit]');
        var text = button.html();
        var redirect = form.attr('data-ajax');

        var icon = $('<span></span>')
            .addClass('glyphicon')
            .addClass(classLoading);

        button.empty().append(icon).attr('disabled');

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize()
        })
        .done(function(json)
        {
            if(json.state == true) {
                icon.fadeOut(function(){
                    icon.removeClass(classLoading).addClass(classOk).fadeIn();
                })
                if(json.redirect) {
                    redirect = json.redirect;
                }
                if(redirect) {
                    window.location.replace(redirect);
                }

                setTimeout(function() {
                    icon.fadeOut(function() {
                        button.empty().html(text);
                    });
                }, 2000);
            }
            else {
                button.empty().html(text);
                alert(json.message);
            }
        })
        .fail(function(xhr, error)
        {
            button.empty().html(text);
            alert('Erreur');
        });

        return false;
    });

});