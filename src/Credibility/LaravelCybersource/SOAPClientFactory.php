<?php namespace Credibility\LaravelCybersource;

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
    public function getInstance($options = [])
    {
        return new SOAPClient($this->app, $options);
    }


} 