<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Favorite;
use Illuminate\Auth\Access\HandlesAuthorization;

class FavoritePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Favorite $favorite)
    {
        return $user->id === $favorite->user_id;
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function add(User $user)
    {
        return $user !== null;
    }

    public function delete(User $user, Favorite $favorite)
    {
        return $user->id === $favorite->user_id;
    }
}