<?php

namespace App\Logic;

use App\Model\Collection;
use Colorium\Http\Error\NotFound;

/**
 * @access 1
 */
class Albums
{

    /**
     * Render all albums, sorted by year
     *
     * @html albums/all
     *
     * @return array
     */
    public function all()
    {
        $collection = Collection::albums();

        return ['collection' => $collection];
    }


    /**
     * Render albums of specified year
     *
     * @html albums/year
     *
     * @param int $year
     * @return array
     *
     * @throws NotFound
     */
    public function year($year)
    {
        $collection = Collection::albums($year);
        if(!$collection->albums) {
            throw new NotFound;
        }

        return ['collection' => $collection];
    }


    /**
     * Render albums of specified month
     *
     * @html albums/month
     *
     * @param int $year
     * @param int $month
     * @return array
     *
     * @throws NotFound
     */
    public function month($year, $month)
    {
        $collection = Collection::albums($year, $month);
        if(!$collection->albums) {
            throw new NotFound;
        }

        return ['collection' => $collection];
    }


    /**
     * Render albums of specified day
     *
     * @html albums/day
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return array
     *
     * @throws NotFound
     */
    public function day($year, $month, $day)
    {
        $collection = Collection::albums($year, $month, $day);
        if(!$collection->albums) {
            throw new NotFound;
        }

        return ['collection' => $collection];
    }


    /**
     * Render a specific album
     *
     * @html albums/one
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string$name
     * @return array
     *
     * @throws NotFound
     */
    public function one($year, $month, $day, $name)
    {
        $album = Collection::album($year, $month, $day, $name);
        if(!$album) {
            throw new NotFound;
        }

        return ['album' => $album];
    }

}