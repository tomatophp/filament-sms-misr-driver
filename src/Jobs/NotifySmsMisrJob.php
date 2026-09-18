<?php

namespace TomatoPHP\FilamentSmsMisrDriver\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use TomatoPHP\FilamentAlerts\Models\NotificationsLogs;
use TomatoPHP\FilamentSmsMisrDriver\Services\SmsMisr;

class NotifySmsMisrJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public ?string $phone;

    public ?string $title;

    public ?string $message;

    /**
     * Create a new notification instance.
     *
     * @param  array{phone: ?string, message: ?string, title?: ?string}  $arg
     */
    public function __construct(array $arg)
    {
        $this->phone = $arg['phone'];
        $this->message = $arg['message'];
        $this->title = $arg['title'] ?? null;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // An unconfigured gateway or a notifiable without a phone number is skipped quietly.
        if (blank($this->phone) || blank($this->message) || ! SmsMisr::isConfigured()) {
            return;
        }

        SmsMisr::send($this->phone, $this->message);

        $log = new NotificationsLogs;
        $log->title = $this->title ?: $this->message;
        $log->description = $this->message;
        $log->provider = 'sms-misr';
        $log->type = 'info';
        $log->save();
    }
}
