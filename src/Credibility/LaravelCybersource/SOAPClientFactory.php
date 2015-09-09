<?php namespace Credibility\LaravelCybersource;

use Illuminate\Container\Container;

class SOAPClientFactory {

    /** @var Container */
    protected $app;

    public function __construct(Container $app)
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