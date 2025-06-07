<?php

namespace App\Observers\Massevent;

use App\Models\CRM\Contact;
use App\Models\Massevent\Member;
use App\Models\Massevent\MemberStatus;

class MemberObserver
{
    /**
     * Handle the Member "created" event.
     */
    public function created(Member $member): void
    {
//        Contact::create([
//            'source_id' => 2,
//            'name' => $member->name_for_massevent,
//            'code' => $member->phone_for_massevent,
//        ]);

    }

    /**
     * Handle the Member "updated" event.
     */
    public function updated(Member $member): void
    {
    }

    /**
     * Handle the Member "deleted" event.
     */
    public function deleted(Member $member): void
    {
        //
    }

    /**
     * Handle the Member "restored" event.
     */
    public function restored(Member $member): void
    {
        //
    }

    /**
     * Handle the Member "force deleted" event.
     */
    public function forceDeleted(Member $member): void
    {
        //
    }
}
