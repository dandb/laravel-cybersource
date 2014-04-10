<?php namespace Credibility\LaravelCybersource;

use BeSimple\SoapClient\SoapClient as BeSimpleSoapClient;
use Illuminate\Support\Facades\Config;

/**
 * Class SOAPClient
 * @package Credibility\LaravelCybersource
 */
class SOAPClient extends BeSimpleSoapClient {

    private $wsdl;

    public function __construct()
    {
        $this->wsdl = \Config::get('laravel-cybersource::cybersource.wsdl_endpoint');
        parent::__construct($this->wsdl, null);
    }

    public function doRequest($request, $location, $action, $version, $one_way = null)
    {
        


        return parent::__doRequest($request, $location, $action, $version, $one_way);
    }


} 