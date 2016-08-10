<?php

namespace Purwandi\Responder;

use Illuminate\Support\ServiceProvider;

class ResponderServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('responder', function ($app) {
            return new Responder;
        });
    }
}
