<?php

namespace LaravelCybersource;

use \Mockery as m;

class TestCase extends \PHPUnit_Framework_TestCase {

    protected $mockClient;

    public function setUp()
    {
        $this->mockClient = m::mock('mockSoapClient');
    }

    public function tearDown()
    {
        m::close();
    }

} 