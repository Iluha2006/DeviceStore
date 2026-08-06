<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['categoryname'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }


    public function favorites()
    {
        return $this->hasManyThrough(Favorite::class, Product::class);
    }


    public function cartItems()
    {
        return $this->hasManyThrough(CartProduct::class, Product::class);
    }
}
