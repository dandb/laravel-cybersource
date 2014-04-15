<?php

use Credibility\LaravelCybersource\Cybersource;
use Credibility\LaravelCybersource\models\CybersourceSOAPModel;
use LaravelCybersource\TestCase;
use \Mockery as m;

class CybersourceTest extends TestCase {

    private $mockRequester;
    /**
     * @var Cybersource
     */
    private $cybersource;

    public function setUp()
    {
        parent::setUp();
        $this->mockRequester = m::mock('soapRequester');
        $this->cybersource = new Cybersource($this->mockRequester, $this->mockApp);
        $this->cybersource->app = $this->mockApp;
    }

    public function testCreateNewRequest()
    {
        $model = $this->cybersource->createNewRequest();

        $this->assertNotNull($model);
        $this->assertInstanceOf('Credibility\LaravelCybersource\models\CybersourceSOAPModel', $model);
    }

    public function testCreateSubscriptionRequest()
    {
        $request = $this->cybersource->createSubscriptionStatusRequest('123');

        $this->assertInstanceOf('Credibility\LaravelCybersource\models\CybersourceSOAPModel', $request);
        $this->assertNotNull($request->clientEnvironment);
        $this->assertNotNull($request->merchantID);

        $this->assertEquals('true', $request->paySubscriptionRetrieveService->run);
        $this->assertEquals('123', $request->recurringSubscriptionInfo->subscriptionID);
    }

    public function testGetSubscriptionStatusReturnsCybersourceResponse()
    {
        $model = new CybersourceSOAPModel();
        $model->reasonCode = 100;
        $model->decision = 'ACCEPT';
        $this->mockRequester->shouldReceive('send')->andReturn($model);

        $response = $this->cybersource->getSubscriptionStatus('123');

        $this->assertInstanceOf('Credibility\LaravelCybersource\models\CybersourceResponse', $response);
        $this->assertTrue($response->isValid());
    }



}
