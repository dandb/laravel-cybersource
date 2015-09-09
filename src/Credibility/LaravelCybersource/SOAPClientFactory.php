<?php namespace Credibility\LaravelCybersource;

use Illuminate\Foundation\Application;

class SOAPClientFactory {

    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Static getInstance Method for updating SOAP options
     * @param null $options
     * @return SOAPClient
     */
    public function getInstance(array $options = [])
    {
        return new SOAPClient($this->app, $options);
    }


} 