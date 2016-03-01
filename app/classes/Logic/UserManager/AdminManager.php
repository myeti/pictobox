<?php

namespace App\Logic\UserManager;

use App\Model\Album;
use Colorium\Web\Context;
use Colorium\Http\Response;


/**
 * @access 9
 */
class AdminManager
{

    /**
     * Generate albums cache
     *
     * @html admin/cache
     * @param Context $ctx
     * @return array
     */
    public function cache(Context $ctx)
    {
        $files = [];
        $albums = Album::fetch();
        $force = (bool)$ctx->request->uri->param('force');

        foreach($albums as $album) {
            foreach($album->authors() as $author) {
                foreach($author->pics() as $pic) {
                    if($force or !file_exists($pic->cachepath)) {
                        $files[] = $pic->path;
                    }
                }
            }
        }

        return ['files' => $files, 'force' => $force];
    }


    /**
     * Generate albums cache
     *
     * @json
     *
     * @param Context $ctx
     * @return array
     */
    public function cacheClear(Context $ctx)
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

            $state = $pic->cache(true);
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