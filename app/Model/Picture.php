<?php

namespace App\Model;

class Picture
{

    /** @var string */
    public $path;

    /** @var string */
    public $name;


    /**
     * Open picture
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->name = basename($path);
    }

    /**
     * Get cache image if exists
     *
     * @return string
     */
    public function cache()
    {

    }

}