<?php

namespace cotint\fileManager;

use Illuminate\Support\ServiceProvider;

class FileManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'fileManager');
        $this->loadMigrationsFrom(__DIR__.'/migrations/2017_10_09_055144_create_filemanager_table.php');
        $this->publishes([

           __DIR__.'/assets' => public_path('cotint/fileManager'),
        ], 'assets');

        $this->publishes([
           __DIR__.'/migrations' => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        $this->app->make('Cotint\fileManager\FileController');
    }
}
