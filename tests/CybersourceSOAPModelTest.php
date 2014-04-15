<?php namespace LaravelCybersource;

use LaravelCybersource\TestCase;
use Credibility\LaravelCybersource\models\CybersourceSOAPModel;

class CybersourceSOAPModelTest extends TestCase {



    public function testGetWorks()
    {
        $model = new CybersourceSOAPModel();
        $model->test = 'test';

        $this->assertEquals('test', $model->test);
    }

    public function testEmptyGetIsFalse()
    {
        $model = new CybersourceSOAPModel();

        $this->assertFalse($model->notExists);
    }

    public function testCreateNestedSOAPModel()
    {
        $model = new CybersourceSOAPModel('PHP', phpversion(), $this->environment, $this->merchantId);
        $nested = new CybersourceSOAPModel();

        $model->nested = $nested;

        $this->assertEquals($nested, $model->nested);
        $this->assertFalse($nested->clientEnvironment);
        $this->assertFalse($nested->merchantID);
    }

    public function testToXMLCreatesWellFormedXML()
    {
        $model = new CybersourceSOAPModel('PHP', phpversion(), $this->environment, $this->merchantId);
        $nested = new CybersourceSOAPModel();

        $nested->testValue = 'test';
        $model->nested = $nested;

        $xml = $model->toXML();

        $this->assertStringStartsWith('<?xml', $xml);
    }


} 