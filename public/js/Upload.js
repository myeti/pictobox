function Upload(container)
{
    var self = this;

    /**
     * Html handlers
     */
    this.html = {
        container: container,
        form: container.find('form'),
        input: container.find(':file'),
        state: container.find('.state'),
        list: container.find('.list')
    };


    /**
     * Progress data
     */
     this.progress = {
        items: [],
        total: 0,
        done: 0
    };


    /**
     * Open file explorer
     */
    this.open = function()
    {
        self.html.input.trigger('click');
    };


    /**
     * Run upload
     */
    this.run = function()
    {
        // set files
        self.html.input.files = self.html.input.prop('files');

        // no file to upload
        if(!self.html.input.files) {
            console.log(self.html.input.files);
            return;
        }

        // reset progress
        self.progress.total = self.html.input.files.length;
        self.progress.done = 0;
        self.html.state.text(self.progress.done + '/' + self.progress.total);

        // prepare list
        $.each(self.html.input.files, function (index, file) {
            var item = $('<li/>').css('background-size', 0).text(file.name);
            var bar = $('<span/>').css('width', '0%');
            item.append(bar);
            self.html.list.append(item);
            self.progress.items[index] = {
                file: file,
                html: item,
                bar: bar
            };
        });

        // start upload first item
        self.progress.items.reverse();
        self.next();
    };


    /**
     * Upload next item
     */
    this.next = function()
    {
        // pop item
        var item = self.progress.items.pop();
        if(!item) {
            self.finish();
            return;
        }

        // prepare form data
        var form = new FormData();
        form.append(self.html.input.attr('name'), item.file);

        // run ajax query
        $.ajax({
            url: self.html.form.attr('action'),
            type: 'POST',
            data: form,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function (event) {
                    if(event.lengthComputable) {
                        var percent = (event.loaded / event.total) * 100;
                        item.bar.css('width', percent + '%');
                    }
                }, false);
                return xhr;
            }
        })
        .complete(function()
        {
            item.html.fadeOut(function()
            {
                item.html.remove();
                self.progress.done++;
                self.html.state.text(self.progress.done + '/' + self.progress.total);
                self.next();
            });
        });
    };


    /**
     * Finish upload
     */
    this.finish = function()
    {
        window.location.reload(true);
    };


    /**
     * Bind input event
     */
    this.html.input.on('change', function()
    {
        self.run();
    });
}