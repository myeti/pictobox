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
            $message = null;
            if(checkdate(1, 1, $year)) {
                $message = text('logic.albumlist.year.empty', ['year' => $year]);
            }
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
            $message = null;
            if(checkdate($month, 1, $year)) {
                $message = text('logic.albumlist.month.empty', [
                    'year' => $year,
                    'month' => text('date.month.' . (int)$month)
                ]);
            }
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
            $message = null;
            if(checkdate($month, $day, $year)) {
                $message = text('logic.albumlist.day.empty', [
                    'year' => $year,
                    'month' => text('date.month.' . (int)$month),
                    'day' => $day
                ]);
            }
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

}