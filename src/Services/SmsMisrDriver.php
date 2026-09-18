<?php

namespace TomatoPHP\FilamentSmsMisrDriver\Services;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentAlerts\Services\Drivers\Driver;
use TomatoPHP\FilamentSmsMisrDriver\Jobs\NotifySmsMisrJob;

class SmsMisrDriver extends Driver
{
    public function setup(): void
    {
        // TODO: Implement setup() method.
    }

    public function sendIt(
        string $title,
        string $model,
        int | string | null $modelId = null,
        ?string $body = null,
        ?string $url = null,
        ?string $icon = null,
        ?string $image = null,
        ?string $type = 'info',
        ?string $action = 'system',
        ?array $data = [],
        ?int $template_id = null,
        ?Notification $notification = null
    ): void {
        dispatch(new NotifySmsMisrJob([
            // An explicit phone on the data bag wins, otherwise it is read from the notifiable.
            'phone' => $data['phone'] ?? SmsMisr::phoneOf($this->resolveNotifiable($model, $modelId)),
            'message' => $body,
            'title' => $title,
        ]))->onQueue(config('filament-alerts.queue'));
    }

    protected function resolveNotifiable(string $model, int | string | null $modelId): ?Model
    {
        if (blank($modelId) || ! is_subclass_of($model, Model::class)) {
            return null;
        }

        return $model::query()->find($modelId);
    }
}
