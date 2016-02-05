<?php

namespace App\Logic;

use App\Model\Album;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManagerStatic as Image;

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
            foreach($album->pics() as $author => $pics) {
                foreach($pics as $pic) {

                    // if cache file does not exist
                    if(!file_exists($pic->cachepath)) {

                        // create folder
                        $folder = dirname($pic->cachepath);
                        if(!is_dir($folder)) {
                            if(!mkdir($folder, 0777, true)) {
                                $states[$pic->cachepath] = 'Cannot create directory';
                                continue;
                            }
                        }

                        // process image
                        $states[$pic->cachepath] = false;
                        if(!$img = Image::make($pic->path)) {
                            $states[$pic->cachepath] = 'Cannot generate cache file';
                            continue;
                        }

                        // generate cache
                        $img->resize(1280, null, function(Constraint $constraint) {
                            $constraint->aspectRatio();
                        });

                        $img->save($pic->cachepath, 75);

                        // generate small cache
                        $img = Image::make($pic->path);
                        $img->resize(500, null, function(Constraint $constraint) {
                            $constraint->aspectRatio();
                        });

                        $img->save($pic->cachepath_small, 75);
                    }
                }
            }
        }

        return ['states' => $states];
    }

}