<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAttachments extends Model
{
    use HasFactory;

    protected $table = 'ticket_attachments';
    protected $fillable = [
        'user_id',
        'ticket_id',
        'attachment_id',
        'ticket_comment_id'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }

    public function ticket_comment()
    {
        return $this->belongsTo(TicketsComment::class, 'ticket_comment_id');
    }
}
