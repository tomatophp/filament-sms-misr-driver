<?php

namespace TomatoPHP\FilamentSmsMisrDriver;

use Illuminate\Support\ServiceProvider;


class FilamentSmsMisrDriverServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register generate command
        $this->commands([
           \TomatoPHP\FilamentSmsMisrDriver\Console\FilamentSmsMisrDriverInstall::class,
        ]);
 
        //Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/filament-sms-misr-driver.php', 'filament-sms-misr-driver');
 
        //Publish Config
        $this->publishes([
           __DIR__.'/../config/filament-sms-misr-driver.php' => config_path('filament-sms-misr-driver.php'),
        ], 'filament-sms-misr-driver-config');
 
        //Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
 
        //Publish Migrations
        $this->publishes([
           __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'filament-sms-misr-driver-migrations');
        //Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-sms-misr-driver');
 
        //Publish Views
        $this->publishes([
           __DIR__.'/../resources/views' => resource_path('views/vendor/filament-sms-misr-driver'),
        ], 'filament-sms-misr-driver-views');
 
        //Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-sms-misr-driver');
 
        //Publish Lang
        $this->publishes([
           __DIR__.'/../resources/lang' => base_path('lang/vendor/filament-sms-misr-driver'),
        ], 'filament-sms-misr-driver-lang');
 
        //Register Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
 
    }

    public function boot(): void
    {
        //you boot methods here
    }
}
