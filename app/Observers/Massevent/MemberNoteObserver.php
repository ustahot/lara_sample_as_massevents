<?php

namespace App\Observers\Massevent;

use App\Models\Massevent\MemberNote;
use App\Models\Massevent\MemberStatus;
use App\UseCases\Massevent\Statuses\MemberStatusInvited;

class MemberNoteObserver
{
    /**
     * Handle the MemberNote "created" event.
     */
    public function created(MemberNote $memberNote): void
    {
        $member = $memberNote->member;

        if (null !== $member->status) {
            return;
        }

        // По факту создания примечания, меняем статус на приглашен, при условии, что ранее статус
        // этому участнику не был установлен
        MemberStatus::set($member, MemberStatusInvited::getCode());
    }

    /**
     * Handle the MemberNote "updated" event.
     */
    public function updated(MemberNote $memberNote): void
    {
        //
    }

    /**
     * Handle the MemberNote "deleted" event.
     */
    public function deleted(MemberNote $memberNote): void
    {
        //
    }

    /**
     * Handle the MemberNote "restored" event.
     */
    public function restored(MemberNote $memberNote): void
    {
        //
    }

    /**
     * Handle the MemberNote "force deleted" event.
     */
    public function forceDeleted(MemberNote $memberNote): void
    {
        //
    }
}
