$(function() {

    /**
     * Photoswipe
     */

    var images = [];
    var triggers = $('a[data-slideshow]');
    var slideshow = document.querySelectorAll('.pswp')[0];

    triggers.each(function(index, element)
    {
        var trigger = $(element);
        var size = trigger.attr('data-slideshow').split('x');
        images.push({
            src: trigger.attr('href'),
            w: size[0],
            h: size[1]
        });
    });

    triggers.on('click', function(e)
    {
        e.preventDefault();

        var photoswipe = new PhotoSwipe(slideshow, PhotoSwipeUI_Default, images, {
            index: triggers.index(this)
        });

        photoswipe.init();

        return false;
    });



    /**
     * Upload
     */

    window.Dropzone.autoDiscover = false;
    if($('#upload').find('form').length) {

        var dropzone = new Dropzone("#upload form");
        dropzone.on('queuecomplete', function ()
        {
            setTimeout(function()
            {
                window.location.reload();
            }, 1000)
        });

        $(window).bind('beforeunload', function()
        {
            if(dropzone.getUploadingFiles().length > 0){
                return 'Si vous changez de page, l\'upload des photos en cours sera annulé.';
            }
        });
    }

});