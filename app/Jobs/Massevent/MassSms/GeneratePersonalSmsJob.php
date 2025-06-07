<?php

namespace App\Jobs\Massevent\MassSms;

use App\Models\Massevent\MassSmsByPersonal;
use App\Services\SMS\Integrations\StreamSmsSimple\StreamSmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GeneratePersonalSmsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private MassSmsByPersonal $smsByPersonal)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = new StreamSmsService();
        $this->smsByPersonal->send(service: $service); // todo фактическая отправка сервисом раскоментить на бою
    }
}
