<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Order $order)
    {
        return $user->id == $order->user_id
            || $user->is_admin;
    }

    public function edit(User $user)
    {
        return $user->is_admin;
    }
}
