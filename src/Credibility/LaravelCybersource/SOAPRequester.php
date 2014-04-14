<?php namespace Credibility\LaravelCybersource;
use Credibility\LaravelCybersource\models\CybersourceSOAPModel;
use Illuminate\Foundation\Application;

/**
 * Class SOAPRequester creates SOAP requests for Cybersource and uses
 * the $soapClient to send requests out to a specific url
 * @package Credibility\LaravelCybersource
 */
class SOAPRequester {

    private $soapClient;

    public function __construct($soapClient, Application $app)
    {
        $this->soapClient = $soapClient;

    }

    public function send(CybersourceSOAPModel $request, $location, $action, $version)
    {
        $xmlRequest = $this->convertToXMLRequest($request);

        return $this->soapClient->doRequest($xmlRequest, $location, $action, $version);
    }

    public function convertToXMLRequest(CybersourceSOAPModel $request)
    {
        $contextOpts = array(
            'http' => array(
                'timeout' => ''
            )
        );

        return $request;
    }

}