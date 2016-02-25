<?php

namespace App\Service;

use Monolog\Handler\AbstractHandler;
use Monolog\Logger;

class SlackLogger extends AbstractHandler
{

    /** @var string */
    protected $url;

    /** @var string */
    protected $channel;

    /** @var string */
    protected $botname;

    /** @var array */
    protected $records = [];


    /**
     * Create slack webhook handler
     *
     * @param string $url
     * @param string $channel
     * @param string $botname
     * @param int $level
     */
    public function __construct($url, $channel, $botname, $level = Logger::DEBUG)
    {
        parent::__construct($level);

        $this->url = $url;
        $this->channel = $channel;
        $this->botname = $botname;
    }


    /**
     * Handles a record.
     *
     * All records may be passed to this method, and the handler should discard
     * those that it does not want to handle.
     *
     * The return value of this function controls the bubbling process of the handler stack.
     * Unless the bubbling is interrupted (by returning true), the Logger class will keep on
     * calling further handlers in the stack with a given log record.
     *
     * @param  array $record The record to handle
     * @return Boolean true means that this handler handled the record, and that bubbling is not permitted.
     *                        false means the record was either not processed or that this handler allows bubbling.
     */
    public function handle(array $record)
    {
        $this->records[] = $record;
    }


    /**
     * Send logs in one go
     */
    public function __destruct()
    {
        // no log to send
        if(!$this->records) {
            return;
        }

        // format message
        $message = '';
        foreach($this->records as $record) {
            $message .= '[' . strtoupper($record['level_name']) . '] ' . $record['message'] . "\n";
        }

        // generate payload
        $payload = 'payload=' . json_encode([
            'text' => $message,
            'username' => $this->botname,
            'channel' => $this->channel
        ]);

        //open connection
        $curl = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);

        // execute and close
        curl_exec($curl);
        curl_close($curl);
    }

}