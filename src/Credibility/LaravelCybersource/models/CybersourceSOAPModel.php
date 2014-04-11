<?php namespace Credibility\LaravelCybersource\models;

class CybersourceSOAPModel {

    public function __construct()
    {
        $this->clientLibrary = "PHP";
        $this->clientLibraryVersion = phpversion();
        $this->clientEnvironment = \App::environment();
        $this->merchantID = \Config::get('laravel-cybersource::cybersource.outbound_merchant_id');
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