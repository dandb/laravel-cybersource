<?php namespace Credibility\LaravelCybersource;

/**
 * Class Cybersource
 * @package Credibility\LaravelCybersource
 */
class Cybersource {

    private $requester;

    public function __construct($requester)
    {
        $this->requester = $requester;
    }

    public function getSubscriptionStatus()
    {

    }

} 