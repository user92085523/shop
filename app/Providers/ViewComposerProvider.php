<?php

namespace App\Providers;

use App\View\Composers\CurrentUserComposer;
use App\View\Composers\UriTreeComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', CurrentUserComposer::class);
        View::composer('*', UriTreeComposer::class);
    }
}
