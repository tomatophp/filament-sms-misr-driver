<?php

namespace TomatoPHP\FilamentSmsMisrDriver\Traits;

use TomatoPHP\FilamentSmsMisrDriver\Jobs\NotifySmsMisrJob;
use TomatoPHP\FilamentSmsMisrDriver\Services\SmsMisr;

trait InteractsWithSmsMisr
{
    /**
     * Send an SMS to the model's phone column through SMS Misr.
     */
    public function notifySmsMisr(
        string $message,
        ?string $title = null,
        ?string $phone = null
    ): void {
        dispatch(new NotifySmsMisrJob([
            'phone' => $phone ?: SmsMisr::phoneOf($this),
            'message' => $message,
            'title' => $title,
        ]));
    }
}
