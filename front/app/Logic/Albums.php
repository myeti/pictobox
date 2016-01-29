<?php

namespace App\Logic;

use App\Model\Album;
use Colorium\App\Context;
use Colorium\Http\Error\NotFoundException;
use Colorium\Http\Uri;
use Colorium\Stateful\Flash;

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
        $albums = Album::fetch();

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
        $album = Album::one($year, $month, $day, $flatname);
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


    /**
     * Create new album
     *
     * @param Context $self
     * @return \Colorium\Http\Response\Json
     */
    public function create(Context $self)
    {
        list($name, $date) = $self->post('name', 'date');

        // check name

        // check date
        list($day, $month, $year) = explode('/', $date);

        // check if folder already exists
        $flatname = Uri::sanitize($name);
        if(Album::one($year, $month, $day, $flatname)) {
            return Context::json([
                'state' => false,
                'message' => 'L\'album existe déja'
            ]);
        }

        // create album folder
        $author = $self->access->user->username;
        $album = Album::create($year, $month, $day, $name, $author);
        if(!$album) {
            return Context::json([
                'state' => false,
                'message' => 'Impossible de créer l\'album'
            ]);
        }

        return Context::json([
            'state' => true,
            'redirect' => $self->request->uri->make($album->url)
        ]);
    }

}