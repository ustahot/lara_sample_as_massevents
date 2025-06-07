<?php

namespace App\Jobs\Eventbus;

use App\Models\Eventbus\EventInterface;
use App\UseCases\Eventbus\EventHandlerAbstract;
use App\UseCases\Eventbus\EventHandlerBuilder;
use App\UseCases\Eventbus\EventHandlerInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunEventHandlerJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly EventInterface $event)
    {
        //
    }

    /**
     * Execute the job.
     * @throws \Exception
     */
    public function handle(): void
    {
        EventHandlerBuilder::makeHandler(event: $this->event)->run();
    }
}
