<?php

/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'created_by',
        'category_id',
        'subcategory_id',
        'title',
        'code',
        'priority',
        'status',
        'due_date',
    ];

    protected $casts = [
        'due_date'=>'datetime:Y-m-d H:i:s',
        'latest_update'=>'datetime:Y-m-d H:i:s',
    ];

    public $statusLabel = [
        'open' => "Menunggu Respon Agen",
        'on_going' => "Tiket Sedang Berjalan",
        'on_progress' => "Tiket Sedang Berjalan",
        'resolved' => "Tiket Selesai",
        'unresolved' => "Tiket Belum Selesai",
        'closed' => "Ticket di Tutup",
    ];
    
    public $statusColor = [
        'open' => "warning",
        'on_going' => "info",
        'on_progress' => "info",
        'resolved' => "success",
        'unresolved' => "warning",
        'closed' => "danger",
    ];

    public $priorityColor = [
        'low' => "info",
        'normal' => "warning",
        'high' => "danger",
    ];


    public static function getStatusLabel(string $str_status)
    {
        $listStatus = self::$statusLabel ?? [];
        if(!isset($listStatus[$str_status])) return false;
        return $listStatus[$str_status];
    }
    
    public static function getStatusColor(string $str_status)
    {
        $listColor = self::$statusColor ?? [];
        if(!isset($listColor[$str_status])) return false;
        return $listColor[$str_status];
    }

    public function getPriorityColor()
    {
        $listColor = self::$priorityColor ?? [];
        if(!isset($listColor[$this->priority])) return false;
        return $listColor[$this->priority];
    }

    public function tOptions():Attribute
    {
        return Attribute::make(
            get: fn ($value) => (object)[
                'status' => (object)[
                    'label'=>self::getStatusLabel($this->status),
                    'color'=>self::getStatusColor($this->status),
                ],
                'priority' => (object)[
                    'label'=>ucwords($this->priority),
                    'color'=>$this->getPriorityColor()
                ],
            ]
        );
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function histories()
    {
        return $this->hasMany(TicketsHistory::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function agents()
    {
        return $this->belongsToMany(User::class,TicketsAgent::class,'ticket_id','user_id')->withPivot(['isActive','due_date']);
    }

    public function activeAgent()
    {
        return $this->hasOneThrough(User::class,TicketsAgent::class,'user_id','id')->where('tickets_agents.isActive',1)->where('tickets_agents.due_date','>',now());
    }

    public function comments()
    {
        return $this->hasMany(TicketsComment::class);
    }

    public function scopeLatestUpdate($query)
    {
        return $query->select('tickets.*')
        ->selectRaw('MAX(th.created_at) as latest_update')
        ->join('tickets_histories as th', 'th.ticket_id', '=', 'tickets.id')
        ->groupBy('tickets.id');
    }

    public function getAvailableStatusAttribute()
    {
        $listStatus = [
            'open'=>[
                'unresolved',
                'closed'
            ],
            'on_progress'=>[
                'resolved',
                'unresolved',
                'closed',
            ],
            'resolved'=>[
                'open',
                'unresolved',
            ],
            'unresolved'=>[
                'open'
            ],
            'closed'=>[
                'open',
                'on_progress'
            ],
        ];
        return $listStatus[$this->status];
    }
}
