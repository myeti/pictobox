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
        $this->logger = (new Logger('pictobox'))
            ->pushHandler(new FingersCrossedHandler(new StreamHandler(LOGS_DIR . 'errors.log'), Logger::ERROR))
            ->pushHandler(new FilterHandler(new StreamHandler(LOGS_DIR . 'info.log', Logger::INFO), [Logger::INFO]));

        // load config file
        $config = Spyc::YAMLLoad(__APP__ . '/config.yml');
        $this->merge($config['logics']);
        $this->events = $config['events'];
        $this->errors = $config['errors'];
    }


    /**
     * Guard handler
     *
     * @param Context $context
     */
    protected function guard(Context $context)
    {
        $context = parent::guard($context);

        // log user navigation
        if($context->logic->name != 'user_ping') {
            $http = null;
            $user = $context->user ? $context->user->username : 'guest';
            if($context->request->uri->path) {
                $http = ' -> ' . $context->request->method . ' ' . $context->request->uri->path;
            }
            $this->logger->info($user . ' is browsing logic "' . $context->logic->name . '"' . $http);
        }

        return $context;
    }

}