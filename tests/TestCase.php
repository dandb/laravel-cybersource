<?php

namespace LaravelCybersource;

use \Mockery as m;
use Credibility\LaravelCybersource\Configs\Factory as ConfigsFactory;

class TestCase extends \PHPUnit_Framework_TestCase {

    protected $environment = 'testing';
    protected $merchantId = 'test-merchant-id';
    protected $merchantRefCode = 'test-merchant-code';
    protected $configs;

    public function setUp()
    {
        $this->configs = (new ConfigsFactory())->getFromConfigFile();
    }

    public function tearDown()
    {
        m::close();
    }

} 