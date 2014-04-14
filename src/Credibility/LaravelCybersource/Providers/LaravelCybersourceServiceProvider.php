<?php namespace Credibility\LaravelCybersource\Providers;

use Credibility\LaravelCybersource\Cybersource;
use Credibility\LaravelCybersource\SOAPClient;
use Credibility\LaravelCybersource\SOAPRequester;
use Illuminate\Support\ServiceProvider;

class LaravelCybersourceServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('credibility/laravel-cybersource');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind('cybersource', function($app) {
            $client = new SOAPClient();
            $requester = new SOAPRequester($client);
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