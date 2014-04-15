<?php namespace LaravelCybersource;

use Credibility\LaravelCybersource\models\CybersourceResponse;
use Credibility\LaravelCybersource\models\CybersourceSOAPModel;
use LaravelCybersource\TestCase;
use \Mockery as m;

class CybersourceResponseTest extends TestCase {

    public function testResponseDoesNotBreak()
    {
        $responseObj = new CybersourceResponse(false, array());

        $this->assertFalse($responseObj->isValid());
        $this->assertEmpty($responseObj->getResponseDetails());
    }


    public function testConstructionSetsValidity()
    {
        $responseObj = new CybersourceResponse(true, array());

        $this->assertTrue($responseObj->isValid());
    }


} 