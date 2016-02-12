<?php

namespace App\Model;

use Colorium\Http\Uri;

class Album
{

    /** @var string */
    public $path;

    /** @var string */
    public $cachepath;

    /** @var string */
    public $basename;

    /** @var string */
    public $name;

    /** @var string */
    public $flatname;

    /** @var string */
    public $date;

    /** @var int */
    public $year;

    /** @var int */
    public $month;

    /** @var string */
    public $monthname;

    /** @var int */
    public $day;

    /** @var int */
    public $url;

    /** @var Author[] */
    protected $authors = [];

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
        $this->open($path);
    }


    /**
     * Reload data
     *
     * @param string $path
     */
    protected function open($path)
    {
        if(!preg_match('/([0-9]{4})([0-9]{2})([0-9]{2}) - (.+)/', $path, $extracted)) {
            throw new \InvalidArgumentException('Invalid album path');
        }

        $this->path = $path;
        $this->basename = basename($path);
        $this->cachepath = CACHE_DIR . $this->basename;

        list(, $year, $month, $day, $name) = $extracted;

        $this->name = $name;
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        $this->flatname = Uri::sanitize($name);
        $this->monthname = static::$months[(int)$month];
        $this->date = ltrim($this->day, 0) . ' ' . $this->monthname . ' ' . $this->year;
        $this->url = '/' . $this->year . '/' . $this->month . '/' . $this->day . '/' . $this->flatname;

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
            $path = ALBUMS_DIR . $this->basename . DIRECTORY_SEPARATOR;
            foreach(glob($path . '*', GLOB_ONLYDIR) as $folder) {
                $author = new Author($this, $folder);
                $this->authors[$author->name] = $author;
            }
        }

        return $this->authors;
    }


    /**
     * Get author
     *
     * @param string $name
     * @return Author
     */
    public function author($name)
    {
        $this->authors();

        return isset($this->authors[$name])
            ? $this->authors[$name]
            : null;
    }


    /**
     * Get random author
     *
     * @return Author
     */
    public function random()
    {
        $this->authors();
        $random = array_rand($this->authors);
        return $this->authors[$random];
    }


    /**
     * Get random pic
     *
     * @param Author $author
     * @return Picture
     */
    public function thumbnail(Author $author = null)
    {
        $author = $author ?: $this->random();

        if($author) {
            return $author->random();
        }
    }


    /**
     * Rename album
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $name
     * @return bool
     */
    public function rename($year, $month, $day, $name)
    {
        $folder = static::format($year, $month, $day, $name);
        if(!rename($this->path, ALBUMS_DIR . $folder)
            or rename($this->cachepath, CACHE_DIR . $folder)) {
            return false;
        }

        $this->open($folder);
        return true;
    }


    /**
     * Add picture to author
     *
     * @param string $source
     * @param string $file
     * @param string $in
     * @return Picture
     */
    public function add($source, $file, $in)
    {
        $this->authors();

        // create author folder
        $author = $this->author($in);
        if(!$author) {
            $author = new Author($this, $this->path . DIRECTORY_SEPARATOR . $in);
            mkdir($author->path, 0777);
            mkdir($author->cachepath, 0777);
            $this->authors[$author->name] = $author;
        }

        // create picture
        $path = $author->path . DIRECTORY_SEPARATOR . $file;
        if(!file_exists($path)) {
            copy($source, $path);
        }

        $pic = new Picture($author, $path);
        if(!$pic->cache()) {
            return false;
        }

        return $pic;
    }


    /**
     * Delete album
     *
     * @return bool
     */
    public function delete()
    {
        return false;
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
        $folder = static::format($year, $month, $day, $name);
        if(is_dir(ALBUMS_DIR . $folder) or !mkdir(ALBUMS_DIR . $folder, 0777)
            or is_dir(CACHE_DIR . $folder) or !mkdir(CACHE_DIR . $folder, 0777)) {
            return false;
        }

        return new Album($folder);
    }


    /**
     * Format name
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $name
     * @return string
     */
    public static function format($year, $month, $day, $name)
    {
        $name = preg_replace('/[^a-zA-Z0-9-\'áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ ]*/', null, $name);
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $day = str_pad($day, 2, '0', STR_PAD_LEFT);

        return $year . $month . $day . ' - ' . $name;
    }

}