<?php

namespace App\Model;

class Picture
{

    /** @var string */
    public $path;

    /** @var string */
    public $cachepath;

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

        $file = $this->album . '/' . $this->author . '/' . $this->name;
        $this->url = ALBUMS_URL . $file;
        $this->cacheurl = $this->url;

        $this->cachepath = CACHE_DIR . $file;
        if(file_exists($this->cachepath)) {
            $this->cacheurl = CACHE_URL . $file;
        }
    }

}