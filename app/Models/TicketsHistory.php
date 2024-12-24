<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketsHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'ticket_id',
        'action',
        'description',
        'old_data',
        'new_data',
    ];

    protected $casts = [
        'old_data'=>'object',
        'new_data'=>'object'
    ];

    public $actionDesc = [
        'create_ticket'=>':nama_user: membuat tiket baru',
        'update_ticket'=>':nama_user: memperbarui tiket',
        'delete_ticket'=>':nama_user: menghapus tiket',
        'join'=>':nama_user: bergabung untung menyelesaikan tiket',
        'add_comment'=>':nama_user: menambahkan komentar',
        'edit_comment'=>':nama_user: mengubah Pesan',
        'delete_comment'=>':nama_user: menghapus Pesan',
    ];

    public static $_actionDesc = [
        'create_ticket'=>':nama_user: membuat tiket baru',
        'update_ticket'=>':nama_user: memperbarui tiket',
        'delete_ticket'=>':nama_user: menghapus tiket',
        'join'=>':nama_user: bergabung untung menyelesaikan tiket',
        'add_comment'=>':nama_user: menambahkan komentar',
        'edit_comment'=>':nama_user: mengubah Pesan',
        'delete_comment'=>':nama_user: menghapus Pesan',
    ];

    public function action():Attribute
    {
        return Attribute::make(
            get: function($value) {
                if($this->user!==null){
                    return str_replace(':nama_user:', $this->user->name, $this->actionDesc[$this->action]);
                }else{
                    return str_replace(":nama_user:", "Sistem ", $this->actionDesc[$this->action]);
                }
            }
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
