<?php

namespace TomatoPHP\FilamentSmsMisrDriver\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Artisan;
use TomatoPHP\FilamentSettingsHub\Pages\SettingsHub;
use TomatoPHP\FilamentSmsMisrDriver\Settings\SmsMisrSettings;

class SmsMisrSettingsPage extends SettingsPage
{
    protected static BackedEnum | null | string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = SmsMisrSettings::class;

    /**
     * Settings that hold a credential and must never be sent back to the browser.
     *
     * @var array<int, string>
     */
    protected const SECRETS = [
        'sms_misr_password',
    ];

    public function getTitle(): string
    {
        return trans('filament-sms-misr-driver::messages.settings.sms_misr.title');
    }

    protected function getActions(): array
    {
        return [
            Action::make('back')->url(SettingsHub::getUrl()),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function form(Schema $form): Schema
    {
        return $form->columns(1)
            ->schema([
                Section::make()->schema([
                    TextInput::make('sms_misr_username')
                        ->label(trans('filament-sms-misr-driver::messages.settings.sms_misr.username'))
                        ->hint(config('filament-alerts.show_hint') ? 'setting("sms_misr_username")' : null),
                    TextInput::make('sms_misr_password')
                        ->password()
                        ->revealable(false)
                        ->autocomplete('new-password')
                        ->label(trans('filament-sms-misr-driver::messages.settings.sms_misr.password'))
                        ->helperText(trans('filament-sms-misr-driver::messages.settings.sms_misr.keep_secret'))
                        ->hint(config('filament-alerts.show_hint') ? 'setting("sms_misr_password")' : null),
                    TextInput::make('sms_misr_sender')
                        ->label(trans('filament-sms-misr-driver::messages.settings.sms_misr.sender'))
                        ->helperText(trans('filament-sms-misr-driver::messages.settings.sms_misr.sender_help'))
                        ->hint(config('filament-alerts.show_hint') ? 'setting("sms_misr_sender")' : null),
                    Select::make('sms_misr_environment')
                        ->options([
                            'live' => trans('filament-sms-misr-driver::messages.settings.sms_misr.environments.live'),
                            'test' => trans('filament-sms-misr-driver::messages.settings.sms_misr.environments.test'),
                        ])
                        ->default('live')
                        ->selectablePlaceholder(false)
                        ->label(trans('filament-sms-misr-driver::messages.settings.sms_misr.environment'))
                        ->hint(config('filament-alerts.show_hint') ? 'setting("sms_misr_environment")' : null),
                    Toggle::make('sms_misr_active')
                        ->label(trans('filament-sms-misr-driver::messages.settings.sms_misr.active'))
                        ->hint(config('filament-alerts.show_hint') ? 'setting("sms_misr_active")' : null),
                ]),
            ]);
    }

    /**
     * Secrets never leave the server, the password input always starts empty.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach (static::SECRETS as $secret) {
            $data[$secret] = null;
        }

        return $data;
    }

    /**
     * A blank secret input keeps the value already stored.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $settings = app(static::getSettings());

        foreach (static::SECRETS as $secret) {
            if (blank($data[$secret] ?? null)) {
                $data[$secret] = $settings->{$secret};
            }
        }

        return $data;
    }

    public function afterSave(): void
    {
        Artisan::call('cache:clear');
    }
}
