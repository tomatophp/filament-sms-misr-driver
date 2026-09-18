<?php

namespace TomatoPHP\FilamentSmsMisrDriver\Tests;

use TomatoPHP\FilamentSmsMisrDriver\Filament\Pages\SmsMisrSettingsPage;
use TomatoPHP\FilamentSmsMisrDriver\Settings\SmsMisrSettings;
use TomatoPHP\FilamentSmsMisrDriver\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can render SMS Misr Settings Page', function () {
    get(SmsMisrSettingsPage::getUrl())->assertSuccessful();
});

it('saves the SMS Misr settings', function () {
    livewire(SmsMisrSettingsPage::class)
        ->fillForm([
            'sms_misr_username' => 'saved-user',
            'sms_misr_password' => 'saved-pass',
            'sms_misr_sender' => 'TOMATO',
            'sms_misr_environment' => 'test',
            'sms_misr_active' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $settings = app(SmsMisrSettings::class);

    expect($settings->sms_misr_username)->toBe('saved-user')
        ->and($settings->sms_misr_password)->toBe('saved-pass')
        ->and($settings->sms_misr_sender)->toBe('TOMATO')
        ->and($settings->sms_misr_environment)->toBe('test')
        ->and($settings->sms_misr_active)->toBeTrue();
});

it('keeps the stored password when the password input is left blank', function () {
    $settings = app(SmsMisrSettings::class);
    $settings->sms_misr_username = 'stored-user';
    $settings->sms_misr_password = 'stored-pass';
    $settings->save();

    livewire(SmsMisrSettingsPage::class)
        ->fillForm([
            'sms_misr_password' => '',
            'sms_misr_sender' => 'RENAMED',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $settings = app(SmsMisrSettings::class)->refresh();

    expect($settings->sms_misr_password)->toBe('stored-pass')
        ->and($settings->sms_misr_sender)->toBe('RENAMED');
});

it('never sends the stored password to the browser', function () {
    $settings = app(SmsMisrSettings::class);
    $settings->sms_misr_username = 'visible-user';
    $settings->sms_misr_password = 'super-secret-password';
    $settings->save();

    $page = livewire(SmsMisrSettingsPage::class);

    // Neither the Livewire state nor the rendered HTML may carry the saved password.
    expect($page->get('data.sms_misr_password'))->toBeNull()
        ->and($page->get('data.sms_misr_username'))->toBe('visible-user');

    $page->assertDontSee('super-secret-password', escape: false);

    get(SmsMisrSettingsPage::getUrl())
        ->assertDontSee('super-secret-password', escape: false);
});
