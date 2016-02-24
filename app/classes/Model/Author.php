<?php

namespace App\Model;

use App\Error\AuthorAlreadyExists;

class Author
{

    /** @var Album */
    public $album;

    /** @var string */
    public $name;

    /** @var string */
    public $path;

    /** @var string */
    public $cachepath;

    /** @var Picture[] */
    public $pics = [];


    /**
     * Open album author
     *
     * @param Album $album
     * @param string $name
     */
    public function __construct(Album $album, $name)
    {
        $this->album = $album;
        $this->open($name);
    }


    /**
     * Open author's folder
     *
     * @param string $name
     */
    protected function open($name)
    {
        $this->name = $name;
        $this->path = $this->album->path . DIRECTORY_SEPARATOR . $name;
        $this->cachepath = $this->album->cachepath . DIRECTORY_SEPARATOR . $name;
    }


    /**
     * Get author's pictures
     *
     * @return Picture[]
     */
    public function pics()
    {
        if(!$this->pics) {
            $this->pics = Picture::fetch($this);
        }

        return $this->pics;
    }


    /**
     * Get specific picture
     *
     * @param $filename
     * @return Picture
     */
    public function pic($filename)
    {
        $this->pics();
        if(isset($this->pics[$filename])){
            return $this->pics[$filename];
        }
    }


    /**
     * Get random picture
     *
     * @return Picture
     */
    public function random()
    {
        $random = array_rand($this->pics());
        if($random) {
            return $this->pics[$random];
        }
    }


    /**
     * Edit author's name
     *
     * @param string $name
     * @return bool
     *
     * @throws AuthorAlreadyExists
     * @throws \Exception
     */
    public function rename($name)
    {
        // format name
        $newname = ucfirst($name);

        // author already exists
        if(file_exists($this->album->path . DIRECTORY_SEPARATOR . $newname)) {
            throw new AuthorAlreadyExists;
        }

        // rename
        $done = rename($this->path, $this->album->path . DIRECTORY_SEPARATOR .  $newname);
        $done &= rename($this->cachepath, $this->album->cachepath . DIRECTORY_SEPARATOR . $newname);
        if(!$done) {
            throw new \Exception;
        }

        // update this author
        $this->open($newname);

        return true;
    }


    /**
     * Delete author
     */
    public function delete()
    {
        unlink($this->path);
        unlink($this->cachepath);
    }


    /**
     * Create new author
     *
     * @param Album $album
     * @param string $name
     * @return static
     *
     * @throws AuthorAlreadyExists
     * @throws \Exception
     */
    public static function create(Album &$album, $name)
    {
        // format name
        $newname = ucfirst($name);

        // author already exists
        if(file_exists($album->path . DIRECTORY_SEPARATOR . $newname)) {
            throw new AuthorAlreadyExists;
        }

        // rename
        $done = mkdir($album->path . DIRECTORY_SEPARATOR .  $newname, 0777, true);
        $done &= mkdir($album->cachepath . DIRECTORY_SEPARATOR . $newname, 0777, true);
        if(!$done) {
            throw new \Exception;
        }

        $author = new static($album, $newname);
        $album->authors[$author->name] = $author;

        return $author;
    }


    /**
     * Fetch album's authors
     *
     * @param Album $album
     * @return static[]
     */
    public static function fetch(Album $album)
    {
        $authors = [];
        foreach(glob($album->path . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR) as $folder) {
            $basename = basename($folder);
            $authors[$basename] = new static($album, $basename);
        }

        return $authors;
    }


    /**
     * Fetch one author
     *
     * @param Album $album
     * @param string $name
     * @return static
     */
    public static function one(Album $album, $name)
    {
        $folders = glob($album->path . DIRECTORY_SEPARATOR . $name, GLOB_ONLYDIR);
        if($folders) {
            return new static($album, $name);
        }
    }

}