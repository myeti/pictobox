<?php

namespace App\Logic;

use App\Model\Album;
use App\Model\Library;
use Colorium\Http\Error\NotFoundException;

/**
 * @access 1
 */
class Albums
{


    /**
     * Render all albums, sorted by year
     *
     * @html albums/list
     *
     * @return array
     */
    public function all()
    {
        $albums = Library::albums();

        return [
            'albums' => $albums,
            'ariane' => []
        ];
    }


    /**
     * Render albums of specified year
     *
     * @html albums/list
     *
     * @param int $year
     * @return array
     *
     * @throws NotFoundException
     */
    public function year($year)
    {
        $albums = Library::albums($year);
        if(!$albums) {
            throw new NotFoundException;
        }

        return [
            'albums' => $albums,
            'ariane' => [
                $year => $year
            ]
        ];
    }


    /**
     * Render albums of specified month
     *
     * @html albums/list
     *
     * @param int $year
     * @param int $month
     * @return array
     *
     * @throws NotFoundException
     */
    public function month($year, $month)
    {
        $albums = Library::albums($year, $month);
        if(!$albums) {
            throw new NotFoundException;
        }

        return [
            'albums' => $albums,
            'ariane' => [
                Album::$months[(int)$month] => $year . '/' . $month,
                $year => $year
            ]
        ];
    }


    /**
     * Render albums of specified day
     *
     * @html albums/list
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return array
     *
     * @throws NotFoundException
     */
    public function day($year, $month, $day)
    {
        $albums = Library::albums($year, $month, $day);
        if(!$albums) {
            throw new NotFoundException;
        }

        return [
            'albums' => $albums,
            'ariane' => [
                $day => $year . '/' . $month . '/' . $day,
                Album::$months[(int)$month] => $year . '/' . $month,
                $year => $year
            ]
        ];
    }


    /**
     * Render a specific album
     *
     * @html albums/one
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $flatname
     * @return array
     *
     * @throws NotFoundException
     */
    public function one($year, $month, $day, $flatname)
    {
        $album = Library::album($year, $month, $day, $flatname);
        if(!$album) {
            throw new NotFoundException;
        }

        return [
            'album' => $album,
            'ariane' => [
                $day => $year . '/' . $month . '/' . $day,
                Album::$months[(int)$month] => $year . '/' . $month,
                $year => $year
            ]
        ];
    }

}