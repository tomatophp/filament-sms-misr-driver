<?php

namespace TomatoPHP\FilamentSmsMisrDriver\Settings;

use Spatie\LaravelSettings\Settings;

class SmsMisrSettings extends Settings
{
    public ?string $sms_misr_username = null;

    public ?string $sms_misr_password = null;

    public ?string $sms_misr_sender = null;

    public ?string $sms_misr_environment = 'live';

    public ?bool $sms_misr_active = true;

    public static function group(): string
    {
        return 'sms-misr';
    }
}
