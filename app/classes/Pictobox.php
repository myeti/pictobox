<?php

namespace App;

use Colorium\Http\Error\ServiceUnavailableException;
use Colorium\Stateful\Auth;
use Colorium\Web;
use Colorium\Web\Context;
use Monolog\Logger;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FilterHandler;
use App\Service\Spyc;
use App\Service\SlackLogger;

class Pictobox extends Web\App
{

    /**
     * Setup Pictobox
     */
    public function setup()
    {
        parent::setup();

        // set template directory
        $this->templater->directory = __APP__ . '/templates/';

        // set logger instance
        $this->setupLogger();

        // load config file
        $this->loadConfigFile();
    }


    /**
     * Setup logger
     */
    protected function setupLogger()
    {
        $this->logger = new Logger(APP_NAME);
        $this->logger->pushHandler(new FingersCrossedHandler(new StreamHandler(LOGS_DIR . 'errors.log'), Logger::ERROR));
        $this->logger->pushHandler(new FilterHandler(new StreamHandler(LOGS_DIR . 'info.log', Logger::INFO), [Logger::INFO]));

        if(SLACK_WEBHOOK and SLACK_CHANNEL and SLACK_BOTNAME) {
            $this->logger->pushHandler(new FingersCrossedHandler(new SlackLogger(SLACK_WEBHOOK, SLACK_CHANNEL, SLACK_BOTNAME), Logger::ERROR));
        }
    }


    /**
     * Open config file
     */
    protected function loadConfigFile()
    {
        $config = Spyc::YAMLLoad(__APP__ . '/config.yml');

        $this->merge($config['logics']);
        $this->events = $config['events'];
        $this->errors = $config['errors'];
    }


    /**
     * Execute handler
     *
     * @param Context $context
     */
    protected function execute(Context $context)
    {
        // log user nav
        $this->logUser($context);

        // site under maintenance
        $this->checkMaintenance($context);

        // execute context
        $context = parent::execute($context);

        // renew session
        $this->renewSession();

        // set http cookie for htaccess
        $this->setHttpCookie($context);

        return $context;
    }


    /**
     * Log user navigation
     *
     * @param Context $context
     */
    protected function logUser(Context $context)
    {
        // do not log user_ping route
        if($context->logic->name == 'user_ping') {
            return;
        }

        // define user name
        $user = $context->request->cli ? 'Cli' : 'Guest';
        if($context->user) {
            $user = $context->user->username;
        }

        // format message
        $message = $user . ' hits #' . $context->logic->name;
        if($context->request->uri->path) {
            $message .= ' on ' . $context->request->method . ' ' . $context->request->uri->path;
        }

        // write log with POST data
        $post = $_POST;
        unset($post['password']);
        $this->logger->info($message, $post);
    }


    /**
     * Raise maintenance
     *
     * @param Context $context
     */
    protected function checkMaintenance(Context $context)
    {
        $isNotAdmin = $context->user && !$context->user->isAdmin();
        if(!APP_LIVE and $context->logic->access and $isNotAdmin and $context->logic->name != 'error_maintenance') {
            throw new ServiceUnavailableException;
        }
    }


    /**
     * Renew user session if still logged in
     */
    protected function renewSession()
    {
        if(Auth::valid()) {
            session_regenerate_id();
        }
    }


    /**
     * Set HTTP cookie for direct pic access
     *
     * @param Context $context
     */
    protected function setHttpCookie(Context $context)
    {
        // set cookie if user is authenticated
        if($context->user) {
            $ttl = time() + 3600 * 24 * 30;
            setrawcookie(COOKIE_SALT_KEY, COOKIE_SALT_VALUE, $ttl, '/');
        }
        // destroy cookie
        else {
            setrawcookie(COOKIE_SALT_KEY, null, -1, '/');
        }
    }

}