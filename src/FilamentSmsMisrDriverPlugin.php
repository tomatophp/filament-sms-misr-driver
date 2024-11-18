<?php

namespace TomatoPHP\FilamentSmsMisrDriver;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentSmsMisrDriverPlugin implements Plugin
{

    public function getId(): string
    {
        return 'filament-sms-misr-driver';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): self
    {
        return new FilamentSmsMisrDriverPlugin;
    }
}
