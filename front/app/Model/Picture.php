<?php

namespace App\Model;

class Picture
{

    /** @var string */
    public $path;

    /** @var string */
    public $name;

    /** @var string */
    public $author;

    /** @var string */
    public $album;

    /** @var string */
    public $url;

    /** @var string */
    public $cache;


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
        $this->cache = CACHE_URL . $this->album . '/' . $this->author . '/' . $this->name;
    }

}