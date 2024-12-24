<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Models\TicketsHistory;
use DB;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $desc = TicketsHistory::$_actionDesc['create_ticket'];
            $desc = str_replace(':nama_user:', "[{$user->role->name}] {$user->name}", $desc);
            $newTicketHistory = new TicketsHistory();
            $newTicketHistory->user_id = auth()->user()->id;
            $newTicketHistory->ticket_id = $ticket->id;
            $newTicketHistory->action = "create_ticket";
            $newTicketHistory->description = $desc;
            $newTicketHistory->new_data = $ticket->toArray();
            $newTicketHistory->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        try {
            DB::beginTransaction();
            $dirtyColumns = $ticket->getDirty();
            $newData = [];
            $oldData = [];
            foreach ($dirtyColumns as $column => $newValue) {
                $oldData[$column] = $ticket->getOriginal($column);
                $newData[$column] = $newValue;
            }
            
            $user = auth()->user();
            $desc = TicketsHistory::$_actionDesc['update_ticket'];
            $desc = str_replace(':nama_user:', "[{$user->role->name}] {$user->name}", $desc);
            $newTicketHistory = new TicketsHistory();
            $newTicketHistory->user_id = $user->id;
            $newTicketHistory->ticket_id = $ticket->id;
            $newTicketHistory->action = "update_ticket";
            $newTicketHistory->description = $desc;
            $newTicketHistory->old_data = $oldData;
            $newTicketHistory->new_data = $newData;
            $newTicketHistory->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $desc = TicketsHistory::$_actionDesc['delete_ticket'];
            $desc = str_replace(':nama_user:', "[{$user->role->name}] {$user->name}", $desc);
            $newTicketHistory = new TicketsHistory();
            $newTicketHistory->user_id = auth()->user()->id;
            $newTicketHistory->ticket_id = $ticket->id;
            $newTicketHistory->action = "delete_ticket";
            $newTicketHistory->description = $desc;
            $newTicketHistory->old_data = $ticket->toArray();
            $newTicketHistory->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Handle the Ticket "restored" event.
     */
    public function restored(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "force deleted" event.
     */
    public function forceDeleted(Ticket $ticket): void
    {
        //
    }
}
