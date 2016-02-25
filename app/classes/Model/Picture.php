<?php

namespace App\Model;

use App\Error\InvalidAlbumName;
use App\Error\InvalidImageFile;
use App\Error\InvalidPictureFile;
use App\Error\PictureAlreadyExists;
use Colorium\Http\Request;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManagerStatic as Image;

class Picture
{

    /** @var Author */
    public $author;

    /** @var string */
    public $filename;

    /** @var string */
    public $path;

    /** @var string */
    public $cachepath;

    /** @var string */
    public $cachepath_small;

    /** @var string */
    public $url;

    /** @var string */
    public $url_small;

    /** @var int */
    protected $width;

    /** @var int */
    protected $height;

    /** @var int */
    protected $ctime;


    /**
     * Open picture
     *
     * @param Author $author
     * @param string $filename
     */
    public function __construct(Author $author, $filename)
    {
        $this->author = $author;
        $this->open($filename);
    }


    /**
     * Open picture data
     *
     * @param string $filename
     */
    public function open($filename)
    {
        $this->filename = $filename;

        $this->path = $this->author->path . DIRECTORY_SEPARATOR . $this->filename;
        $this->cachepath = $this->author->cachepath . DIRECTORY_SEPARATOR . $this->filename;
        $this->cachepath_small = $this->author->cachepath . DIRECTORY_SEPARATOR . 'small_' . $this->filename;

        $this->url = CACHE_URL . $this->author->album->fullname . '/' . $this->author->name . '/' . $this->filename;
        $this->url_small = CACHE_URL . $this->author->album->fullname . '/' . $this->author->name . '/small_' . $this->filename;
    }


    /**
     * Get width
     *
     * @return int
     */
    public function width()
    {
        if(!$this->width) {
            list($width, $height) = getimagesize($this->path);
            $this->width = $width;
            $this->height = $height;
        }

        return $this->width;
    }


    /**
     * Get height
     *
     * @return int
     */
    public function height()
    {
        if(!$this->height) {
            list($width, $height) = getimagesize($this->path);
            $this->width = $width;
            $this->height = $height;
        }

        return $this->height;
    }


    /**
     * Get last modified date
     *
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
     * Create cache
     *
     * @param bool $force
     * @return bool
     * @throws InvalidPictureFile
     */
    public function cache($force = false)
    {
        // already cached
        if(!$force and file_exists($this->cachepath) and file_exists($this->cachepath_small)) {
            return true;
        }

        // error
        if(!$cache = Image::make($this->path)) {
            throw new InvalidPictureFile;
        }

        // create folders
        $dirname = dirname($this->cachepath);
        if(!is_dir($dirname)) {
            mkdir($dirname, 0777, true);
        }

        // create cache picture
        $width = ($this->width() >= $this->height()) ? 1280 : null;
        $height = ($this->width() >= $this->height()) ? null : 1080;
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
     * Rotate image to the right
     *
     * @return bool
     */
    public function rotateRight()
    {
        $img = Image::make($this->path);
        $img->rotate(-90);
        $done = $img->save();

        $cache = Image::make($this->cachepath);
        $cache->rotate(-90);
        $done &= $cache->save();

        $cache_small = Image::make($this->cachepath_small);
        $cache_small->rotate(-90);
        $done &= $cache_small->save();

        return $done;
    }


    /**
     * Rotate image to the left
     *
     * @return bool
     */
    public function rotateLeft()
    {
        $img = Image::make($this->path);
        $img->rotate(90);
        $done = $img->save();

        $cache = Image::make($this->cachepath);
        $cache->rotate(90);
        $done &= $cache->save();

        $cache_small = Image::make($this->cachepath_small);
        $cache_small->rotate(90);
        $done &= $cache_small->save();

        return $done;
    }


    /**
     * Create picture from source
     *
     * @param Author $author
     * @param Request\File $source
     * @return static
     *
     * @throws PictureAlreadyExists
     * @throws \Exception
     */
    public static function create(Author &$author, Request\File $source)
    {
        // invalid picture
        if(!static::valid($source->tmp)) {
            throw new InvalidPictureFile;
        }

        // picture already exists
        if(file_exists($author->path . DIRECTORY_SEPARATOR . $source->name)) {
            throw new PictureAlreadyExists;
        }

        // attempt saving
        if(!$source->save($author->path)){
            throw new \Exception;
        }

        // get instance
        $picture = new static($author, $source->name);

        // generate cache
        $picture->cache();

        // update author
        $author->pics[] = $picture;
        return $picture;

    }


    /**
     * Fetch author's pictures
     *
     * @param Author $author
     * @return static[]
     */
    public static function fetch(Author $author)
    {
        $pics = [];
        foreach(glob($author->path . DIRECTORY_SEPARATOR . '*.{jpg,JPG,jpeg,JPEG}', GLOB_BRACE) as $file) {
            $basename = basename($file);
            $pic = new Picture($author, $basename);
            $pics[$pic->filename] = $pic;
        }

        return $pics;
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