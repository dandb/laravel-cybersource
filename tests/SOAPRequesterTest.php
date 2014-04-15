<?php namespace LaravelCybersource;

use Credibility\LaravelCybersource\models\CybersourceSOAPModel;
use Credibility\LaravelCybersource\SOAPRequester;
use LaravelCybersource\TestCase;
use \Mockery as m;

class SOAPRequesterTest extends TestCase {

    /**
     * @var SOAPRequester
     */
    private $soapRequester;
    private $mockClient;

    public function setUp()
    {
        parent::setUp();
        $this->mockClient = m::mock('SOAPClient');
        $this->soapRequester = new SOAPRequester($this->mockClient, $this->mockApp);
    }

    public function testConvertToModelCreatesCybersourceSOAPModel()
    {
        $obj = new \stdClass();
        $obj->requestID = '12345';
        $obj->decision = 'REJECT';

        $testModel = new CybersourceSOAPModel();
        $model = $this->soapRequester->convertToModel($testModel, $obj);

        $this->assertEquals('12345', $model->requestID);
        $this->assertEquals('REJECT', $model->decision);
    }

    public function testNestedConvertToModelCreatesCybersourceSOAPModel()
    {
        $obj = new \stdClass();
        $obj->requestID = '12345';
        $obj->decision = 'REJECT';

        $newObj = new \stdClass();
        $newObj->testReason = 101;

        $obj->reasonCode = $newObj;

        $testModel = new CybersourceSOAPModel();
        $model = $this->soapRequester->convertToModel($testModel, $obj);

        $this->assertEquals('12345', $model->requestID);
        $this->assertInstanceOf('Credibility\LaravelCybersource\models\CybersourceSOAPModel', $model->reasonCode);

    }

} 