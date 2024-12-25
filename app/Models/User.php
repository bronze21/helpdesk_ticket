<?php

/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Pennant\Concerns\HasFeatures;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, softDeletes, HasFeatures;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function scopeQueryRole($query, $role)
    {
        $query->join('role_users', 'role_users.user_id', '=', 'users.id')
        ->join('roles', 'roles.id', '=', 'role_users.role_id')->where('roles.slug', $role)
        ->select('users.*');
        return $query;
    }

    public function scopeQueryWithoutRole($query,array|null $notRole=null)
    {
        if(!$notRole){
            $query->whereDoesntHave('role');
        }else{
            $query->whereHas('role',function($query) use ($notRole){
                $query->whereNotIn('slug',$notRole);
            });
        }
        return $query;
    }

    public function assignRole($slug)
    {
        try {
            $role = Role::where('slug', $slug)->first();
            $role->users()->attach($this);
            return $this->role;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users','user_id','role_id');
    }

    public function role()
    {
        return $this->hasOneThrough(Role::class, RoleUser::class,'user_id','id','id','role_id');
    }

    public function tickets()
    {
        if($this->role->slug=='user'){
            return $this->hasMany(Ticket::class,'created_by');
        }else{
            return $this->belongsToMany(Ticket::class,TicketsAgent::class,'user_id','ticket_id')->withPivot(['isActive','due_date']);
        }
    }

    public function comments()
    {
        return $this->hasMany(TicketsComment::class);
    }
}
