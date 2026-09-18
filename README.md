![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-sms-misr-driver/master/arts/fadymondy-tomato-sms-misr-driver.jpg)

# Filament SMS Misr Driver

[![Dependabot Updates](https://github.com/tomatophp/filament-sms-misr-driver/actions/workflows/dependabot/dependabot-updates/badge.svg)](https://github.com/tomatophp/filament-sms-misr-driver/actions/workflows/dependabot/dependabot-updates)
[![PHP Code Styling](https://github.com/tomatophp/filament-sms-misr-driver/actions/workflows/fix-php-code-styling.yml/badge.svg)](https://github.com/tomatophp/filament-sms-misr-driver/actions/workflows/fix-php-code-styling.yml)
[![Tests](https://github.com/tomatophp/filament-sms-misr-driver/actions/workflows/tests.yml/badge.svg)](https://github.com/tomatophp/filament-sms-misr-driver/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-sms-misr-driver/version.svg)](https://packagist.org/packages/tomatophp/filament-sms-misr-driver)
[![License](https://poser.pugx.org/tomatophp/filament-sms-misr-driver/license.svg)](https://packagist.org/packages/tomatophp/filament-sms-misr-driver)
[![Downloads](https://poser.pugx.org/tomatophp/filament-sms-misr-driver/d/total.svg)](https://packagist.org/packages/tomatophp/filament-sms-misr-driver)

[SMS Misr](https://smsmisr.com) Integration for [Filament Alerts Sender](https://github.com/tomatophp/filament-alerts)

Send the body of your alert as an SMS to the phone number of the notifiable, through the SMS Misr API v2.

## Screenshots

| Light | Dark |
|-------|------|
| ![Settings](https://raw.githubusercontent.com/tomatophp/filament-sms-misr-driver/master/arts/settings-light.png) | ![Settings](https://raw.githubusercontent.com/tomatophp/filament-sms-misr-driver/master/arts/settings-dark.png) |
| ![Settings Hub](https://raw.githubusercontent.com/tomatophp/filament-sms-misr-driver/master/arts/settings-hub-light.png) | ![Settings Hub](https://raw.githubusercontent.com/tomatophp/filament-sms-misr-driver/master/arts/settings-hub-dark.png) |
| ![Driver](https://raw.githubusercontent.com/tomatophp/filament-sms-misr-driver/master/arts/drivers-light.png) | ![Driver](https://raw.githubusercontent.com/tomatophp/filament-sms-misr-driver/master/arts/drivers-dark.png) |

## Requirements

| Package version | Filament | Laravel     | PHP  |
|-----------------|----------|-------------|------|
| 5.x             | 5.x      | 12.x, 13.x  | 8.2+ |

## Installation

```bash
composer require tomatophp/filament-sms-misr-driver
```
after install your package please run this command

```bash
php artisan filament-sms-misr-driver:install
```

finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentSmsMisrDriver\FilamentSmsMisrDriverPlugin::make())
```

## Configuration

open the SMS Misr settings page from the settings hub and fill in your account username, password and the sender ID
approved on your account. The password is write-only, it is never sent back to the browser, and leaving it empty keeps
the value that is already stored. Pick the `test` environment to validate your integration without sending real messages.

you can also set the values from your `.env` file, a saved setting always wins over the env value:

```dotenv
SMS_MISR_USERNAME=your-username
SMS_MISR_PASSWORD=your-password
SMS_MISR_SENDER=YourSenderID
SMS_MISR_ENVIRONMENT=live
SMS_MISR_LANGUAGE=1
SMS_MISR_ACTIVE=true
SMS_MISR_PHONE_COLUMN=phone
```

`SMS_MISR_LANGUAGE` is `1` for English, `2` for Arabic and `3` for Unicode.

when the account is not configured, when the driver is switched off, or when the notifiable has no phone number,
nothing is sent and nothing is logged.

## Usage

the driver reads the phone number from the `phone` column of the notifiable, change `SMS_MISR_PHONE_COLUMN` if your
model keeps it under another name.

to set up any model to get notifications you

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use TomatoPHP\FilamentSmsMisrDriver\Traits\InteractsWithSmsMisr;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use InteractsWithSmsMisr;
    ...
```

### Queue

the notification is run on queue, so you must run the queue worker to send the notifications

```bash
php artisan queue:work
```

### Use Filament Native Notification

you can use the filament native notification and we add some `macro` for you

```php
use Filament\Notifications\Notification;

Notification::make('send')
    ->title('Test Notifications')
    ->body('This is a test notification')
    ->icon('heroicon-o-bell')
    ->color('success')
    ->sendUse(auth()->user(), \TomatoPHP\FilamentSmsMisrDriver\Services\SmsMisrDriver::class);

```

### Notification Service

to create a new template you can use template CRUD and make sure that the template key is unique because you will use it on every single notification.

### Send Notification

to send a notification you must use our helper SendNotification::class like

```php
use TomatoPHP\FilamentAlerts\Facades\FilamentAlerts;

FilamentAlerts::notify(User::first())
    ->template($template->id)
    ->title([
        "find-text" => "change with this"
    ])
    ->body([
        "find-text" => "change with this"
    ])
    ->drivers(\TomatoPHP\FilamentSmsMisrDriver\Services\SmsMisrDriver::class)
    ->send();
```

where `$template` is selected of the template by key or id, and title, body use to select and replace string on the template with custom data.
the body of the template is the text of the SMS. you can pass an explicit number with `->data(['phone' => '201000000000'])`
to send somewhere other than the phone of the notifiable.

### Notification Channels

it can be working with direct user methods like

```php
$user->notifySmsMisr(string $message, ?string $title = null, ?string $phone = null);
```

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-sms-misr-driver-config"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-sms-misr-driver-lang"
```

## Testing

if you like to run `PEST` testing just use this command

```bash
composer test
```

## Code Style

if you like to fix the code style just use this command

```bash
composer format
```

## PHPStan

if you like to check the code by `PHPStan` just use this command

```bash
composer analyse
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](mailto:info@3x1.io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
