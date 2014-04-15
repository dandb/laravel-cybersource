<?php namespace Credibility\LaravelCybersource\models;

class CybersourceSOAPModel {

    private $data;

    public function __construct($clientLibrary = null, $clientLibVersion = null, $clientEnv = null, $merchantId = null)
    {
        $this->clientLibrary = $clientLibrary;
        $this->clientLibraryVersion = $clientLibVersion;
        $this->clientEnvironment = $clientEnv;
        $this->merchantID = $merchantId;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if(isset($this->data[$name])) {
            return $this->data[$name];
        }
        return false;
    }

    public function toXML()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
        $xml = $this->createNestedArray($xml, $this);

        return $xml;
    }

    private function createNestedArray($xml, $value)
    {
        if($value instanceof CybersourceSOAPModel) {
            foreach($value->data as $key => $subValue) {
                if($subValue instanceof CybersourceSOAPModel) {
                    $xml .= '<' . $key . '>';
                    $this->createNestedArray($xml, $subValue);
                    $xml .= '</' . $key . '>';
                } else if(!is_null($subValue)) {
                    $xml .= '<' . $key . '>';
                    $xml .= $subValue;
                    $xml .= '</' . $key . '>';
                }
            }
        }
        return $xml;
    }

} 