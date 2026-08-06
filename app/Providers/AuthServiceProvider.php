<?php

namespace App\Providers;

use App\Models\CartProduct;
use App\Policies\CartPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        CartProduct::class => CartPolicy::class,
        Favorite::class => FavoritePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}