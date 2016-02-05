<?php

namespace App\Model;

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

    /** @var string */
    public $author;

    /** @var string */
    public $album;

    /** @var string */
    public $url;

    /** @var string */
    public $cacheurl;

    /** @var string */
    public $cacheurl_small;

    /** @var int */
    public $width;

    /** @var int */
    public $height;


    /**
     * Open picture
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->name = basename($path);
        $this->author = basename(dirname($path));
        $this->album = basename(dirname(dirname($path)));

        $this->url = ALBUMS_URL . $this->album . '/' . $this->author . '/' . $this->name;
        $this->cacheurl = $this->url;
        $this->cacheurl_small = $this->url;

        $this->cachepath = CACHE_DIR . $this->album . '/' . $this->author . '/' . $this->name;
        $this->cachepath_small = CACHE_DIR . $this->album . '/' . $this->author . '/small_' . $this->name;

        if(file_exists($this->cachepath)) {
            $this->cacheurl = CACHE_URL . $this->album . '/' . $this->author . '/' . $this->name;
            $this->cacheurl_small = CACHE_URL . $this->album . '/' . $this->author . '/small_' . $this->name;
        }

        list($width, $height) = getimagesize($this->cachepath);
        $this->width = $width;
        $this->height = $height;
    }

}