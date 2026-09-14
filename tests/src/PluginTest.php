<?php

use Filament\Facades\Filament;
use TomatoPHP\FilamentSmsMisrDriver\FilamentSmsMisrDriverPlugin;

it('makes the plugin with its id', function () {
    expect(FilamentSmsMisrDriverPlugin::make())
        ->toBeInstanceOf(FilamentSmsMisrDriverPlugin::class)
        ->getId()->toBe('filament-sms-misr-driver');
});

it('registers the plugin on the panel', function () {
    expect(Filament::getPanel('admin')->getPlugin('filament-sms-misr-driver'))
        ->toBeInstanceOf(FilamentSmsMisrDriverPlugin::class);
});
