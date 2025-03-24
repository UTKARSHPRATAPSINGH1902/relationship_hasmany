<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status'];

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_payment_method');
    }
}