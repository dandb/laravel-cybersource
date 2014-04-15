<?php namespace Credibility\LaravelCybersource;

use Credibility\LaravelCybersource\Exceptions\CybersourceConnectionException;
use Credibility\LaravelCybersource\Exceptions\CybersourceException;
use Credibility\LaravelCybersource\models\CybersourceSOAPModel;
use Illuminate\Foundation\Application;

/**
 * Class SOAPRequester creates SOAP requests for Cybersource and uses
 * the $soapClient to send requests out to a specific url
 * @package Credibility\LaravelCybersource
 */
class SOAPRequester {

    /**
     * @var Illuminate\Foundation\Application
     */
    public $app;
    public $soapClient;
    public $timeout;

    public function __construct($soapClient, Application $app)
    {
        $this->app = $app;
        $this->soapClient = $soapClient;
        $this->timeout = $this->app->make('config')->get('laravel-cybersource::timeout');
    }

    public function send(CybersourceSOAPModel $request, $location)
    {
        $xmlRequest = $this->convertToXMLRequest($request);
        $xmlResponse = $this->soapClient->runTransaction($xmlRequest);
//        $xmlResponse = $this->soapClient->doRequest($xmlRequest, $location);

        return $this->convertToModel($xmlResponse);
    }

    public function convertToXMLRequest(CybersourceSOAPModel $request)
    {
        $contextOpts = array(
            'http' => array(
                'timeout' => $this->timeout
            )
        );

        $context = stream_context_create($contextOpts);

        $soapOts = array(
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
            'encoding' => 'utf-8',
            'exceptions' => true,
            'connection_timeout' => $this->timeout,
            'stream_context' => $context,
            'cache_wsdl' => WSDL_CACHE_MEMORY
        );

        try {
            $this->soapClient = SOAPClient::getInstance($soapOts);
        } catch(\SoapFault $sf) {
            throw new CybersourceConnectionException($sf->getMessage(), $sf->getCode());
        }

        $this->soapClient->addWSSEToken();

        return $request->toXML();
    }

    public function convertToModel($xmlResponse)
    {
        var_dump($xmlResponse);exit;
    }

}