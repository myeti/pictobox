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
        if($self->access->auth) {
            return $self::redirect('/');
        }

        // keep previous location
        $referer = $self->request->uri->param('from');

        return [
            'redirect' => $referer ?: $self->request->uri->make('/')
        ];
    }


    /**
     * Attempt authentication
     *
     * @param Context $self
     * @return \Colorium\Http\Response\Json
     */
    public function auth(Context $self)
    {
        // get form data
        list($username, $password) = $self->post('username', 'password');
        $username = ucfirst(strtolower($username));
        $password = sha1($this->salt . $password);

        // look user up
        $user = User::one(['username' => $username, 'password' => $password]);
        if(!$user) {
            return Context::json([
                'state' => false,
                'message' => 'Identifiants incorrects'
            ]);
        }

        // log user in
        Auth::login($user->rank, $user->id);

        return Context::json([
            'state' => true
        ]);
    }


    /**
     * Edit user details
     *
     * @access 1
     *
     * @param Context $self
     * @return \Colorium\Http\Response\Json
     */
    public function edit(Context $self)
    {
        // get post data
        list($email, $password, $id) = $self->post('email', 'password', 'id');

        // get user
        $user = $self->user;
        if($id and $self->access->user->rank == User::ADMIN) {
            $user = User::one(['id' => $id]);
        }

        // edit email
        if($email != $user->email) {
            if(!filter_var($email, FILTER_SANITIZE_EMAIL)) {
                return Context::json([
                    'state' => false,
                    'message' => 'Adresse email invalide'
                ]);
            }

            $user->email = $email;
        }

        // edit password
        if($password) {
            if(strlen($password) < $this->minLength) {
                return Context::json([
                    'state' => false,
                    'message' => 'Mot de passe trop court'
                ]);
            }

            $user->password = sha1($this->salt . $password);
        }

        // save user
        $user->edit();

        return Context::json([
            'state' => true
        ]);
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