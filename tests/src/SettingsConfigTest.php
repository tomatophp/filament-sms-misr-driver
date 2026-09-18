<?php

namespace TomatoPHP\FilamentSmsMisrDriver\Tests;

use Illuminate\Support\Facades\DB;
use TomatoPHP\FilamentSmsMisrDriver\FilamentSmsMisrDriverServiceProvider;

function saveSmsMisrSetting(string $name, mixed $value): void
{
    DB::table('settings')->updateOrInsert(
        ['group' => 'sms-misr', 'name' => $name],
        ['payload' => json_encode($value), 'locked' => false],
    );
}

function bootSmsMisrProvider(): void
{
    (new FilamentSmsMisrDriverServiceProvider(app()))->boot();
}

it('loads the account credentials saved from the settings hub', function () {
    saveSmsMisrSetting('sms_misr_username', 'settings-user');
    saveSmsMisrSetting('sms_misr_password', 'settings-pass');
    saveSmsMisrSetting('sms_misr_sender', 'TOMATO');

    bootSmsMisrProvider();

    expect(config('filament-sms-misr-driver.username'))->toBe('settings-user')
        ->and(config('filament-sms-misr-driver.password'))->toBe('settings-pass')
        ->and(config('filament-sms-misr-driver.sender'))->toBe('TOMATO');
});

it('loads the environment saved from the settings hub', function () {
    saveSmsMisrSetting('sms_misr_environment', 'test');

    bootSmsMisrProvider();

    expect(config('filament-sms-misr-driver.environment'))->toBe('test');
});

it('keeps the env username when the setting is empty', function () {
    config()->set('filament-sms-misr-driver.username', 'user-from-env');
    saveSmsMisrSetting('sms_misr_username', '');

    bootSmsMisrProvider();

    expect(config('filament-sms-misr-driver.username'))->toBe('user-from-env');
});

it('turns the driver off from the settings hub', function () {
    saveSmsMisrSetting('sms_misr_active', false);

    bootSmsMisrProvider();

    expect(config('filament-sms-misr-driver.active'))->toBeFalse();
});
