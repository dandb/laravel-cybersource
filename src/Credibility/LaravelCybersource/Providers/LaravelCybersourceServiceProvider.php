<?php namespace Credibility\LaravelCybersource\Providers;

use Credibility\LaravelCybersource\Configs\Factory as ConfigsFactory;
use Credibility\LaravelCybersource\Cybersource;
use Credibility\LaravelCybersource\SOAPClient;
use Credibility\LaravelCybersource\SOAPClientFactory;
use Credibility\LaravelCybersource\SOAPRequester;
use Illuminate\Support\ServiceProvider;

class LaravelCybersourceServiceProvider extends ServiceProvider {



	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind('Credibility\LaravelCybersource\Cybersource', function() {
            $configs = (new ConfigsFactory())->getFromConfigFile();
            $client = new SOAPClient($configs, []);
            $factory = new SOAPClientFactory();
            $requester = new SOAPRequester($client, $factory);
            return new Cybersource($requester);
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