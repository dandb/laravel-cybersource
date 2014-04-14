<?php

use Credibility\LaravelCybersource\Cybersource;
use LaravelCybersource\TestCase;
use \Mockery as m;

class CybersourceTest extends TestCase {

    private $mockRequester;

    private $cybersource;

    public function setUp()
    {
        parent::setUp();
        $this->mockRequester = m::mock('soapRequester');
        $this->cybersource = new Cybersource($this->mockRequester, $this->mockApp);
        $this->cybersource->app = $this->mockApp;
    }

    public function testCreateSubscriptionRequest()
    {
        $request = $this->cybersource->createSubscriptionRequest('123');

        $this->assertInstanceOf('Credibility\LaravelCybersource\models\CybersourceSOAPModel', $request);
        $this->assertNotNull($request->clientEnvironment);
        $this->assertNotNull($request->merchantID);
    }


}
