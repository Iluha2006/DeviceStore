<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CartProduct;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
{
    use HandlesAuthorization;

    public function view(User $user, CartProduct $cartProduct)
    {
        return $user->id === $cartProduct->user_id;
    }
    public function viewAny(User $user)
    {
        return true;
    }
    public function add(User $user)
    {
        return $user !== null;
    }

    public function increment(User $user, CartProduct $cartProduct)
    {
        return $user->id === $cartProduct->user_id;
    }

    public function decrement(User $user, CartProduct $cartProduct)
    {
        return $user->id === $cartProduct->user_id;
    }

    public function remove(User $user, CartProduct $cartProduct)
    {
        return $user->id === $cartProduct->user_id;
    }
}