<?php

namespace App;

use Colorium\Web;
use Colorium\Web\Context;
use Symfony\Component\Yaml\Yaml;

class Pictobox extends Web\App
{

    const CACHE_DIR = __ROOT__ . '/public/img/cache/';
    const CACHE_URL = '/img/cache/';


    /**
     * Setup Pictobox
     */
    public function setup()
    {
        // set template directory
        $this->templater->directory = __APP__ . '/templates/';

        // load config file
        $config = Yaml::parse(file_get_contents(__APP__ . '/config.yml'));
        $this->merge($config['logics']);
        $this->events = $config['events'];
        $this->errors = $config['errors'];
    }


    /**
     * Before handler
     *
     * @param Context $context
     */
    protected function before(Context $context)
    {
        // log user navigation
        if($context->user) {
            $this->logger->info('user ' . $context->user->username . ' is browsing [' . $context->logic->name . ': ' . $context->logic->http . ']');
        }
    }

}