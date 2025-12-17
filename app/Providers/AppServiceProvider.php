<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Date;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar Carbon para PT-BR
        Date::setLocale('pt_BR');
        setlocale(LC_TIME, 'pt_BR.utf8', 'pt_BR', 'Portuguese_Brazil');
    }
}
