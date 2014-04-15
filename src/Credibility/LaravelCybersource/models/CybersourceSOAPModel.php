<?php namespace Credibility\LaravelCybersource\models;

class CybersourceSOAPModel {

    private $data;

    private $runnable = array(
        'paySubscriptionUpdateService',
        'paySubscriptionRetrieveService',
        'paySubscriptionDeleteService',
    );

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

    public function __call($name, $args)
    {
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
        $xml = '<requestMessage xmlns="urn:schemas-cybersource-com:transaction-data-1.92">';
        $xml = $this->createNestedXML($xml, $this);
        $xml .= '</requestMessage>';

        return $xml;
    }

    private function createNestedXML($xml, $value)
    {
        if($value instanceof CybersourceSOAPModel) {
            foreach($value->data as $key => $subValue) {
                if($subValue instanceof CybersourceSOAPModel) {
                    $xml .= '<' . $key . '>';
                    $this->createNestedXML($xml, $subValue);
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