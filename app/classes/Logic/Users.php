<?php

namespace App\Logic;

use App\Model\User;
use App\Service\Mail;
use Colorium\App\Context;
use Colorium\Http\Response;
use Colorium\Stateful\Auth;

class Users
{

    /** @var int */
    protected $minLength = 5;


    /**
     * Login form
     *
     * @html users/login
     *
     * @param Context $self
     * @return array
     */
    public function login(Context $self)
    {
        // already logged in
        if($self->access->auth) {
            return Response::redirect('/');
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
     * @json
     *
     * @param Context $self
     * @return array
     */
    public function auth(Context $self)
    {
        // get form data
        list($username, $password) = $self->post('username', 'password');
        $username = ucfirst(strtolower($username));
        $password = sha1(PWD_SALT . $password);

        // look user up
        $user = User::one(['username' => $username, 'password' => $password]);
        if(!$user) {
            return [
                'state' => false,
                'message' => 'Identifiants incorrects'
            ];
        }

        // log user in
        Auth::login($user->rank, $user->id);

        return [
            'state' => true
        ];
    }


    /**
     * Edit user details
     *
     * @access 1
     * @json
     *
     * @param Context $self
     * @return array
     */
    public function edit(Context $self)
    {
        // get post data
        $changed = false;
        list($email, $password, $id, $rank, $username) = $self->post('email', 'password', 'id', 'rank', 'username');

        // get user
        $user = $self->access->user;
        if($user->isAdmin()) {
            $user = $id ? User::one(['id' => $id]) : new User;
            if($username != $user->username) {
                $changed = true;
                $username = htmlentities(strip_tags($username));
                $user->username = $username;
            }
            if($rank != $user->rank) {
                $changed = true;
                $user->rank = $rank;
            }
        }

        // edit email
        if($email != $user->email) {
            if(!filter_var($email, FILTER_SANITIZE_EMAIL)) {
                return [
                    'state' => false,
                    'message' => 'Adresse email invalide'
                ];
            }

            $changed = true;
            $user->email = $email;
        }

        // edit password
        if($password) {
            if(strlen($password) < $this->minLength) {
                return [
                    'state' => false,
                    'message' => 'Mot de passe trop court'
                ];
            }

            $changed = true;
            $user->password = sha1(PWD_SALT . $password);
        }

        // save user
        $user->save();

        // send confirmation mail
        if($changed) {
            $email = new Mail(APP_NAME . ' - Mise à jour de votre profil');
            $email->content = $self->templater->render('emails/user-updated', [
                'user' => $user,
                'password' => $password
            ]);
            $email->send($user->email);
        }

        return [
            'state' => true
        ];
    }


    /**
     * Check if user is still logged in
     *
     * @json
     *
     * @return array
     */
    public function ping()
    {
        return [
            'state' => (bool)Auth::valid()
        ];
    }


    /**
     * Log out user
     *
     * @return Response\Redirect
     */
    public function logout()
    {
        Auth::logout();
        return Response::redirect('/login');
    }


    /**
     * Send feedback to admin
     *
     * @param Context $self
     * @return array
     */
    public function feedback(Context $self)
    {
        // get post data
        $message = $self->post('message');
        if(!$message) {
            return [
                'state' => false,
                'message' => 'Message vide'
            ];
        }

        // send mail
        $email = new Mail(APP_NAME . ' - Feedback ' . $self->access->user->username);
        $email->content = htmlentities(strip_tags($message));
        $email->send(ADMIN_EMAIL);

        return [
            'state' => true
        ];
    }

}