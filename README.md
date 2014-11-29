laravel-cybersource
===================
[![Build Status](https://travis-ci.org/credibility/laravel-cybersource.svg?branch=master)](https://travis-ci.org/credibility/laravel-cybersource)
[![Coverage Status](https://coveralls.io/repos/credibility/laravel-cybersource/badge.png)](https://coveralls.io/r/credibility/laravel-cybersource)
[![Packagist](http://img.shields.io/packagist/v/credibility/laravel-cybersource.svg)](https://packagist.org/packages/credibility/laravel-cybersource)

This package wraps the Cybersource SOAP API in a convenient, easy to use package for Laravel.

## Installation

Install using composer:

    "require": {
      "credibility/laravel-cybersource": "dev-master"  
    }
    
See [Packagist](https://packagist.org/packages/credibility/laravel-cybersource) for latest version 

Then, in `config/app.php`, add the following to the service providers array.

    array(
       ...
      'Credibility\LaravelCybersource\Providers\LaravelCybersourceServiceProvider',
    )
    
Finally, in `config/app.php`, add the following to the facades array.

    array(
        ...
        'Cybersource'     => 'Credibility\LaravelCybersource\Facades\Cybersource',
    )

## Usage

Example usage using Facade:
    
    $response = Cybersource::createSubscription(
        $paymentToken,
        $productId,
        $productTotal,
        $frequency
    );
    
    if($response->isValid()) {
        $responseDetails = $response->getDetails();
        echo $responseDetails['paySubscriptionCreateReply']['subscriptionID'];
    } else {
        echo $response->error();
    }
    



