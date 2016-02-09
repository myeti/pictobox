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
    protected $minLength = 6;


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
        list($email, $password, $id, $rank) = $self->post('email', 'password', 'id', 'rank');

        // get user
        $user = $self->access->user;
        $admin = $user->isAdmin();
        if($id and $admin) {
            $user = User::one(['id' => $id]);
        }

        // edit rank
        if($rank != $user->rank and $admin) {
            $user->rank = $rank;
        }

        // edit email
        if($email != $user->email) {
            if(!filter_var($email, FILTER_SANITIZE_EMAIL)) {
                return [
                    'state' => false,
                    'message' => 'Adresse email invalide'
                ];
            }

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

            $user->password = sha1(PWD_SALT . $password);
        }

        // save user
        $user->edit();

        // send confirmation mail
        $email = new Mail(APP_NAME . ' - Mise à jour de ton profil');
        $email->content = $self->templater->render('emails/user-updated', [
            'user' => $user,
            'password' => $password,
            'host' => $self->request->uri->host
        ]);

        $email->send('arkhen.exitium@gmail.com');

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

}