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
     * @param Context $ctx
     * @return array
     */
    public function login(Context $ctx)
    {
        // already logged in
        if($ctx->access->auth) {
            return Response::redirect('/');
        }

        // keep previous location
        $referer = $ctx->request->uri->param('from');

        return [
            'redirect' => $referer ?: $ctx->uri('/')
        ];
    }


    /**
     * Attempt authentication
     *
     * @json
     *
     * @param Context $ctx
     * @return array
     */
    public function auth(Context $ctx)
    {
        // get form data
        list($username, $password) = $ctx->post('username', 'password');
        $username = ucfirst(strtolower($username));
        $password = sha1(PWD_SALT . $password);

        // look user up
        $user = User::one(['username' => $username, 'password' => $password]);
        if(!$user) {
            return [
                'state' => false,
                'message' => text('logic.user.username.invalid')
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
     * @param Context $ctx
     * @return array
     */
    public function edit(Context $ctx)
    {
        // get post data
        $changed = false;
        list($email, $password, $id, $rank, $username) = $ctx->post('email', 'password', 'id', 'rank', 'username');

        // get user
        $user = $ctx->user();
        if($user->isAdmin()) {
            $user = $id ? User::one(['id' => $id]) : new User;
            if($username != $user->username) {
                $changed = true;
                $username = strip_tags($username);
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
                    'message' => text('logic.user.email.invalid')
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
                    'message' => text('logic.user.password.invalid')
                ];
            }

            $changed = true;
            $user->password = sha1(PWD_SALT . $password);
        }

        // save user
        $user->save();

        // send confirmation mail
        if($changed) {
            $email = new Mail(APP_NAME . ' - ' . text('email.profile.title'));
            $email->content = $ctx->templater->render('emails/profile', [
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
     * @param Context $ctx
     * @return array
     */
    public function feedback(Context $ctx)
    {
        // get post data
        list($message, $album) = $ctx->post('message', 'album');
        if(!$message) {
            return [
                'state' => false,
                'message' => text('logic.feedback.empty')
            ];
        }

        // send mail
        $email = new Mail(APP_NAME . ' - Feedback ' . $ctx->user()->username);
        if($album) {
            $email->content .= '--- <br/>';
            $email->content .= strip_tags($album) . '<br/>';
            $email->content .= '--- <br/><br/>';
        }
        $email->content .= strip_tags($message);
        $email->content .= '<br/><br/>';
        $email->content .= $ctx->user()->username;
        $email->send(ADMIN_EMAIL);

        return [
            'state' => true
        ];
    }

}