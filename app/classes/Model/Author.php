<?php

namespace App\Model;

class Author
{

    /** @var string */
    public $path;

    /** @var string */
    public $cachepath;

    /** @var string */
    public $name;

    /** @var Album */
    public $album;

    /** @var Picture[] */
    protected $pics = [];


    /**
     * Get author
     *
     * @param Album $album
     * @param string $path
     */
    public function __construct(Album $album, $path)
    {
        $this->album = $album;
        $this->path = $path;
        $this->name = basename($path);

        $this->cachepath = $album->cachepath . DIRECTORY_SEPARATOR . $this->name;
    }


    /**
     * Get pics
     *
     * @return Picture[]
     */
    public function pics()
    {
        if(!$this->pics) {
            $folder = $this->album->path . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR;
            foreach(glob($folder . '*.{jpg,JPG,jpeg,JPEG}', GLOB_BRACE) as $file) {
                $pic = new Picture($this, $file);
                $this->pics[$pic->name] = $pic;
            }
        }

        return $this->pics;
    }


    /**
     * Get pic
     *
     * @param string $name
     * @return Picture
     */
    public function pic($name)
    {
        $this->pics();

        return isset($this->pics[$name])
            ? $this->pics[$name]
            : null;
    }


    /**
     * Get random pic
     *
     * @return Picture
     */
    public function random()
    {
        $this->pics();
        $random = array_rand($this->pics);
        return $this->pics[$random];
    }

}