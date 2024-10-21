<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FilesService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind FilesService with a different key
        $this->app->bind('files.service', function ($app) {
            return new FilesService();
        });
    }

    // Other methods...
}
