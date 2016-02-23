<?php

namespace App;

use Colorium\Web;
use Colorium\Web\Context;
use Monolog\Handler\SlackHandler;
use Monolog\Logger;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FilterHandler;
use App\Service\Spyc;

class Pictobox extends Web\App
{

    /**
     * Setup Pictobox
     */
    public function setup()
    {
        // set template directory
        $this->templater->directory = __APP__ . '/templates/';

        // set logger instance
        $this->logger = new Logger('pictobox');
        $this->logger->pushHandler(new FingersCrossedHandler(new StreamHandler(LOGS_DIR . 'errors.log'), Logger::ERROR));
        $this->logger->pushHandler(new FilterHandler(new StreamHandler(LOGS_DIR . 'info.log', Logger::INFO), [Logger::INFO]));

        // load config file
        $config = Spyc::YAMLLoad(__APP__ . '/config.yml');
        $this->merge($config['logics']);
        $this->events = $config['events'];
        $this->errors = $config['errors'];
    }


    /**
     * After handler
     *
     * @param Context $context
     */
    protected function after(Context $context = null)
    {
        // log user navigation
        if($context->logic->name != 'user_ping') {
            $user = $context->user ? $context->user->username : 'Guest';
            $message = $user . ' hits #' . $context->logic->name;
            if($context->request->uri->path) {
                $message .= ' on ' . $context->request->method . ' ' . $context->request->uri->path;
            }
            $this->logger->info($message, $_POST);
        }
    }

}