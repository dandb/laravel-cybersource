<?php namespace LaravelCybersource;

use Credibility\LaravelCybersource\models\CybersourceResponse;
use Credibility\LaravelCybersource\models\CybersourceSOAPModel;
use LaravelCybersource\TestCase;
use \Mockery as m;

class CybersourceResponseTest extends TestCase {

    public function testResponseDoesNotBreak()
    {
        $responseObj = new CybersourceResponse(false, array('code' => 100));

        $this->assertFalse($responseObj->isValid());
        $this->assertNotEmpty($responseObj->getResponseDetails());
    }

    public function testConstructionSetsValidity()
    {
        $responseObj = new CybersourceResponse(true, array('code' => 100));

        $this->assertTrue($responseObj->isValid());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Response Code Not Provided
     */
    public function testEmptyCodeThrowsError()
    {
        $responseObj = new CybersourceResponse(false, array());
    }


} 