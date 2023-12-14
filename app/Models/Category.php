<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function image(): Attribute
    {
        return Attribute::make(
            get: fn($value) => asset('/storage/categories/'.$value),
        );
    }
}
