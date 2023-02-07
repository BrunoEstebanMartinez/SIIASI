<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         View::share('ANIO');
         View::share('histPeriodos');
         View::share('cartPart2');
        View::share('catSEU');
        View::share('regCatDestin');
        View::share('regCatAsuntos');
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
