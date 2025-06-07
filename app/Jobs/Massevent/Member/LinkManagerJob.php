<?php

namespace App\Jobs\Massevent\Member;

use App\Models\HR\Employee;
use App\Models\Massevent\Member;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class LinkManagerJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private Member $member)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!isset($this->member->manager_guid) || $this->member->manager_guid === '') {
            $this->member->setStatusAsUndefinedManagerGuid();
            $this->member->save();
            return;
        }

        $manager = Employee::firstOrCreate(['code' => $this->member->manager_guid]);
        $this->member->manager_id = $manager->id;
        $this->member->setStatusAsLinked();
        $this->member->save();
    }
}
