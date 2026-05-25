<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'pickup_date',
        'pickup_time',
        'return_date',
        'return_time',
        'pickup_location',
        'dropoff_location',
        'car_type',
        'number_of_passengers',
        'price',
    ];
}
