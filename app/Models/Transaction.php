<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function profits()
    {
        return $this->hasMany(Profit::class);
    }


    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d-M-Y h:i:s'),
        );
    }
}
