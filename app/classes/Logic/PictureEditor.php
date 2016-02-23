<?php

namespace App\Logic;

use App\Model\Album;
use Colorium\Http\Error\NotFoundException;
use Colorium\Web\Context;


/**
 * @access 5
 */
class PictureEditor
{

    /**
     * Rotate picture to left
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $flatname
     * @param Context $ctx
     * @return array
     *
     * @throws NotFoundException
     */
    public function rotateLeft($year, $month, $day, $flatname, Context $ctx)
    {
        $year = (int)$year;
        $month = (int)$month;
        $day = (int)$day;

        // get post data and album
        $album = Album::one($year, $month, $day, $flatname);
        list($author, $filename) = $ctx->post('author', 'filename');

        if(!$album or !$author = $album->author($author) or !$picture = $author->pic($filename)) {
            throw new NotFoundException;
        }

        // rotate
        if(!$picture->rotateLeft()) {
            return [
                'state' => false,
                'message' => 'Not implemented'
            ];
        }

        return [
            'state' => true
        ];
    }

    /**
     * Rotate picture to right
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $flatname
     * @param Context $ctx
     * @return array
     *
     * @throws NotFoundException
     */
    public function rotateRight($year, $month, $day, $flatname, Context $ctx)
    {
        $year = (int)$year;
        $month = (int)$month;
        $day = (int)$day;

        // get post data and album
        $album = Album::one($year, $month, $day, $flatname);
        list($author, $filename) = $ctx->post('author', 'filename');

        if(!$album or !$author = $album->author($author) or !$picture = $author->pic($filename)) {
            throw new NotFoundException;
        }

        // rotate
        if(!$picture->rotateRight()) {
            return [
                'state' => false,
                'message' => 'Not implemented'
            ];
        }

        return [
            'state' => true
        ];
    }

}