<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Added by Vicsante Aseniero
 * Fix Laravel Specified key was too long error
 */
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        /**
         * Added by Vicsante Aseniero
         * Fix Laravel Specified key was too long error
         */
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
