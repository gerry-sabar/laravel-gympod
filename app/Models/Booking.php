<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 
        'user_id', 
        'pod_id',
        'phone',
        'status',
        'booking_datetime', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }    

    public function pod(){
        return $this->belongsTo(Pod::class);
    }

}
