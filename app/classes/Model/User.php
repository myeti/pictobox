<?php

namespace Pictobox\Model;

use Colorium\Orm;

class User
{

    use Orm\Model;

    const GUEST = 0;
    const VIEWER = 1;
    const UPLOADER = 5;
    const ADMIN = 9;

    const PWD_MINLENGTH = 5;

    /** @var string */
    public $username;

    /** @var string */
    public $password;

    /** @var string */
    public $email;

    /** @var int */
    public $rank = self::VIEWER;


    /**
     * New user
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param int $rank
     */
    public function __construct($username = null, $password = null, $email = null, $rank = self::VIEWER)
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->rank = $rank;
    }


    /**
     * Weither user can upload
     *
     * @return bool
     */
    public function isUploader()
    {
        return $this->rank >= self::UPLOADER;
    }


    /**
     * Weither user is admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->rank >= self::ADMIN;
    }

}