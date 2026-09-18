<?php

namespace TomatoPHP\FilamentSmsMisrDriver\Tests;

use Filament\Notifications\Notification;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use TomatoPHP\FilamentSmsMisrDriver\Services\SmsMisrDriver;
use TomatoPHP\FilamentSmsMisrDriver\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can send notification using Filament Native Notification', function () {
    $user = User::factory()->create(['phone' => '+201333333333']);

    Notification::make()
        ->title('Test title')
        ->body('Test body')
        ->icon('heroicon-o-bell')
        ->info()
        ->sendUse($user, SmsMisrDriver::class);

    Http::assertSent(fn (Request $request): bool => $request->data()['mobile'] === '201333333333');

    assertDatabaseHas('notifications_logs', [
        'title' => 'Test title',
        'description' => 'Test body',
        'provider' => 'sms-misr',
        'type' => 'info',
    ]);
});
