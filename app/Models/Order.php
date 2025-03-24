<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id','payment_method_id',
        'amount',
        'status'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    public function paymentMethod() {
        return $this->belongsTo(PaymentMethod::class);
    }
}
