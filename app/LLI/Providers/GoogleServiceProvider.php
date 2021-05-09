<?php

namespace LocalheroPortal\LLI\Providers;

use Google_Client;
use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Google_Client', function($app){
            $client = new Google_Client([
            'api_format_v2' => true,
            ]);
            $client->setAuthConfig(__DIR__ . '/client.json');
            $client->setApplicationName('Localhero Portal');
            $client->setIncludeGrantedScopes(true);
            $client->setRedirectUri(url('/googleoauth'));
            $client->setAccessType('offline');
            $client->setScopes([
                "https://www.googleapis.com/auth/plus.business.manage",
            ]);
            return $client;
        });
    }
}