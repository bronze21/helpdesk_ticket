<?php

/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

namespace App\Features;

use App\Models\User;
use Illuminate\Support\Lottery;

class isStaff
{
    public $name = 'isStaff';
    /**
     * Resolve the feature's initial value.
     */
    public function resolve(User $user): mixed
    {
        return $user->role->slug == 'staff' ? true : false;
    }
}
