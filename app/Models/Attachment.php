<?php

/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'upload_by',
        'path',
        'name',
        'type',
        'size',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'upload_by');
    }
}
