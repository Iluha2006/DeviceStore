<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{

    public $incrementing = true;
    protected $keyType = 'integer';
    protected $fillable = [

        'name',
        'description',
        'price',
        'discount',
        'imageUrl',
        'quantity',
        'category_id'
    ];

public function cartprod(): \Illuminate\Database\Eloquent\Relations\BelongsTo
   {
       return $this->hasMany(CartProduct::class);
   }
   public function reviews()
   {
       return $this->hasMany(ProductReview::class);
   }
   public function category(): BelongsTo
   {
       return $this->belongsTo(Category::class);
   }
}
