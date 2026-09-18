<?php

namespace TomatoPHP\FilamentSmsMisrDriver;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use TomatoPHP\FilamentSmsMisrDriver\Console\FilamentSmsMisrDriverInstall;

class FilamentSmsMisrDriverServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register generate command
        $this->commands([
            FilamentSmsMisrDriverInstall::class,
        ]);

        // Register Config file
        $this->mergeConfigFrom(__DIR__ . '/../config/filament-sms-misr-driver.php', 'filament-sms-misr-driver');

        // Publish Config
        $this->publishes([
            __DIR__ . '/../config/filament-sms-misr-driver.php' => config_path('filament-sms-misr-driver.php'),
        ], 'filament-sms-misr-driver-config');

        // Register Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publish Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'filament-sms-misr-driver-migrations');
        // Register views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-sms-misr-driver');

        // Publish Views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-sms-misr-driver'),
        ], 'filament-sms-misr-driver-views');

        // Register Langs
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-sms-misr-driver');

        // Publish Lang
        $this->publishes([
            __DIR__ . '/../resources/lang' => base_path('lang/vendor/filament-sms-misr-driver'),
        ], 'filament-sms-misr-driver-lang');

        // Register Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

    }

    public function boot(): void
    {
        try {
            // Settings saved from the settings hub win over the env based config, empty settings keep the config value.
            foreach ([
                'username' => 'sms_misr_username',
                'password' => 'sms_misr_password',
                'sender' => 'sms_misr_sender',
                'environment' => 'sms_misr_environment',
                'active' => 'sms_misr_active',
            ] as $config => $setting) {
                $value = setting($setting);

                if (filled($value)) {
                    Config::set("filament-sms-misr-driver.{$config}", $value);
                }
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }
}
