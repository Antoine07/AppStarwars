<?php

namespace App\Providers;

use App\Customer;
use App\Policies\CustomerPolicy;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
      /*  'App\Model' => 'App\Policies\ModelPolicy',*/
        Customer::class => CustomerPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
/*
        $gate->define('show-customer', function ($user, $id) {

            return ($user->id == $id && in_array($user->role, ['visitor', 'administrator']));
        });*/
    }

}
