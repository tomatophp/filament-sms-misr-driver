<?php

namespace TomatoPHP\FilamentSmsMisrDriver\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class SmsMisr
{
    /**
     * The SMS Misr code returned for a message that was accepted.
     */
    public const SUCCESS_CODE = '1901';

    /**
     * Whether the driver is enabled and the account credentials are complete.
     */
    public static function isConfigured(): bool
    {
        if (! config('filament-sms-misr-driver.active')) {
            return false;
        }

        return filled(config('filament-sms-misr-driver.username'))
            && filled(config('filament-sms-misr-driver.password'))
            && filled(config('filament-sms-misr-driver.sender'));
    }

    /**
     * Send an SMS through the SMS Misr API v2.
     */
    public static function send(string $phone, string $message): ?Response
    {
        if (! static::isConfigured() || blank($phone) || blank($message)) {
            return null;
        }

        return Http::asJson()->post((string) config('filament-sms-misr-driver.endpoint'), [
            'environment' => static::environment(),
            'username' => (string) config('filament-sms-misr-driver.username'),
            'password' => (string) config('filament-sms-misr-driver.password'),
            'language' => (string) config('filament-sms-misr-driver.language'),
            'sender' => (string) config('filament-sms-misr-driver.sender'),
            'mobile' => static::mobile($phone),
            'message' => $message,
        ]);
    }

    /**
     * SMS Misr expects `1` for the live environment and `2` for the test one.
     */
    public static function environment(): string
    {
        return config('filament-sms-misr-driver.environment') === 'test' ? '2' : '1';
    }

    /**
     * SMS Misr expects a bare international number, so `+20…` and `0020…` are normalised to `20…`.
     */
    public static function mobile(string $phone): string
    {
        return (string) str($phone)
            ->replaceMatches('/[^0-9+]/', '')
            ->ltrim('+')
            ->replaceMatches('/^00/', '');
    }

    /**
     * Read the phone number of a notifiable model.
     */
    public static function phoneOf(?Model $model): ?string
    {
        $column = (string) config('filament-sms-misr-driver.phone_column');

        $phone = $model?->getAttribute($column);

        return is_scalar($phone) ? (string) $phone : null;
    }
}
