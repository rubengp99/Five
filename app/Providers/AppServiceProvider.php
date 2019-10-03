<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    /**
         * Show the application chat room.
         *
         * @return \Illuminate\Contracts\Support\Renderable
         */
        
    public function boot()
    {
        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ChatKit', function() {
        return new \Chatkit\Chatkit([
            'instance_locator' => config('services.chatkit.locator'),
            'key' => config('services.chatkit.secret'),
        ]);
    });
    }
}
