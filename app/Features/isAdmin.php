<?php

namespace App\Features;

use App\Models\User;
use Illuminate\Support\Lottery;

class isAdmin
{
    public $name = 'isAdmin';
    /**
     * Resolve the feature's initial value.
     */
    public function resolve(User $user): mixed
    {
        return $user->role->slug == 'admin' ? true : false;
    }
}
