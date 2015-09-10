<?php namespace Credibility\LaravelCybersource;

use Credibility\LaravelCybersource\Configs\Factory as ConfigsFactory;

class SOAPClientFactory {

    /**
     * Static getInstance Method for updating SOAP options
     * @param null $options
     * @return SOAPClient
     */
    public function getInstance(array $options = [])
    {
        $configs = (new ConfigsFactory())->getFromConfigFile();
        return new SOAPClient($configs, $options);
    }


} 