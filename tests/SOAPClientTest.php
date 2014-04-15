<?php namespace LaravelCybersource;

use Credibility\LaravelCybersource\SOAPClient;
use LaravelCybersource\TestCase;
use \Mockery as m;

class SOAPClientTest extends TestCase {

    private $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = new SOAPClient($this->mockApp, null);
    }

    public function testConstruct()
    {
        //only need to test creation with WSDL
        $this->assertNotNull($this->client);
    }

    public function testGetInstance()
    {
        $timeout = 10;

        $contextOpts = array(
            'http' => array(
                'timeout' => $timeout
            )
        );

        $context = stream_context_create($contextOpts);

        $soapOts = array(
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
            'encoding' => 'utf-8',
            'exceptions' => true,
            'connection_timeout' => $timeout,
            'stream_context' => $context,
            'cache_wsdl' => WSDL_CACHE_MEMORY
        );

        $newClient = SOAPClient::getInstance($soapOts);
    }

    public function testAddWSSEToken()
    {
        $this->client->addWSSEToken();

        $headers = $this->client->__default_headers[0];

        $this->assertInstanceOf('SoapHeader', $headers);
        $this->assertEquals(SOAPClient::WSSE_NAMESPACE, $headers->namespace);
        $this->assertEquals('Security', $headers->name);
    }

} 