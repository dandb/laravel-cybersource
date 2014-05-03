<?php namespace Credibility\LaravelCybersource;

use Illuminate\Foundation\Application;

class SOAPClientFactory {

    /** @var Illuminate\Foundation\Application */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Static getInstance Method for updating SOAP options
     * @param null $options
     * @return SOAPClient
     */
    public function getInstance($options = null)
    {
        return new SOAPClient($this->app, $options);
    }


} 