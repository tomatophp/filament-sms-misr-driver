![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-sms-misr-driver/master/art/3x1io-tomato-sms-misr-driver.jpg)

# Filament sms misr driver

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-sms-misr-driver/version.svg)](https://packagist.org/packages/tomatophp/filament-sms-misr-driver)
[![License](https://poser.pugx.org/tomatophp/filament-sms-misr-driver/license.svg)](https://packagist.org/packages/tomatophp/filament-sms-misr-driver)
[![Downloads](https://poser.pugx.org/tomatophp/filament-sms-misr-driver/d/total.svg)](https://packagist.org/packages/tomatophp/filament-sms-misr-driver)

SMS Misr Integration for Filament Alerts Sender

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


## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-sms-misr-driver-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="filament-sms-misr-driver-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-sms-misr-driver-lang"
```

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="filament-sms-misr-driver-migrations"
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](mailto:info@3x1.io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
