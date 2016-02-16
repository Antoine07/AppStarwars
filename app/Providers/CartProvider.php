<?php

namespace App\Providers;

use App\Cart\Cart;
use Illuminate\Support\ServiceProvider;

class CartProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Cart\IStorage', 'App\Cart\SessionStorage');

        $this->app->singleton(Cart::class, function ($app) {
            return new Cart($app['App\Cart\IStorage']);
        });
    }
}
