<?php namespace Credibility\LaravelCybersource\Providers;

use Credibility\LaravelCybersource\Cybersource;
use Credibility\LaravelCybersource\SOAPClient;
use Credibility\LaravelCybersource\SOAPClientFactory;
use Credibility\LaravelCybersource\SOAPRequester;
use Illuminate\Support\ServiceProvider;

class LaravelCybersourceServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind('Credibility\LaravelCybersource\Cybersource', function($app) {
            $client = new SOAPClient($app);
            $factory = new SOAPClientFactory($app);
            $requester = new SOAPRequester($client, $app, $factory);
            return new Cybersource($requester, $app);
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('cybersource');
	}

}