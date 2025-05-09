<?php

namespace App\Features;

use App\Models\User;
use Illuminate\Support\Lottery;

class isAdminOrStaff
{
    public $name = 'isAdminOrStaff';
    /**
     * Resolve the feature's initial value.
     */
    public function resolve(User $user): mixed
    {
        return in_array($user->role->slug, ['admin','staff']);
    }
}
