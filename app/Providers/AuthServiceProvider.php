<?php

namespace App\Providers;

use App\BookingHouse;
use App\Policies\BookingHousePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
      //   'App\Model' => 'App\Policies\ModelPolicy',
      User::class => UserPolicy::class,
        BookingHouse::class => BookingHousePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
       
    //  $this->app['auth']->viaRequest('api', function ($request){
    //     if($request->header('api_token')){
    //         return User::where('api_token', $request->header('api_token'))->first();
    //     }
    //  });
     $this->registerPolicies();
    }
}
