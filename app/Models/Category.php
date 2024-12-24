<?php

/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name','slug','isActive'];

    public function subCategories()
    {
        return $this->hasMany(Subcategory::class,'category_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
