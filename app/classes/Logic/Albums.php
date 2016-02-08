<?php

namespace App\Logic;

use App\Model\Album;
use App\Model\Picture;
use Colorium\App\Context;
use Colorium\Http\Error\HttpException;
use Colorium\Http\Error\NotFoundException;
use Colorium\Http\Uri;
use Colorium\Http\Response;

/**
 * @access 1
 */
class Albums
{


    /**
     * Render many albums
     *
     * @html albums/listing
     *
     * @return array
     *
     * @throws NotFoundException
     */
    public function listing()
    {
        $albums = Album::fetch();

        return [
            'albums' => $albums
        ];
    }

    /**
     * Render many albums
     *
     * @html albums/listing
     *
     * @param int $year
     * @return array
     *
     * @throws NotFoundException
     */
    public function listingYear($year)
    {
        $year = (int)$year;

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
     *
     * @throws NotFoundException
     */
    public function listingMonth($year, $month)
    {
        $year = (int)$year;
        $month = (int)$month;

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
     *
     * @throws NotFoundException
     */
    public function listingDay($year, $month, $day)
    {
        $year = (int)$year;
        $month = (int)$month;
        $day = (int)$day;

        $albums = Album::fetch($year, $month, $day);
        if($year and !$albums) {
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
     * @html albums/show
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $flatname
     * @return array
     *
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
                Album::$months[(int)$month] => $year . '/' . $month,
                $year => $year
            ]
        ];
    }


    /**
     * Create new album
     *
     * @access 5
     *
     * @param Context $self
     * @return \Colorium\Http\Response\Json
     */
    public function create(Context $self)
    {
        list($name, $date) = $self->post('name', 'date');

        // check date
        list($day, $month, $year) = explode('/', $date);
        $year = (int)$year;
        $month = (int)$month;
        $day = (int)$day;

        // check if folder already exists
        $flatname = Uri::sanitize($name);
        if(Album::one($year, $month, $day, $flatname)) {
            return Response::json([
                'state' => false,
                'message' => 'L\'album existe déja'
            ]);
        }

        // create album folder
        $album = Album::create($year, $month, $day, $name);
        if(!$album) {
            return Response::json([
                'state' => false,
                'message' => 'Impossible de créer l\'album'
            ]);
        }

        return Response::json([
            'state' => true,
            'redirect' => (string)$self->request->uri->make($album->url)
        ]);
    }


    /**
     * Edit album details
     *
     * @access 5
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $flatname
     * @param Context $self
     * @return Response\Json
     *
     * @throws NotFoundException
     */
    public function edit($year, $month, $day, $flatname, Context $self)
    {
        $year = (int)$year;
        $month = (int)$month;
        $day = (int)$day;

        // get album
        $album = Album::one($year, $month, $day, $flatname);
        if(!$album) {
            throw new NotFoundException;
        }

        // get form data
        list($name, $date) = $self->post('name', 'date');

        // check name
        $name = htmlentities($name);

        // check date
        list($newday, $newmonth, $newyear) = explode('/', $date);
        $newday = (int)$newday;
        $newmonth = (int)$newmonth;
        $newyear = (int)$newyear;

        // check if folder already exists
        $flatname = Uri::sanitize($name);
        if(Album::one($newday, $newmonth, $newyear, $flatname)) {
            return Response::json([
                'state' => false,
                'message' => 'L\'album existe déja'
            ]);
        }

        // rename
        if(!$album->rename($newday, $newmonth, $newyear, $name)) {
            return Response::json([
                'state' => false,
                'message' => 'Impossible de modifier l\'album'
            ]);
        }

        return Response::json([
            'state' => true,
            'redirect' => (string)$self->request->uri->make($album->url)
        ]);
    }


    /**
     * Upload pics to album
     *
     * @access 5
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $flatname
     * @param Context $self
     * @return Response\Json
     *
     * @throws NotFoundException
     */
    public function upload($year, $month, $day, $flatname, Context $self)
    {
        $year = (int)$year;
        $month = (int)$month;
        $day = (int)$day;

        // get album
        $album = Album::one($year, $month, $day, $flatname);
        if(!$album) {
            throw new NotFoundException;
        }

        // get uploaded file
        $file = $self->request->file('file');
        if(!$file) {
            return Response::json([
                'state' => false,
                'message' => 'Echec de l\'envoie'
            ]);
        }

        // bad format
        if(!Picture::valid($file->tmp)) {
            return Response::json([
                'state' => false,
                'message' => 'Mauvais format'
            ]);
        }

        // save and create cache
        $picture = $album->add($file->tmp, $file->name, $self->access->user->username);
        if(!$picture) {
            return Response::json([
                'state' => false,
                'message' => 'Erreur durant l\'upload'
            ]);
        }

        return Response::json([
            'state' => true
        ]);
    }


    /**
     * Download album as .zip
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $flatname
     * @return Response\Redirect
     *
     * @throws HttpException
     * @throws NotFoundException
     */
    public function download($year, $month, $day, $flatname)
    {
        $year = (int)$year;
        $month = (int)$month;
        $day = (int)$day;

        // get album
        $album = Album::one($year, $month, $day, $flatname);
        if(!$album) {
            throw new NotFoundException;
        }

        $zipname = sys_get_temp_dir() . '/' . $album->basename . ' - ' . uniqid() . '.zip';

        $zip = new \ZipArchive;
        $code = $zip->open($zipname, \ZipArchive::CREATE);
        if($code !== true) {
            throw new HttpException;
        }

        $zip->addEmptyDir($album->basename);
        foreach($album->authors() as $author) {
            $zip->addEmptyDir($album->basename . '/' . $author->name);
            foreach($author->pics() as $pic) {
                $zip->addFile($pic->path, $album->basename . '/' . $author->name . '/' . $pic->name);
            }
        }
        $zip->close();

        return Response::download($zipname)->header('Content-Type', 'application/zip');
    }

}