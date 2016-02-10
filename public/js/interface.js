$(function()
{

    /**
     * Admin profil form
     */

    window.PictoboxUI.modals.controller.profile = function(modal)
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
     * Dropzone
     */

    if(window.Dropzone) {
        window.Dropzone.autoDiscover = false;
    }

    window.PictoboxUI.modals.controller.upload = function()
    {
        var dropzone = new Dropzone("#upload form");
        dropzone.on('queuecomplete', function()
        {
            setTimeout(function()
            {
                window.location.reload();
            }, 1000)
        });

        $(window).on('beforeunload', function()
        {
            if(dropzone.getUploadingFiles().length > 0){
                return 'Si vous changez de page, l\'upload des photos en cours sera annulé.';
            }
        });

        return true;
    };


    /**
     * Photoswipe
     */

    var thumbnails = $('a[data-thumbnail]');
    if(thumbnails.length) {
        var images = [];
        var slideshow = document.querySelectorAll('.pswp')[0];

        thumbnails.each(function(index, element)
        {
            var trigger = $(element);
            var size = trigger.attr('data-thumbnail').split('x');
            images.push({
                src: trigger.attr('href'),
                w: size[0],
                h: size[1]
            });
        });

        thumbnails.on('click', function(e)
        {
            var photoswipe = new PhotoSwipe(slideshow, PhotoSwipeUI_Default, images, {
                index: thumbnails.index(this)
            });
            photoswipe.init();

            e.preventDefault();
            return false;
        });

        return true;
    }

});