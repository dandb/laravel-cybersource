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
            ->with('laravel-cybersource::merchant_id')
            ->andReturn($this->merchantId);
        $mockConfig->shouldReceive('get')
            ->with('laravel-cybersource::wsdl_endpoint')
            ->andReturn('https://ics2wstest.ic3.com/commerce/1.x/transactionProcessor/CyberSourceTransaction_1.26.wsdl');
        $mockConfig->shouldReceive('get')
            ->with('laravel-cybersource::transaction_id')
            ->andReturn('test_trans_id');
        $mockConfig->shouldReceive('get')
            ->with('laravel-cybersource::timeout')
            ->andReturn(10);

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