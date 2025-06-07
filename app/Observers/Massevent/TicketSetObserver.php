<?php

namespace App\Observers\Massevent;

use App\Models\Massevent\TicketSet;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;


class TicketSetObserver
{
    /**
     * Handle the TicketSet "created" event.
     */
    public function created(TicketSet $ticketSet): void
    {
        //
    }

    /**
     * Handle the TicketSet "updated" event.
     */
    public function updated(TicketSet $ticketSet): void
    {

    }

    /**
     * Handle the TicketSet "deleted" event.
     */
    public function deleted(TicketSet $ticketSet): void
    {
        //
    }

    /**
     * Handle the TicketSet "restored" event.
     */
    public function restored(TicketSet $ticketSet): void
    {
        //
    }

    /**
     * Handle the TicketSet "force deleted" event.
     */
    public function forceDeleted(TicketSet $ticketSet): void
    {
        //
    }
}
