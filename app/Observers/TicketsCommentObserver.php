<?php

namespace App\Observers;

use App\Mail\TicketNotification;
use App\Models\TicketsComment;
use App\Models\TicketsHistory;
use DB;
use Mail;

class TicketsCommentObserver
{
    /**
     * Handle the TicketsComment "created" event.
     */
    public function created(TicketsComment $ticketsComment): void
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $desc = TicketsHistory::$_actionDesc['add_comment'];
            $desc = str_replace(':nama_user:', "[{$user->role->name}] {$user->name}", $desc);
            $newTicketHistory = new TicketsHistory();
            $newTicketHistory->user_id = auth()->user()->id;
            $newTicketHistory->ticket_id = $ticketsComment->ticket_id;
            $newTicketHistory->action = "add_comment";
            $newTicketHistory->description = $desc;
            $newTicketHistory->new_data = $ticketsComment->toArray();
            $newTicketHistory->save();
            DB::commit();
            $mailAccounts = [$ticketsComment->ticket->owner->email];
            if($user->role->slug=='user'){
                $mailAccounts = $ticketsComment->ticket->agents()->pluck('email')->toArray();
            }
            foreach($mailAccounts as $mailAccount){
                Mail::to($mailAccount)->send(new TicketNotification($ticketsComment));
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Handle the TicketsComment "updated" event.
     */
    public function updated(TicketsComment $ticketsComment): void
    {
        try {
            DB::beginTransaction();
            $dirtyColumns = $ticketsComment->getDirty();
            $newData = [];
            $oldData = [];
            foreach ($dirtyColumns as $column => $newValue) {
                $oldData[$column] = $ticketsComment->getOriginal($column);
                $newData[$column] = $newValue;
            }
            
            $user = auth()->user();
            $desc = TicketsHistory::$_actionDesc['update_ticket'];
            $desc = str_replace(':nama_user:', "[{$user->role->name}] {$user->name}", $desc);
            $newTicketHistory = new TicketsHistory();
            $newTicketHistory->user_id = $user->id;
            $newTicketHistory->ticket_id = $ticketsComment->ticket_id;
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
     * Handle the TicketsComment "deleted" event.
     */
    public function deleted(TicketsComment $ticketsComment): void
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $desc = TicketsHistory::$_actionDesc['delete_comment'];
            $desc = str_replace(':nama_user:', "[{$user->role->name}] {$user->name}", $desc);
            $newTicketHistory = new TicketsHistory();
            $newTicketHistory->user_id = auth()->user()->id;
            $newTicketHistory->ticket_id = $ticketsComment->ticket_id;
            $newTicketHistory->action = "delete_comment'";
            $newTicketHistory->description = $desc;
            $newTicketHistory->old_data = $ticketsComment->toArray();
            $newTicketHistory->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Handle the TicketsComment "restored" event.
     */
    public function restored(TicketsComment $ticketsComment): void
    {
        //
    }

    /**
     * Handle the TicketsComment "force deleted" event.
     */
    public function forceDeleted(TicketsComment $ticketsComment): void
    {
        //
    }
}
