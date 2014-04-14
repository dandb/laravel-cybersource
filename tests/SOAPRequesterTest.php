<?php namespace LaravelCybersource;

use Credibility\LaravelCybersource\models\CybersourceSOAPModel;
use Credibility\LaravelCybersource\SOAPRequester;
use LaravelCybersource\TestCase;
use \Mockery as m;

class SOAPRequesterTest extends TestCase {

    private $soapRequester;
    private $mockClient;

    public function setUp()
    {
        parent::setUp();
        $this->mockClient = m::mock('SOAPClient');
        $this->soapRequester = new SOAPRequester($this->mockClient, $this->mockApp);
    }

    public function testConvertToXMLRequestNotNull()
    {
        $model = new CybersourceSOAPModel($this->environment, $this->merchantId);
        $model->test = 'test';

        $newRequest = $this->soapRequester->convertToXMLRequest($model);

        $this->assertNotNull($newRequest);
    }

    public function testHeadersCreatedProperly()
    {

    }


} 