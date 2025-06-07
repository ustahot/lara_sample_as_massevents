<?php

namespace App\Jobs\Massevent\MassSms;

use App\Models\Massevent\MassSms;
use App\UseCases\Massevent\MassSmsToPersonalSmsBuilder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class MassSmsToPersonalJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly array $validated)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $massSms = MassSms::find($this->validated['mass_sms_id']);
        $builder = new MassSmsToPersonalSmsBuilder($massSms);
        $builder->generatePersonalSmsJobs();
    }
}
