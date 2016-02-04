<?php

namespace App\Model;

use Colorium\Http\Uri;

class Album
{

    /** @var string */
    public $name;

    /** @var string */
    public $flatname;

    /** @var string */
    public $path;

    /** @var int */
    public $year;

    /** @var int */
    public $month;

    /** @var string */
    public $monthname;

    /** @var int */
    public $day;

    /** @var string */
    public $date;

    /** @var string */
    public $url;

    /** @var array */
    protected $pics = [];

    /** @var array */
    public static $months = [
        1 => 'Janvier',
        2 => 'Février',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
        7 => 'Juillet',
        8 => 'Août',
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre',
    ];


    /**
     * Open album
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
        if(preg_match('/([0-9]{4})([0-9]{2})([0-9]{2}) - (.+)/', $path, $extracted)) {
            list(, $year, $month, $day, $name) = $extracted;

            $this->year = $year;
            $this->month = $month;
            $this->day = $day;
            $this->name = $name;

            $this->flatname = Uri::sanitize($name);
            $this->monthname = static::$months[(int)$month];
            $this->date = ltrim($this->day, 0) . ' ' . $this->monthname . ' ' . $this->year;
            $this->url = '/' . $this->year . '/' . $this->month . '/' . $this->day . '/' . $this->flatname;
        }
    }


    /**
     * Get author list
     *
     * @param string $author
     * @return array
     */
    public function pics($author = null)
    {
        if(!$this->pics) {
            foreach(glob($this->path . '/*') as $folder) {
                $sub = basename($folder);
                foreach(glob($this->path . '/' . $sub . '/*') as $picture) {
                    $this->pics[$sub][] = new Picture($picture);
                }
            }
        }

        if($author) {
            $author = ucfirst(strtolower($author));
            return isset($this->pics[$author])
                ? $this->pics[$author]
                : null;
        }

        return $this->pics;
    }


    /**
     * Get thumbnail of album
     * Currently random, need proper system (TODO)
     *
     * @param null $author
     * @return Picture
     */
    public function thumbnail($author = null)
    {
        $authors = $this->pics();
        if($authors) {
            $author = $author ?: array_rand($authors);
            $rand = array_rand($authors[$author]);
            return $authors[$author][$rand];
        }
    }


    /**
     * Count pics
     *
     * @param string $author
     * @return int
     */
    public function count($author = null)
    {
        return count($this->pics($author));
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
        $albums = [];
        $folders = glob(ALBUMS_DIR . '/' . $year . $month . $day . '*', GLOB_ONLYDIR);
        foreach($folders as $folder) {
            $albums[] = new Album($folder);
        }

        return $albums;
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
        foreach($albums as $album) {
            if($album->flatname == $flatname) {
                return $album;
            }
        }
    }


    /**
     * Create album
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $name
     *
     * @return Album
     */
    public static function create($year, $month, $day, $name)
    {
        // create album
        $folder = $year . $month . $day . ' - ' . $name;
        if(is_dir(ALBUMS_DIR . $folder) or !mkdir(ALBUMS_DIR . $folder, 0777)
           or is_dir(CACHE_DIR . $folder) or !mkdir(CACHE_DIR . $folder, 0777)) {
            return false;
        }

        // create cache
        if(!is_dir(CACHE_DIR . $folder)) {
            mkdir(CACHE_DIR . $folder, 0777);
        }

        return new Album($folder);
    }


    /**
     * Create album
     *
     * @param Album $album
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $name
     *
     * @return Album
     */
    public static function rename(Album $album, $year, $month, $day, $name)
    {
        // rename album
        $folder = $year . $month . $day . ' - ' . $name;
        if(!rename($album->path, ALBUMS_DIR . $folder) or rename(CACHE_DIR . $album->name, ALBUMS_DIR . $folder)) {
            return false;
        }

        // rename cache
        if(!is_dir(CACHE_DIR . $folder)) {
            mkdir(CACHE_DIR . $folder, 0777);
        }

        return new Album($folder);
    }


    /**
     * Create album
     *
     * @param Album $album
     *
     * @return Album
     */
    public static function delete(Album $album)
    {

    }

}