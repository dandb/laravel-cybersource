<?php namespace Credibility\LaravelCybersource\models;

class CybersourceSOAPModel {

    public function __construct($clientEnv = null, $merchantId = null)
    {
        $this->clientLibrary = 'PHP';
        $this->clientLibraryVersion = phpversion();
        $this->clientEnvironment = $clientEnv;
        $this->merchantID = $merchantId;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        if(isset($this->$name)) {
            return $this->$name;
        }
        return false;
    }

} 