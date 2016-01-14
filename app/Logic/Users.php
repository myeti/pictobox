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
     */
    public function login() {}


    /**
     * Attempt authentication
     *
     * @param Context $self
     * @return \Colorium\Http\Response\Redirect
     */
    public function authenticate(Context $self)
    {
        $username = $self->post('username');
        $username = ucfirst(strtolower($username));

        $password = $self->post('password');
        $password = sha1($this->salt . $password);

        $user = User::one(['username' => $username, 'password' => $password]);
        if($user) {
            $user->password = null;
            Auth::login($user->rank, $user);
            return $self::redirect('/');
        }

        Flash::set('login.error', 'Identifiants incorrects');
        return $self::redirect('/login');
    }


    /**
     * Password reset form
     *
     * @access 1
     * @html users/reset-password
     */
    public function resetPassword() {}


    /**
     * Attempt password reset
     *
     * @access 1
     *
     * @param Context $self
     * @return \Colorium\Http\Response\Redirect
     */
    public function submitNewPassword(Context $self)
    {
        $password = $self->post('password');
        if(strlen($password) < $this->minLength) {
            Flash::set('reset.error', 'Mot de passe trop court (min. 6 caractères)');
            return $self::redirect('/pwd-reset');
        }

        $password = sha1($this->salt . $password);
        $edited = User::edit(['password' => $password], ['id' => Auth::user()->id]);
        if(!$edited) {
            Flash::set('reset.error', 'Mot de passe incorrect (erreur inconnue)');
            return $self::redirect('/pwd-reset');
        }

        Flash::set('success', 'Mot de passe modifié avec succès');
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