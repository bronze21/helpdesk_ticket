<?php

/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

namespace App\Observers;

use App\Models\TicketsAgent;
use App\Models\TicketsHistory;
use DB;

class TicketsAgentObserver
{
    /**
     * Handle the TicketsAgent "created" event.
     */
    public function created(TicketsAgent $ticketsAgent): void
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $desc = TicketsHistory::$_actionDesc['join'];
            $desc = str_replace(':nama_user:', "[{$user->role->name}] {$user->name}", $desc);
            $newTicketHistory = new TicketsHistory();
            $newTicketHistory->user_id = $user->id;
            $newTicketHistory->ticket_id = $ticketsAgent->ticket->id;
            $newTicketHistory->action = "join";
            $newTicketHistory->description = $desc;
            $newTicketHistory->new_data = $ticketsAgent->toArray();
            $newTicketHistory->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Handle the TicketsAgent "updated" event.
     */
    public function updated(TicketsAgent $ticketsAgent): void
    {
        //
    }

    /**
     * Handle the TicketsAgent "deleted" event.
     */
    public function deleted(TicketsAgent $ticketsAgent): void
    {
        //
    }

    /**
     * Handle the TicketsAgent "restored" event.
     */
    public function restored(TicketsAgent $ticketsAgent): void
    {
        //
    }

    /**
     * Handle the TicketsAgent "force deleted" event.
     */
    public function forceDeleted(TicketsAgent $ticketsAgent): void
    {
        //
    }
}
