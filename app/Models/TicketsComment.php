<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketsComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id','user_type', 'ticket_id', 'messages','isRead'];

    public function user()
    {
        return $this->belongsTo(User::class)->with('role');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function attachments()
    {
        return $this->belongsToMany(Attachment::class,TicketAttachments::class,'ticket_comment_id');
    }
}
