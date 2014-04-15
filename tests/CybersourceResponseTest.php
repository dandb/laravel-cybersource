<?php namespace LaravelCybersource;

use Credibility\LaravelCybersource\Exceptions\CybersourceException as CybersourceException;
use Credibility\LaravelCybersource\models\CybersourceResponse;
use Credibility\LaravelCybersource\models\CybersourceSOAPModel;
use LaravelCybersource\TestCase;
use \Mockery as m;

class CybersourceResponseTest extends TestCase {

    public function testResponseDoesNotBreak()
    {
        $model = $this->createModel(101, 'REJECT');
        $responseObj = new CybersourceResponse($model);

        $this->assertFalse($responseObj->isValid());
        $this->assertNotEmpty($responseObj->getDetails());
    }

    public function testConstructionSetsValidity()
    {
        $model = $this->createModel(100, 'ACCEPT');
        $responseObj = new CybersourceResponse($model);

        $this->assertTrue($responseObj->isValid());
    }

    public function testEmptyCodeThrowsError()
    {
        $this->setExpectedException('Credibility\LaravelCybersource\Exceptions\CybersourceException', 'Response Code Not Provided');
        $model = $this->createModel(null, null);
        $responseObj = new CybersourceResponse($model);
    }

    public function testEmptyDecisionThrowsError()
    {
        $this->setExpectedException('Credibility\LaravelCybersource\Exceptions\CybersourceException', 'Decision Not Provided');
        $model = $this->createModel(100, null);
        $responseObj = new CybersourceResponse($model);
    }

    public function testInvalidCodeThrowsError()
    {
        $this->setExpectedException('Credibility\LaravelCybersource\Exceptions\CybersourceException', 'Invalid Response Code Provided');
        $model = $this->createModel(0, 'ACCEPT');
        $responseObj = new CybersourceResponse($model);
    }

    private function createModel($code, $decision)
    {
        $model = new CybersourceSOAPModel();
        $model->reasonCode = $code;
        $model->decision = $decision;

        return $model;
    }

}