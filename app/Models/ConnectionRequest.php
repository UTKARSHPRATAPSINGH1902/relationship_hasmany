<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectionRequest extends Model {
    use HasFactory;
    protected $fillable = ['connection_id', 'user_id', 'auth_code'];
}