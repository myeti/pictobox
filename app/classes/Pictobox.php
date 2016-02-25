<?php

namespace App;

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
        $this->logger = new Logger('pictobox');
        $this->logger->pushHandler(new FingersCrossedHandler(new StreamHandler(LOGS_DIR . 'errors.log'), Logger::ERROR));
        $this->logger->pushHandler(new FilterHandler(new StreamHandler(LOGS_DIR . 'info.log', Logger::INFO), [Logger::INFO]));

        // set slack logger handler
        if(SLACK_WEBHOOK and SLACK_CHANNEL and SLACK_BOTNAME) {
            $this->logger->pushHandler(new FingersCrossedHandler(new SlackLogger(SLACK_WEBHOOK, SLACK_CHANNEL, SLACK_BOTNAME), Logger::ERROR));
        }

        // load config file
        $config = Spyc::YAMLLoad(__APP__ . '/config.yml');
        $this->merge($config['logics']);
        $this->events = $config['events'];
        $this->errors = $config['errors'];
    }


    /**
     * Route handler
     *
     * @param Context $context
     */
    protected function route(Context $context)
    {
        $context = parent::route($context);

        // log user navigation
        if($context->logic->name != 'user_ping') {

            $user = $context->user ? $context->user->username : 'Guest';
            $message = $user . ' hits #' . $context->logic->name;
            if($context->request->uri->path) {
                $message .= ' on ' . $context->request->method . ' ' . $context->request->uri->path;
            }

            $post = $_POST;
            unset($post['password']);
            $this->logger->info($message, $_POST);
        }

        return $context;
    }

}