<?php namespace Credibility\LaravelCybersource;

use BeSimple\SoapClient\SoapClient as BeSimpleSoapClient;
use Credibility\LaravelCybersource\Exceptions\CybersourceException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

/**
 * Class SOAPClient
 * @package Credibility\LaravelCybersource
 */
class SOAPClient extends BeSimpleSoapClient {

    public static $domVersion = '1.0';

    protected static $soapHeaderPreUser = '<SOAP-ENV:Header xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd"><wsse:Security SOAP-ENV:mustUnderstand="1"><wsse:UsernameToken><wsse:Username>';
    protected static $soapHeaderPrePassword = '</wsse:Username><wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">';
    protected static $soapHeaderFinal = '</wsse:Password></wsse:UsernameToken></wsse:Security></SOAP-ENV:Header>';

    protected $wsdl;
    protected $merchantId;
    protected $transactionId;

    /**
     * Constructs a client off of the
     * configured WSDL
     */
    public function __construct()
    {
        $this->wsdl = \Config::get('laravel-cybersource::cybersource.wsdl_endpoint');
        $this->merchantId = \Config::get('laravel-cybersource::cybersource.merchant_id');
        $this->transactionId = \Config::get('laravel-cybersource::cybersource.transaction_id');
        parent::__construct($this->wsdl, null);
    }

    /**
     * @param $request
     * @param $location
     * @param $action
     * @param $version
     * @param null $one_way
     * @return string
     * @throws Exceptions\CybersourceException
     */
    public function doRequest($request, $location, $action, $version, $one_way = null)
    {
        $header = static::$soapHeaderPreUser . $this->merchantId . static::$soapHeaderPrePassword . $this->transactionId . static::$soapHeaderFinal;

        $requestDOM = new \DOMDocument(static::$domVersion);
        $soapHeaderDOM = new \DOMDocument(static::$domVersion);

        try {
            $requestDOM->loadXML($request);
            $soapHeaderDOM->loadXML($soapHeaderDOM);

            $node = $requestDOM->importNode($soapHeaderDOM->firstChild, true);

            $requestDOM->firstChild->insertBefore($node, $requestDOM->firstChild->firstChild);

            $request = $requestDOM->saveXML();
        } catch (\DOMException $e) {
            \Log::error($e);
            throw new CybersourceException();
        }

        return parent::__doRequest($request, $location, $action, $version, $one_way);
    }


} 