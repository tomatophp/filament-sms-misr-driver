<?php

namespace TomatoPHP\FilamentSmsMisrDriver\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use TomatoPHP\FilamentAlerts\Facades\FilamentAlerts;
use TomatoPHP\FilamentSmsMisrDriver\Jobs\NotifySmsMisrJob;
use TomatoPHP\FilamentSmsMisrDriver\Services\SmsMisr;
use TomatoPHP\FilamentSmsMisrDriver\Services\SmsMisrDriver;
use TomatoPHP\FilamentSmsMisrDriver\Tests\Models\NotificationsTemplate;
use TomatoPHP\FilamentSmsMisrDriver\Tests\Models\User;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

it('posts the SMS to the SMS Misr API v2 endpoint', function () {
    dispatch(new NotifySmsMisrJob([
        'phone' => '+20 100 000 0000',
        'message' => 'Your order is ready',
        'title' => 'Order ready',
    ]));

    Http::assertSent(function (Request $request): bool {
        expect($request->url())->toBe('https://smsmisr.com/api/SMS/')
            ->and($request->data())->toBe([
                'environment' => '1',
                'username' => 'tomato-user',
                'password' => 'tomato-pass',
                'language' => '1',
                'sender' => 'TomatoPHP',
                'mobile' => '201000000000',
                'message' => 'Your order is ready',
            ]);

        return true;
    });
});

it('sends on the test environment when it is selected', function () {
    config()->set('filament-sms-misr-driver.environment', 'test');

    dispatch(new NotifySmsMisrJob([
        'phone' => '201000000000',
        'message' => 'Hello',
        'title' => 'Hello',
    ]));

    Http::assertSent(fn (Request $request): bool => $request->data()['environment'] === '2');
});

it('normalises the phone number to the bare international format', function () {
    expect(SmsMisr::mobile('+20 (100) 000-0000'))->toBe('201000000000')
        ->and(SmsMisr::mobile('00201000000000'))->toBe('201000000000')
        ->and(SmsMisr::mobile('201000000000'))->toBe('201000000000');
});

it('can use FilamentAlerts Facade To Send An SMS To The User Phone', function () {
    $user = User::factory()->create(['phone' => '+201111111111']);
    $template = NotificationsTemplate::factory()->create();

    FilamentAlerts::notify($user)
        ->template($template->id)
        ->drivers([SmsMisrDriver::class])
        ->title([
            'name' => $user->name,
        ])
        ->body([
            'date' => now()->toDateTimeString(),
        ])
        ->send();

    Http::assertSent(function (Request $request) use ($template): bool {
        expect($request->data()['mobile'])->toBe('201111111111')
            ->and($request->data()['message'])->toBe($template->body);

        return true;
    });

    assertDatabaseHas('notifications_logs', [
        'title' => $template->title,
        'description' => $template->body,
        'provider' => 'sms-misr',
        'type' => 'info',
    ]);
});

it('skips sending when the notifiable has no phone number', function () {
    $user = User::factory()->create(['phone' => null]);
    $template = NotificationsTemplate::factory()->create();

    FilamentAlerts::notify($user)
        ->template($template->id)
        ->drivers([SmsMisrDriver::class])
        ->send();

    Http::assertNothingSent();
    assertDatabaseCount('notifications_logs', 0);
});

it('skips sending when SMS Misr is not configured', function () {
    config()->set('filament-sms-misr-driver.password', null);

    dispatch(new NotifySmsMisrJob([
        'phone' => '201000000000',
        'message' => 'Nobody should get this',
    ]));

    Http::assertNothingSent();
    assertDatabaseCount('notifications_logs', 0);
});

it('skips sending when the driver is turned off', function () {
    config()->set('filament-sms-misr-driver.active', false);

    dispatch(new NotifySmsMisrJob([
        'phone' => '201000000000',
        'message' => 'Nobody should get this',
    ]));

    Http::assertNothingSent();
    assertDatabaseCount('notifications_logs', 0);
});

it('skips sending when the sender id is missing', function () {
    config()->set('filament-sms-misr-driver.sender', null);

    dispatch(new NotifySmsMisrJob([
        'phone' => '201000000000',
        'message' => 'Nobody should get this',
    ]));

    Http::assertNothingSent();
});

it('can notify a model through the InteractsWithSmsMisr trait', function () {
    User::factory()->create(['phone' => '+201222222222'])
        ->notifySmsMisr('From the trait', 'Trait title');

    Http::assertSent(function (Request $request): bool {
        expect($request->data()['mobile'])->toBe('201222222222')
            ->and($request->data()['message'])->toBe('From the trait');

        return true;
    });
});
