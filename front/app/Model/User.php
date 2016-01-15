<?php

namespace App\Model;

use Colorium\Orm;

class User
{

    use Orm\Model;

    const GUEST = 0;
    const NORMAL = 1;
    const ADMIN = 9;

    /** @var string */
    public $username;

    /** @var string */
    public $password;

    /** @var string */
    public $email;

    /** @var int */
    public $rank = 1;


    /**
     * New user
     *
     * @param string $username
     * @param string $password
     * @param int $rank
     */
    public function __construct($username = null, $password = null, $rank = 1)
    {
        $this->username = $username;
        $this->password = $password;
        $this->rank = $rank;
    }

}