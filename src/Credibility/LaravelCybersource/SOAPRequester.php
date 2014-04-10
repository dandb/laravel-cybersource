<?php namespace Credibility\LaravelCybersource;

/**
 * Class SOAPRequester creates SOAP requests for Cybersource and uses
 * the $soapClient to send requests out to a specific url
 * @package Credibility\LaravelCybersource
 */
class SOAPRequester {

    private $soapClient;

    public function __construct($soapClient)
    {
        $this->soapClient = $soapClient;
    }

    public function send($request)
    {

    }

    protected function createRequest($request)
    {

    }

} 