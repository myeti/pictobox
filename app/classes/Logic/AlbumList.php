<?php

namespace App\Logic;

use App\Model\Album;
use Colorium\Http\Error\NotFoundException;


/**
 * @access 1
 */
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
        $year = (int)$year;

        $albums = Album::fetch($year);
        if(!$albums) {
            $message = checkdate(1, 1, $year) ? text('logic.albumlist.year.empty') : null;
            throw new NotFoundException($message);
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
        $year = (int)$year;
        $month = (int)$month;

        $albums = Album::fetch($year, $month);
        if(!$albums) {
            $message = checkdate($month, 1, $year) ? text('logic.albumlist.month.empty') : null;
            throw new NotFoundException($message);
        }

        return [
            'albums' => $albums,
            'ariane' => [
                text('date.month.' . $month) => $year . '/' . $month,
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
        $year = (int)$year;
        $month = (int)$month;
        $day = (int)$day;

        $albums = Album::fetch($year, $month, $day);
        if(!$albums) {
            $message = checkdate($month, $day, $year) ? text('logic.albumlist.day.empty') : null;
            throw new NotFoundException($message);
        }

        return [
            'albums' => $albums,
            'ariane' => [
                $day => $year . '/' . $month . '/' . $day,
                text('date.month.' . $month) => $year . '/' . $month,
                $year => $year
            ]
        ];
    }


    /**
     * Render a specific album
     *
     * @html albums/show
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $flatname
     * @return array
     * @throws NotFoundException
     */
    public function show($year, $month, $day, $flatname)
    {
        $year = (int)$year;
        $month = (int)$month;
        $day = (int)$day;

        $album = Album::one($year, $month, $day, $flatname);
        if(!$album) {
            throw new NotFoundException;
        }

        return [
            'album' => $album,
            'ariane' => [
                $day => $year . '/' . $month . '/' . $day,
                text('date.month.' . $month) => $year . '/' . $month,
                $year => $year
            ]
        ];
    }


    /**
     * Display map of all albums
     *
     * @html albums/map
     */
    public function map()
    {
        if(!MAPBOX_TOKEN or !MAPBOX_PROJECT) {
            throw new NotFoundException;
        }

        $albums = Album::fetch();

        // keep only album with meta place
        foreach($albums as $index => $album) {
            if(!$album->meta('place')) {
                unset($albums[$index]);
            }
        }

        return [
            'albums' => $albums,
            'ariane' => []
        ];
    }

}