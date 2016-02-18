<?php

namespace App\Logic;

use App\Model\Album;
use Colorium\Http\Error\NotFoundException;

class AlbumList
{

    /**
     * Render many albums
     *
     * @html albums/listing
     *
     * @return array
     */
    public function all()
    {
        $albums = Album::fetch();

        return [
            'albums' => $albums,
            'ariane' => []
        ];
    }


    /**
     * Render many albums
     *
     * @html albums/listing
     *
     * @param int $year
     * @return array
     * @throws NotFoundException
     */
    public function year($year)
    {
        $albums = Album::fetch($year);
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
     * Render many albums
     *
     * @html albums/listing
     *
     * @param int $year
     * @param int $month
     * @return array
     * @throws NotFoundException
     */
    public function month($year, $month)
    {
        $albums = Album::fetch($year, $month);
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
     * Render many albums
     *
     * @html albums/listing
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return array
     * @throws NotFoundException
     */
    public function day($year, $month, $day)
    {
        $albums = Album::fetch($year, $month, $day);
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

}