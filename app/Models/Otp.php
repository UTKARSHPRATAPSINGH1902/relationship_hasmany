<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model {
    use HasFactory;
    
    protected $fillable = ['phone', 'otp', 'expired_on'];
    protected $dates = ['expired_on']; // Ensure expired_on is treated as a date
}
