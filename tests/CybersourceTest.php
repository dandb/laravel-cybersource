<?php

use LaravelCybersource\TestCase;
use Mockery as m;

class CybersourceTest extends TestCase {

    private $mockRequester;

    public function setUp()
    {
        parent::setUp();
        $this->mockRequester = m::mock('soapRequester');

    }


    public function testSuccess()
    {
        $this->assertEquals(2, 1+1);
    }

}
