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

    public function testSendRequestReturnsCybersourceResponse()
    {
        $model = new CybersourceSOAPModel();
        $model->reasonCode = 100;
        $model->decision = 'ACCEPT';
        $this->mockRequester->shouldReceive('send')->andReturn($model);

        $response = $this->cybersource->sendRequest($model);

        $this->assertInstanceOf('Credibility\LaravelCybersource\models\CybersourceResponse', $response);
        $this->assertTrue($response->isValid());
    }

    public function testCreateNewSubscriptionRequestCreatesProperRequest()
    {
        $paymentToken = 'test123';
        $productTitle = 'Test Title';
        $amount = 100.00;

        $request = $this->cybersource->createNewSubscriptionRequest($paymentToken, $productTitle, $amount);

        $startDate = $request->recurringSubscriptionInfo->startDate;
        $autoRenew = $request->recurringSubscriptionInfo->automaticRenew;
        $frequency = $request->recurringSubscriptionInfo->frequency;

        $this->assertEquals($paymentToken, $request->paySubscriptionCreateService->paymentRequestID);
        $this->assertEquals($productTitle, $request->subscription->title);
        $this->assertEquals($amount, $request->recurringSubscriptionInfo->amount);
        $this->assertEquals('weekly', $frequency);
        $this->assertEquals(true, $autoRenew);
        $this->assertEquals(date('Ymd'), $startDate);
    }

    public function testCreateUpdateSubscriptionRequest()
    {
        $paymentToken = 'test123';
        $subscriptionId = 'testingSubs';

        $request = $this->cybersource->createUpdateSubscriptionRequest($subscriptionId, $paymentToken);

        $this->assertEquals('true', $request->paySubscriptionUpdateService->run);
        $this->assertEquals($paymentToken, $request->paySubscriptionUpdateService->paymentRequestID);
        $this->assertEquals($subscriptionId, $request->recurringSubscriptionInfo->subscriptionID);
    }

    public function testCreateCancelSubscriptionRequest()
    {
        $subId = 'testing123';

        $request = $this->cybersource->createCancelSubscriptionRequest($subId);

        $this->assertEquals('true', $request->paySubscriptionDeleteService->run);
        $this->assertEquals($subId, $request->recurringSubscriptionInfo->subscriptionID);
    }

}
