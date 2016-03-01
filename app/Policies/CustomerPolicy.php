<?php

namespace App\Policies;

use App\User;
use App\Customer;
use App\Http\Requests\Request;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Customer $customer)
    {
        return in_array($user->role, ['visitor', 'administrator']) && $user->id === $customer->user_id;
    }

    public function update(User $user, Customer $customer)
    {
        return $user->id === $customer->user_id;
    }
}
