<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../vendor/components/jquery' => public_path('vendor/jquery'),
            __DIR__ . '/../../vendor/twbs/bootstrap/dist' => public_path('vendor/bootstrap/dist'),
            __DIR__ . '/../../vendor/datatables/datatables/media' => public_path('vendor/datatables')
        ], 'public');
    }
}
