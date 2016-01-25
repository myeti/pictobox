<?php

namespace App\Model;

abstract class Library
{

    /**
     * Get many albums
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return Album[]
     */
    public static function albums($year = null, $month = null, $day = null)
    {
        $albums = [];
        $folders = glob(ALBUMS_DIR . '/' . $year . $month . $day . '*', GLOB_ONLYDIR);
        foreach($folders as $folder) {
            $albums[] = new Album($folder);
        }

        return $albums;
    }


    /**
     * Get one album
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $flatname
     * @return Album
     */
    public static function album($year, $month, $day, $flatname)
    {
        $albums = static::albums($year, $month, $day);
        foreach($albums as $album) {
            if($album->flatname == $flatname) {
                return $album;
            }
        }
    }

}