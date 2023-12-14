<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => asset('storage/products/'.$value),
        );
    }
}
