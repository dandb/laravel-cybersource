<?php namespace LaravelCybersource;

use Credibility\LaravelCybersource\SOAPClient;
use LaravelCybersource\TestCase;
use \Mockery as m;

class SOAPClientTest extends TestCase {

    public function testConstruct()
    {
        $client = new SOAPClient(null, $this->mockApp);

        //only need to test creation with WSDL
        $this->assertNotNull($client);
    }

    public function testGetInstance()
    {
        $client = new SOAPClient(null, $this->mockApp);

        $this->timeout = 10;

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

        $newClient = SOAPClient::getInstance($soapOts);
    }

} 