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
<<<<<<< HEAD
         View::share('responseAI');
         View::share('link');
=======
         View::share('cartPart2');
        View::share('catSEU');
        View::share('regCatDestin');
        View::share('regCatAsuntos');
>>>>>>> 16305e8a577e18fdc3bf29ddadcab125d75cea1b
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
