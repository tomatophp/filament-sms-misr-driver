<?php

namespace TomatoPHP\FilamentSmsMisrDriver;

use Filament\Contracts\Plugin;
use Filament\Panel;
use TomatoPHP\FilamentAlerts\Facades\FilamentAlerts;
use TomatoPHP\FilamentAlerts\Services\Concerns\NotificationDriver;
use TomatoPHP\FilamentSettingsHub\Facades\FilamentSettingsHub;
use TomatoPHP\FilamentSettingsHub\Services\Contracts\SettingHold;
use TomatoPHP\FilamentSmsMisrDriver\Filament\Pages\SmsMisrSettingsPage;
use TomatoPHP\FilamentSmsMisrDriver\Services\SmsMisrDriver;

class FilamentSmsMisrDriverPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-sms-misr-driver';
    }

    public function register(Panel $panel): void
    {
        if (class_exists(FilamentSettingsHub::class) && $panel->getPlugin('filament-alerts')->useSettingsHub) {
            $panel->pages([
                SmsMisrSettingsPage::class,
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
        if (class_exists(FilamentSettingsHub::class) && filament('filament-alerts')->useSettingsHub) {
            FilamentSettingsHub::register([
                SettingHold::make()
                    ->label('filament-sms-misr-driver::messages.settings.sms_misr.title')
                    ->icon('bxs-message-detail')
                    ->page(SmsMisrSettingsPage::class)
                    ->order(2)
                    ->description('filament-sms-misr-driver::messages.settings.sms_misr.description')
                    ->group('filament-alerts::messages.settings.group'),
            ]);
        }

        FilamentAlerts::register(
            NotificationDriver::make('sms-misr')
                ->label('SMS Misr')
                ->driver(SmsMisrDriver::class)
        );
    }

    public static function make(): self
    {
        return new FilamentSmsMisrDriverPlugin;
    }
}
