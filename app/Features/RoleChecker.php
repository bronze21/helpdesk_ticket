<?php

namespace App\Features;

use App\Models\User;
use Illuminate\Support\Lottery;
use Laravel\Pennant\Contracts\FeatureScopeable;
use Log;

class RoleChecker
{
    public $name = "role";
    /**
     * Resolve the feature's initial value.
     */
    public function resolve(User $user, string $check=''): mixed
    {
        [$featureName, $role] = explode(':', $check);
        Log::debug($check);
        return $user->role->slug==$role ?? false;
    }
}
