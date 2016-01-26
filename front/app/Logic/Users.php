<?php

namespace App\Logic;

use App\Model\User;
use Colorium\App\Context;
use Colorium\Stateful\Auth;
use Colorium\Stateful\Flash;

class Users
{

    /** @var string */
    protected $salt = 'EfiM$&5/*.w64$yPM3d';

    /** @var int */
    protected $minLength = 6;


    /**
     * Login form
     *
     * @html users/login
     * @param Context $self
     * @return array
     */
    public function login(Context $self)
    {
        // already logged in
        if($self->auth->rank) {
            return $self::redirect('/');
        }

        // keep previous location
        $referer = $self->request->uri->param('from');

        return [
            'referer' => $referer,
            'error' => Flash::get('login.error')
        ];
    }


    /**
     * Attempt authentication
     *
     * @param Context $self
     * @return \Colorium\Http\Response\Redirect
     */
    public function auth(Context $self)
    {
        // get form data
        list($username, $password, $referer) = $self->post('username', 'password', 'referer');
        $username = ucfirst(strtolower($username));
        $password = sha1($this->salt . $password);

        // look user up
        $user = User::one(['username' => $username, 'password' => $password]);
        if(!$user) {
            Flash::set('login.error', 'Identifiants incorrects');
            if($referer) {
                $referer = '?from=' . $referer;
            }
            return $self::redirect('/login' . $referer);
        }

        // log user in
        Auth::login($user->rank, $user->id);
        return $self::redirect($referer ?: '/');
    }


    /**
     * Edit user details
     *
     * @access 1
     *
     * @param Context $self
     * @return \Colorium\Http\Response\Redirect
     */
    public function edit(Context $self)
    {
        // get post data
        list($email, $password, $id) = $self->post('email', 'password', 'id');

        // get user
        $user = $self->user;
        if($id and $self->user->rank == User::ADMIN) {
            $user = User::one(['id' => $id]);
        }

        // edit email
        if($email != $user->email and filter_var($email, FILTER_SANITIZE_EMAIL)) {
            $user->email = $email;
        }

        // edit password
        if($password and strlen($password) < $this->minLength) {
            $user->password = sha1($this->salt . $password);
        }

        // save user
        $user->edit();
        return $self::redirect('/');
    }


    /**
     * Log out user
     *
     * @param Context $self
     * @return \Colorium\Http\Response\Redirect
     */
    public function logout(Context $self)
    {
        Auth::logout();
        return $self::redirect('/login');
    }

}