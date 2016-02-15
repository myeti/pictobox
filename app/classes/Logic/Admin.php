<?php

namespace App\Logic;

use App\Model\Album;
use App\Model\User;
use App\Service\Mail;
use Colorium\App\Context;
use Colorium\Http\Response;


/**
 * @access 9
 */
class Admin
{

    /**
     * Generate albums cache
     *
     * @html admin/cache
     */
    public function cache()
    {
        $files = [];
        $albums = Album::fetch();
        foreach($albums as $album) {
            foreach($album->authors() as $author) {
                foreach($author->pics() as $pic) {
                    if(!file_exists($pic->cachepath)) {
                        $files[] = $pic->path;
                    }
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
     * @param Context $ctx
     * @return array
     */
    public function cacheclear(Context $ctx)
    {
        set_time_limit(300);

        $files = $ctx->request->value('files');
        $files = explode(',', $files);

        $states = [];
        foreach($files as $file) {
            $author = dirname($file);
            $album = dirname($author);

            $album = new Album($album);
            $author = $album->author(basename($author));
            $pic = $author->pic(basename($file));

            $state = $pic->cache();
            if($state === null) {
                $states[] = [str_replace(ALBUMS_DIR, null, $file), 'skipped'];
            }
            elseif($state === true) {
                $states[] = [str_replace(ALBUMS_DIR, null, $file), 'done'];
            }
            else {
                $states[] = [str_replace(ALBUMS_DIR, null, $file), 'failed'];
            }
        }

        return $states;
    }

}