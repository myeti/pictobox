<?php

namespace App\Model;

use Colorium\Http\Uri;

class Album
{

    /** @var string */
    public $name;

    /** @var string */
    public $path;

    /** @var int */
    public $year;

    /** @var int */
    public $month;

    /** @var string */
    public $monthName;

    /** @var int */
    public $day;

    /** @var array */
    protected $authors = [];

    /** @var array */
    protected $pics = [];

    /** @var string */
    protected $url;

    /** @var array */
    protected $monthNames = [
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

            if($month) {
                $this->monthName = $this->monthNames[(int)$month];
            }
        }
    }


    /**
     * Get author list
     *
     * @return array
     */
    public function authors()
    {
        if(!$this->authors) {
            foreach(glob($this->path . '/*') as $folder) {
                $this->authors[] = dirname($folder);
            }
        }

        return $this->authors;
    }


    /**
     * Fetch album pics
     *
     * @param string $author
     * @return Picture[]
     */
    public function pics($author = null)
    {
        if($author = ucfirst(strtolower($author))) {
            if(empty($this->pics[$author])) {
                foreach(glob($this->path . '/' . $author . '/*') as $file) {
                    $this->pics[$author][] = basename($file);
                }
            }

            return $this->pics[$author];
        }

        if(empty($this->pics)) {
            foreach(glob($this->path . '/*') as $author) {
                $this->pics(basename($author));
            }
        }

        return $this->pics;
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
     * Generate flat name
     *
     * #return string
     */
    public function flatname()
    {
        return Uri::sanitize($this->name);
    }


    /**
     * Generate url
     *
     * @return string
     */
    public function url()
    {
        return '/' . $this->year . '/' . $this->month . '/' . $this->day . '/' . $this->flatname();
    }

}