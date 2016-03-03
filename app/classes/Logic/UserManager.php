<?php

namespace Pictobox\Logic;

use Pictobox\Model\User;
use Pictobox\Service\Mail;
use Colorium\Web\Context;
use Colorium\Http\Response;
use Colorium\Stateful\Auth;

class UserManager
{


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
        if($ctx->user) {
            return Response::redirect('/');
        }

        // keep previous location
        $referer = $ctx->request->uri->param('from');

        return [
            'redirect' => $referer ?: $ctx->url('/')
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
        $ctx->user = Auth::login($user->rank, $user->id);
        $ctx->logger->info($user->username . ' logs in');

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
        $user = $ctx->user;
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
            if(strlen($password) < User::PWD_MINLENGTH) {
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

            $ctx->logger->info($user->username . ' profile is updated', $_POST);
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
        // keep session
        session_regenerate_id();

        return [
            'state' => (bool)Auth::valid()
        ];
    }


    /**
     * Log out user
     *
     * @param Context $ctx
     * @return Response\Redirect
     */
    public function logout(Context $ctx)
    {
        Auth::logout();
        $ctx->logger->info($ctx->user->username . ' logs out');

        return Response::redirect('/login');
    }


    /**
     * Send report to admin
     *
     * @access 1
     *
     * @param Context $ctx
     * @return array
     */
    public function report(Context $ctx)
    {
        // get post data
        $picture = $ctx->post('picture');

        // send mail
        $email = new Mail(APP_NAME . ' - Picture reporting');
        $email->content = $ctx->user->username . ' has reported the following picture : <br/>';
        $email->content .= strip_tags($picture);
        $email->send(ADMIN_EMAIL);

        return [
            'state' => true
        ];
    }


    /**
     * Send feedback to admin
     *
     * @access 1
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
        $email = new Mail(APP_NAME . ' - Feedback ' . $ctx->user->username);
        $email->from($ctx->user);
        if($album) {
            $email->content = '--- <br/>';
            $email->content .= strip_tags($album) . '<br/>';
            $email->content .= '--- <br/><br/>';
        }
        $email->content .= strip_tags($message);
        $email->content .= '<br/><br/>';
        $email->content .= $ctx->user->username;
        $email->send(ADMIN_EMAIL);

        return [
            'state' => true
        ];
    }

}