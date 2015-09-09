<?php namespace LaravelCybersource;

use Credibility\LaravelCybersource\SOAPClientFactory;
use \Mockery as m;

class SOAPClientFactoryTest extends TestCase {

    /** @var  SOAPClientFactory */
    protected $factory;

    public function setUp()
    {
        parent::setUp();
        $this->factory = new SOAPClientFactory($this->mockApp);
    }

    public function testGetInstanceReturnsSoapClient()
    {
        $client = $this->factory->getInstance();

        $this->assertInstanceOf('Credibility\LaravelCybersource\SOAPClient', $client);
    }


} 