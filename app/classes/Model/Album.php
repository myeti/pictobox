<?php

namespace App\Model;

use App\Error\AlbumAlreadyExists;
use App\Error\InvalidAlbumDate;
use App\Error\InvalidAlbumName;
use Colorium\Http\Uri;

class Album
{

    /** @var string */
    public $fullname;

    /** @var string */
    public $flatname;

    /** @var string */
    public $name;

    /** @var string */
    public $date;

    /** @var int */
    public $year;

    /** @var int */
    public $month;

    /** @var int */
    public $day;

    /** @var string */
    public $url;

    /** @var string */
    public $path;

    /** @var string */
    public $cachepath;

    /** @var Author[] */
    public $authors = [];


    /**
     * Open album
     *
     * @param string $fullname
     */
    public function __construct($fullname)
    {
        $this->open($fullname);
    }


    /**
     * Parse fullname
     *
     * @param string $fullname
     *
     * @throws InvalidAlbumName
     */
    protected function open($fullname)
    {
        // remove path from fullname
        $fullname = basename($fullname);

        // extract info from fullname
        if(!preg_match('/^([0-9]{4})([0-9]{2})([0-9]{2}) - (.+)$/', $fullname, $extracted)) {
            throw new InvalidAlbumName;
        }

        // set basic info
        list(, $year, $month, $day, $name) = $extracted;
        $this->fullname = $fullname;
        $this->name = $name;
        $this->year = (int)$year;
        $this->month = (int)$month;
        $this->day = (int)$day;

        // set computed info
        $this->flatname = Uri::sanitize($name);
        $this->date = ltrim($this->day, 0) . ' ' . text('date.month.' . (int)$month) . ' ' . $this->year;
        $this->url = '/' . $this->year . '/' . str_pad($this->month, 2, '0', STR_PAD_LEFT) . '/' . str_pad($this->day, 2, '0', STR_PAD_LEFT) . '/' . $this->flatname;
        $this->path = ALBUMS_DIR . $this->fullname;
        $this->cachepath = CACHE_DIR . $this->fullname;

        $this->authors = [];
    }


    /**
     * Get author list
     *
     * @return Author[]
     */
    public function authors()
    {
        if(!$this->authors) {
            $this->authors = Author::fetch($this);
        }

        return $this->authors;
    }


    /**
     * Get specific author
     *
     * @param $name
     * @return Author
     */
    public function author($name)
    {
        $this->authors();
        if(isset($this->authors[$name])) {
            return $this->authors[$name];
        }
    }


    /**
     * Get random picture
     *
     * @return Picture
     */
    public function random()
    {
        $random = array_rand($this->authors());
        if($random) {
            return $this->authors[$random]->random();
        }
    }


    /**
     * Edit album
     *
     * @param $year
     * @param $month
     * @param $day
     * @param $name
     * @return bool
     *
     * @throws AlbumAlreadyExists
     * @throws InvalidAlbumDate
     * @throws InvalidAlbumName
     * @throws \Exception
     */
    public function edit($year, $month, $day, $name)
    {
        // format name
        $newname = static::format($year, $month, $day, $name);

        // album already exists
        if(file_exists(ALBUMS_DIR . $newname)) {
            throw new AlbumAlreadyExists;
        }

        // rename
        $done = rename($this->path, ALBUMS_DIR . $newname);
        $done &= rename($this->cachepath, CACHE_DIR . $newname);
        if(!$done) {
            throw new \Exception;
        }

        // update this album
        $this->open($newname);

        return true;
    }


    /**
     * Delete album
     */
    public function delete()
    {
        unlink($this->path);
        unlink($this->cachepath);
    }


    /**
     * Make downloadable archive
     *
     * @return string
     *
     * @throws \Exception
     */
    public function zip()
    {
        // generate random filename
        $zipname = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $this->fullname . ' - ' . uniqid() . '.zip';

        // create empty zip
        $zip = new \ZipArchive;
        $code = $zip->open($zipname, \ZipArchive::CREATE);
        if($code !== true) {
            throw new \Exception;
        }

        // add all files to zip
        $zip->addEmptyDir($this->fullname);
        foreach($this->authors() as $author) {
            $zip->addEmptyDir($this->fullname . DIRECTORY_SEPARATOR . $author->name);
            foreach($author->pics() as $pic) {
                $zip->addFile($pic->path, $this->fullname . DIRECTORY_SEPARATOR . $author->name . DIRECTORY_SEPARATOR . $pic->filename);
            }
        }

        $zip->close();
        return $zipname;
    }


    /**
     * Create new album
     *
     * @param $year
     * @param $month
     * @param $day
     * @param $name
     * @return Album
     *
     * @throws AlbumAlreadyExists
     * @throws InvalidAlbumDate
     * @throws InvalidAlbumName
     * @throws \Exception
     */
    public static function create($year, $month, $day, $name)
    {
        // format name
        $fullname = static::format($year, $month, $day, $name);

        // album already exists
        if(file_exists(ALBUMS_DIR . $fullname)) {
            throw new AlbumAlreadyExists;
        }

        // create
        $done = mkdir(ALBUMS_DIR . $fullname, 0777, true);
        $done &= mkdir(CACHE_DIR . $fullname, 0777, true);
        if(!$done) {
            throw new \Exception;
        }

        return new static($fullname);
    }


    /**
     * Get many albums
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return Album[]
     */
    public static function fetch($year = null, $month = null, $day = null)
    {
        if($month) {
            $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        }

        if($day) {
            $day = str_pad($day, 2, '0', STR_PAD_LEFT);
        }

        $albums = [];
        $folders = glob(ALBUMS_DIR . $year . $month . $day . '*', GLOB_ONLYDIR);
        foreach($folders as $folder) {
            $album = new Album($folder);
            $index = $album->year . $album->month . $album->day . $album->flatname;
            $albums[$index] = $album;
        }

        return array_reverse($albums);
    }


    /**
     * Get one album
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $flatname
     * @return Album
     */
    public static function one($year, $month, $day, $flatname)
    {
        $albums = static::fetch($year, $month, $day);
        $index = $year . $month . $day . $flatname;

        if(isset($albums[$index])) {
            return $albums[$index];
        }
    }


    /**
     * Format album fullname
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $name
     * @return string
     *
     * @throws InvalidAlbumDate
     * @throws InvalidAlbumName
     */
    protected static function format($year, $month, $day, $name)
    {
        // cast integer
        $year = (int)$year;
        $month = (int)$month;
        $day = (int)$day;

        // invalid date
        if(!checkdate($month, $day, $year)) {
            throw new InvalidAlbumDate;
        }

        // format name (note: keep space)
        $name = preg_replace('/[^a-zA-Z0-9-\' áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]*/', null, $name);

        // invalid name
        if(!$name) {
            throw new InvalidAlbumName;
        }

        // format date
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $day = str_pad($day, 2, '0', STR_PAD_LEFT);

        return $year . $month . $day . ' - ' . ucfirst($name);
    }

}