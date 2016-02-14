<?php

namespace App\Model;

use Intervention\Image\Constraint;
use Intervention\Image\ImageManagerStatic as Image;

class Picture
{

    /** @var string */
    public $path;

    /** @var string */
    public $cachepath;

    /** @var string */
    public $cachepath_small;

    /** @var string */
    public $name;

    /** @var Author */
    public $author;

    /** @var string */
    public $cacheurl;

    /** @var string */
    public $cacheurl_small;

    /** @var int */
    public $width;

    /** @var int */
    public $height;

    /** @var int */
    protected $ctime;


    /**
     * Open picture
     *
     * @param Author $author
     * @param string $path
     */
    public function __construct(Author $author, $path)
    {
        $this->author = $author;

        $this->path = $path;
        $this->name = basename($path);
        $this->cachepath = $author->cachepath . DIRECTORY_SEPARATOR . $this->name;
        $this->cachepath_small = $author->cachepath . DIRECTORY_SEPARATOR . 'small_' . $this->name;

        $this->cacheurl = CACHE_URL . $author->album->basename . '/' . $author->name . '/' . $this->name;
        $this->cacheurl_small = CACHE_URL . $author->album->basename . '/' . $author->name . '/small_' . $this->name;

        list($width, $height) = file_exists($this->cachepath)
            ? getimagesize($this->cachepath)
            : getimagesize($this->path);

        $this->width = $width;
        $this->height = $height;
    }


    /**
     * Create cache
     *
     * @return bool
     */
    public function cache()
    {
        // already cached
        if(file_exists($this->cachepath) and file_exists($this->cachepath_small)) {
            return null;
        }

        // error
        if(!$cache = Image::make($this->path)) {
            return false;
        }

        // create folders
        $dirname = dirname($this->cachepath);
        if(!is_dir($dirname)) {
            mkdir($dirname, 0777, true);
        }

        // create cache picture
        $width = ($this->width >= $this->height) ? 1280 : null;
        $height = ($this->width >= $this->height) ? null : 1080;
        $cache->resize($width, $height, function(Constraint $constraint) {
            $constraint->aspectRatio();
        });
        $cache->save($this->cachepath, 75);

        // create small cache picture
        $cache = Image::make($this->path);
        $cache->resize(500, null, function(Constraint $constraint) {
            $constraint->aspectRatio();
        });
        $cache->save($this->cachepath_small, 75);

        return true;
    }


    /**
     * Get last modified date
     * @return int
     */
    public function ctime()
    {
        if(!$this->ctime) {
            $this->ctime = filectime($this->path);
        }

        return $this->ctime;
    }


    /**
     * Is valid file
     *
     * @param string $file
     * @return bool
     */
    public static function valid($file)
    {
        return (Image::make($file)->mime() == 'image/jpeg');
    }

}