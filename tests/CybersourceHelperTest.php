<?php

use Credibility\LaravelCybersource\CybersourceHelper;
use LaravelCybersource\TestCase;

class CybersourceHelperTest extends TestCase {

    public function testCsvToArray()
    {
        $csv = 'test,test2,test3,test4';
        $array = CybersourceHelper::csvToArray($csv);

        $this->assertEquals('test', $array[0]);
        $this->assertEquals('test2', $array[1]);
        $this->assertEquals('test3', $array[2]);
        $this->assertEquals('test4', $array[3]);
    }

    public function testArrayToCsv()
    {
        $array = array('test', 'test2', 'test3');
        $csv = CybersourceHelper::arrayToCsv($array);

        $this->assertEquals('test,test2,test3', $csv);
    }

    //TODO: add tests for signing

} 