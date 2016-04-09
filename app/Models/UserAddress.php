<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'address2',
        'password',
        'city',
        'state',
        'country',
        'postal_code',
        'phone'
    ];
}
