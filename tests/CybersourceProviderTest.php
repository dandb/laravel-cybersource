<?php

use Credibility\LaravelCybersource\Configs\Factory as ConfigsFactory;
use LaravelCybersource\TestCase;

class CybersourceProviderTest extends TestCase {

    public function testConfigs()
    {
        $configs = (new ConfigsFactory())->getFromConfigFile();

        $this->assertInstanceOf('Credibility\LaravelCybersource\Configs\ServerConfigs', $configs);
    }

} 