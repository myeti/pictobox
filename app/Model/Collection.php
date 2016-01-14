<?php

namespace App\Model;

class Collection
{

    /** @var int */
    public $year;

    /** @var int */
    public $month;

    /** @var string */
    public $monthName;

    /** @var int */
    public $day;

    /** @var Album[] */
    public $albums = [];

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
     * Open collection
     *
     * @param array $albums
     * @param int $year
     * @param int $month
     * @param int $day
     */
    public function __construct(array $albums, $year = null, $month = null, $day = null)
    {
        $this->albums = $albums;
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;

        if($month) {
            $this->monthName = $this->monthNames[(int)$month];
        }
    }


    /**
     * Get year collections
     *
     * @return Collection[]
     */
    public function years()
    {
        $years = [];
        foreach($this->albums as $album) {
            if(!isset($years[$album->year])) {
                $years[$album->year] = $this->year($album->year);
            }
        }

        return $years;
    }


    /**
     * Filter by year
     *
     * @param $year
     * @return Collection
     */
    public function year($year)
    {
        $albums = [];
        foreach($this->albums as $album) {
            if($album->year == $year) {
                $albums[] = $album;
            }
        }

        return new static($albums, $year);
    }


    /**
     * Get month collections
     *
     * @return Collection[]
     */
    public function months()
    {
        $months = [];
        foreach($this->albums as $album) {
            if(!isset($months[$album->month])) {
                $months[$album->month] = $this->month($album->month);
            }
        }

        return $months;
    }


    /**
     * Filter by month
     *
     * @param int $month
     * @return Collection
     */
    public function month($month)
    {
        $albums = [];
        foreach ($this->albums as $album) {
            if($album->month == $month) {
                $albums[] = $album;
            }
        }

        return new static($albums, $this->year, $month);
    }


    /**
     * Get day collection
     *
     * @return Collection[]
     */
    public function days()
    {
        $days = [];
        foreach($this->albums as $album) {
            if(!isset($days[$album->day])) {
                $days[$album->day] = $this->day($album->day);
            }
        }

        return $days;
    }


    /**
     * Filter by day
     *
     * @param int $day
     * @return Collection
     */
    public function day($day)
    {
        $albums = [];
        foreach ($this->albums as $album) {
            if($album->day == $day) {
                $albums[] = $album;
            }
        }

        return new static($albums, $this->year, $this->month, $day);
    }


    /**
     * Count albums
     *
     * @return int
     */
    public function count()
    {
        return count($this->albums);
    }


    /**
     * Fetch albums
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return Collection
     */
    public static function albums($year = null, $month = null, $day = null)
    {
        $albums = [];
        $folders = glob(PICS_DIR . '/' . $year . $month . $day . '*', GLOB_ONLYDIR);
        foreach($folders as $folder) {
            $albums[] = new Album($folder);
        }

        return new static($albums, $year, $month, $day);
    }


    /**
     * Fetch one album
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $name
     * @return Album
     */
    public static function album($year, $month, $day, $name)
    {
        $collection = static::albums($year, $month, $day);
        foreach($collection->albums as $album) {
            if($album->flatname() == $name) {
                return $album;
            }
        }
    }

}