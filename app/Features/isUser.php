<?php

namespace App\Features;

use App\Models\User;
use Illuminate\Support\Lottery;

class isUser
{
    public $name = 'isUser';
    /**
     * Resolve the feature's initial value.
     */
    public function resolve(User $user): mixed
    {
        return $user->role->slug == 'user' ? true : false;
    }
}
