$(function()
{


    /**
     * Dropzone
     */

    window.Dropzone.autoDiscover = false;
    window.PictoboxUI.menu.controllers.upload = function()
    {
        var dropzone = new Dropzone("#upload form", {
            acceptedFiles: 'image/jpeg, .jpg, .jpeg, .JPG, .JPEG'
        });

        dropzone.on('queuecomplete', function()
        {
            setTimeout(function()
            {
                window.reload();
            }, 1000)
        });

        $(window).on('beforeunload', function()
        {
            if(dropzone.getUploadingFiles().length > 0){
                return window.PictoboxUI.text.leaveUpload;
            }
        });

        return true;
    };



    /**
     * Photoswipe
     */

    var thumbnails = $('a[data-thumbnail]');
    var slidelist = [];
    var slideshow = document.querySelectorAll('.pswp')[0];

    // make slideshow list
    thumbnails.each(function(index, element)
    {
        var trigger = $(element);
        var size = trigger.attr('data-thumbnail').split('x');
        slidelist.push({
            src: trigger.attr('href'),
            title: trigger.attr('data-caption'),
            album: trigger.attr('data-album'),
            author: trigger.attr('data-author'),
            filename: trigger.attr('data-filename'),
            w: size[0],
            h: size[1]
        });
    });

    // open slideshow
    thumbnails.on('click', function(e)
    {
        window.Photoswipe = new PhotoSwipe(slideshow, PhotoSwipeUI_Default, slidelist, {
            index: thumbnails.index(this)
        });

        window.Photoswipe.init();

        window.isLeaving = false;
        e.preventDefault();
        return false;
    });

    // report picture
    $('a[data-report]').on('click', function(e)
    {
        e.preventDefault();

        var url = $(this).attr('data-report');
        var confirmMsg = $(this).attr('data-report-confirm');
        var doneMsg = $(this).attr('data-report-done');

        if(confirm(confirmMsg)) {
            $.post(url, {picture: window.Photoswipe.currItem.src}).complete(function()
            {
                alert(doneMsg);
                window.Photoswipe.close();
            });
        }

        return false;
    });

    // rotate picture
    $('a[data-rotate]').on('click', function(e)
    {
        e.preventDefault();

        window.loader.show();

        var url = $(this).attr('data-rotate');
        var pic = window.Photoswipe.currItem;
        var data = {
            album: pic.album,
            author: pic.author,
            filename: pic.filename
        };

        $.post(url, data).complete(function()
        {
            var oldw = window.Photoswipe.currItem.w;
            var oldh = window.Photoswipe.currItem.h;

            window.Photoswipe.currItem.src += '?' + (new Date().getTime()).toString(6);
            window.Photoswipe.currItem.w = oldh;
            window.Photoswipe.currItem.h = oldw;

            window.Photoswipe.invalidateCurrItems();
            window.Photoswipe.updateSize(true);

            window.loader.hide();
        });

        return false;
    });

});