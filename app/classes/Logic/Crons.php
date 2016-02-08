<?php

namespace App\Logic;

use App\Model\Album;

class Crons
{

    /**
     * Generate albums cache
     *
     * @html crons/cache
     */
    public function cache()
    {
        set_time_limit(300);

        $states = [];
        $albums = Album::fetch();
        foreach($albums as $album) {
            foreach($album->authors() as $author) {
                foreach($author->pics() as $pic) {
                    $done = $pic->cache();
                    $states[$pic->cachepath] = $done
                        ? false
                        : 'Cannot generate cache picture';
                }
            }
        }

        return ['states' => $states];
    }

}