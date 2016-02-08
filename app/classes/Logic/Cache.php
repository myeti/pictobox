<?php

namespace App\Logic;

use App\Model\Album;
use Colorium\App\Context;

/**
 * @access 9
 */
class Cache
{

    /**
     * Generate albums cache
     *
     * @html cache/listing
     */
    public function listing()
    {
        $files = [];
        $albums = Album::fetch();
        foreach($albums as $album) {
            foreach($album->authors() as $author) {
                foreach($author->pics() as $pic) {
                    $files[] = $pic->path;
                }
            }
        }

        return ['files' => $files];
    }

    /**
     * Generate albums cache
     *
     * @json
     *
     * @param Context $self
     * @return bool
     */
    public function clear(Context $self)
    {
        set_time_limit(300);

        $file = $self->request->value('file');
        $author = dirname($file);
        $album = dirname($author);

        $album = new Album($album);
        $author = $album->author(basename($author));
        $pic = $author->pic(basename($file));

        return $pic->cache();
    }

}