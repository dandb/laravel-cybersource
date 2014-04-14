<?php

namespace LaravelCybersource;

use \Mockery as m;

class TestCase extends \PHPUnit_Framework_TestCase {

    protected $environment = 'testing';
    protected $merchantId = 'test-merchant-id';
    protected $mockApp;

    public function setUp()
    {
        $mockConfig = m::mock('Config');
        $mockConfig->shouldReceive('get')
            ->with('laravel-cybersource::cybersource.merchant_id')
            ->andReturn($this->merchantId);

        $this->mockApp = m::mock('Illuminate\Foundation\Application');

        $this->mockApp
            ->shouldReceive('environment')
            ->andReturn($this->environment);
        $this->mockApp
            ->shouldReceive('make')
            ->with('config')
            ->andReturn($mockConfig);
    }

    public function tearDown()
    {
        m::close();
    }

} 