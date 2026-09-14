<?php

use Illuminate\Support\Facades\Artisan;
use TomatoPHP\FilamentSmsMisrDriver\FilamentSmsMisrDriverServiceProvider;

it('boots the service provider', function () {
    expect(app()->getProviders(FilamentSmsMisrDriverServiceProvider::class))->not->toBeEmpty();
});

it('merges the package config', function () {
    expect(config()->has('filament-sms-misr-driver'))->toBeTrue()
        ->and(config('filament-sms-misr-driver'))->toBeArray();
});

it('registers the install command', function () {
    expect(Artisan::all())->toHaveKey('filament-sms-misr-driver:install');
});
