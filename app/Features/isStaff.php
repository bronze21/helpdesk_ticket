<?php

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
