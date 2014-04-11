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


} 