<?php

namespace App\Service;

use App\Model\User;

class Mail
{

    /** @var string */
    public $from;

    /** @var string */
    public $subject;

    /** @var string */
    public $content;


    /**
     * Create message
     * @param string $subject
     * @param string $content
     */
    public function __construct($subject, $content = null)
    {
        $this->from = APP_NAME . ' <' . APP_EMAIL . '>';
        $this->subject = $subject;
        $this->content = $content;
    }


    /**
     * Set from user
     *
     * @param User $user
     */
    public function from(User $user)
    {
        $this->from = $user->username . ' <' . $user->email . '>';
    }


    /**
     * Send email
     *
     * @param string $to
     * @return bool
     */
    public function send($to)
    {
        $headers =
            'From: ' . $this->from . "\r\n" .
            'Reply-To: ' . $this->from . "\r\n" .
            'MIME-Versio: 1.0' . "\r\n" .
            'Content-type: text/html; charset=UTF-8' . "\r\n"
        ;

        return mail($to, $this->subject, $this->content, $headers);
    }

}