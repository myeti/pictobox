<?php

namespace App\Logic;

use App\Error\AlbumAlreadyExists;
use App\Error\AuthorAlreadyExists;
use App\Error\InvalidAlbumDate;
use App\Error\InvalidAlbumName;
use App\Error\InvalidPictureFile;
use App\Error\InvalidPictureUpload;
use App\Error\PictureAlreadyExists;
use App\Model\Album;
use App\Model\Author;
use App\Model\Picture;
use Colorium\Web\Context;
use Colorium\Http\Error\HttpException;
use Colorium\Http\Error\NotFoundException;
use Colorium\Http\Response;

/**
 * @access 1
 */
class AlbumEditor
{

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
        try {

            // get form data
            list($name, $date) = $ctx->post('name', 'date');
            list($day, $month, $year) = explode('/', $date);

            // attempt creation
            $album = Album::create($year, $month, $day, $name);
            $ctx->logger->info($ctx->user->username . ' creates "' . $album->fullname . '"', $_POST);

            return [
                'state' => true,
                'redirect' => (string)$ctx->url($album->url)
            ];
        }
        catch(AlbumAlreadyExists $e) {
            return [
                'state' => false,
                'message' => text('logic.album.already-exists')
            ];
        }
        catch(InvalidAlbumDate $e) {
            return [
                'state' => false,
                'message' => text('logic.album.invalid-date')
            ];
        }
        catch(InvalidAlbumName $e) {
            return [
                'state' => false,
                'message' => text('logic.album.invalid-name')
            ];
        }
        catch(\Exception $e) {
            return [
                'state' => false,
                'message' => text('logic.album.cannot-create')
            ];
        }
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

        try {

            // get form data
            list($name, $date) = $ctx->post('name', 'date');
            list($day, $month, $year) = explode('/', $date);
            $newauthors = $ctx->post('authors');

            // update author list
            if($newauthors and $ctx->user->isAdmin()) {
                $authors = $album->authors();
                foreach($newauthors as $oldname => $newname) {
                    if($oldname != $newname and isset($authors[$oldname])) {
                        $authors[$oldname]->edit($newname);
                    }
                }
            }

            // attempt update
            $year = (int)$year;
            $month = (int)$month;
            $day = (int)$day;
            if($name != $album->name or $year != $album->year or $month != $album->month or $day != $album->day) {
                $oldalbum = $album->fullname;
                $album->edit($year, $month, $day, $name);
                $ctx->logger->info($ctx->user->username . ' edits album "' . $oldalbum . '" to "' . $album->fullname . '"', $_POST);
            }

            return [
                'state' => true,
                'redirect' => (string)$ctx->url($album->url)
            ];
        }
        catch(AuthorAlreadyExists $e) {
            return [
                'state' => false,
                'message' => text('logic.author.already-exists')
            ];
        }
        catch(AlbumAlreadyExists $e) {
            return [
                'state' => false,
                'message' => text('logic.album.already-exists')
            ];
        }
        catch(InvalidAlbumDate $e) {
            return [
                'state' => false,
                'message' => text('logic.album.invalid-date')
            ];
        }
        catch(InvalidAlbumName $e) {
            return [
                'state' => false,
                'message' => text('logic.album.invalid-name')
            ];
        }
        catch(\Exception $e) {
            return [
                'state' => false,
                'message' => text('logic.album.cannot-edit')
            ];
        }
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
     *
     * @throws NotFoundException
     */
    public function upload($year, $month, $day, $flatname, Context $ctx)
    {
        // get album
        $album = Album::one($year, $month, $day, $flatname);
        if(!$album) {
            throw new NotFoundException;
        }

        try {

            // get author (or create it)
            $author = $album->author($ctx->user->username);
            if(!$author) {
                $author = Author::create($album, $ctx->user->username);
                $ctx->logger->info($ctx->user->username . ' adds author "' . $author->name . '" in "' . $album->fullname . '"', $_POST);
            }

            // get uploaded file
            $upload = $ctx->request->file('file');
            if(!$upload) {
                throw new InvalidPictureUpload;
            }

            // add uploaded picture
            $picture = Picture::create($author, $upload);
            $ctx->logger->info($ctx->user->username . ' adds picture "' . $picture->filename . '" to "' . $album->fullname . '"');

            return ['state' => true];
        }
        catch(AuthorAlreadyExists $e) {
            return [
                'state' => false,
                'message' => text('logic.author.already-exists')
            ];
        }
        catch(PictureAlreadyExists $e) {
            return [
                'state' => false,
                'message' => text('logic.picture.already-exists')
            ];
        }
        catch(InvalidPictureUpload $e) {
            return [
                'state' => false,
                'message' => text('logic.picture.upload.empty')
            ];
        }
        catch(InvalidPictureFile $e) {
            return [
                'state' => false,
                'message' => text('logic.picture.upload.denied')
            ];
        }
        catch(\Exception $e) {
            return [
                'state' => false,
                'message' => text('logic.picture.upload.failed')
            ];
        }
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

        try {
            // create zip file
            $zipname = $album->zip();
            $ctx->logger->info($ctx->user->username . ' downloads "' . $album->fullname . '"');
            return Response::download($zipname)->header('Content-Type', 'application/zip');
        }
        catch(\Exception $e) {
            throw new HttpException;
        }
    }

}