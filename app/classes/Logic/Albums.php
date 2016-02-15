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
     * @throws NotFoundException
     */
    public function listing()
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
    public function listingYear($year)
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
    public function listingMonth($year, $month)
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
    public function listingDay($year, $month, $day)
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
     * @json
     *
     * @param Context $ctx
     * @return array
     */
    public function create(Context $ctx)
    {
        list($name, $date) = $ctx->post('name', 'date');

        // check date
        list($day, $month, $year) = explode('/', $date);

        // check if folder already exists
        $flatname = Uri::sanitize($name);
        if(Album::one($year, $month, $day, $flatname)) {
            return [
                'state' => false,
                'message' => text('logic.album.error.exists')
            ];
        }

        // create album folder
        $album = Album::create($year, $month, $day, $name);
        if(!$album) {
            return [
                'state' => false,
                'message' => text('logic.album.error.create')
            ];
        }

        return [
            'state' => true,
            'redirect' => (string)$ctx->uri($album->url)
        ];
    }


    /**
     * Edit album details
     *
     * @access 5
     * @json
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $flatname
     * @param Context $ctx
     * @return array
     * @throws NotFoundException
     */
    public function edit($year, $month, $day, $flatname, Context $ctx)
    {
        // get album
        $album = Album::one($year, $month, $day, $flatname);
        if(!$album) {
            throw new NotFoundException;
        }

        // get form data
        list($name, $date) = $ctx->post('name', 'date');

        // check date
        list($newday, $newmonth, $newyear) = explode('/', $date);

        // check if folder already exists
        $flatname = Uri::sanitize($name);
        if(Album::one($newday, $newmonth, $newyear, $flatname)) {
            return [
                'state' => false,
                'message' => text('logic.album.error.exists')
            ];
        }

        // rename
        if(!$album->rename($newday, $newmonth, $newyear, $name)) {
            return [
                'state' => false,
                'message' => text('logic.album.error.update')
            ];
        }

        return [
            'state' => true,
            'redirect' => (string)$ctx->uri($album->url)
        ];
    }


    /**
     * Upload pics to album
     *
     * @access 5
     * @json
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $flatname
     * @param Context $ctx
     * @return Response\Json
     * @throws NotFoundException
     */
    public function upload($year, $month, $day, $flatname, Context $ctx)
    {
        // get album
        $album = Album::one($year, $month, $day, $flatname);
        if(!$album) {
            throw new NotFoundException;
        }

        // get uploaded file
        $file = $ctx->request->file('file');
        if(!$file) {
            return [
                'state' => false,
                'message' => text('logic.album.upload.empty')
            ];
        }

        // bad format
        if(!Picture::valid($file->tmp)) {
            return [
                'state' => false,
                'message' => text('logic.album.upload.denied')
            ];
        }

        // save and create cache
        $picture = $album->add($file->tmp, $file->name, $ctx->user()->username);
        if(!$picture) {
            return [
                'state' => false,
                'message' => text('logic.album.upload.failed')
            ];
        }

        return [
            'state' => true
        ];
    }


    /**
     * Download album as .zip
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $flatname
     * @return Response\Download
     *
     * @throws HttpException
     * @throws NotFoundException
     */
    public function download($year, $month, $day, $flatname)
    {
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