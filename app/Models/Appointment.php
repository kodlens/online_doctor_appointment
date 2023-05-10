<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';
    protected $primaryKey = 'appointment_id';


    protected $fillable = ['user_id', 
        'schedule_id', 
        'appointment_date', 
        'illness_history', 
        'status',
        'is_notify'
    ];


    public function user(){
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function schedule(){
        return $this->hasOne(Schedule::class, 'schedule_id', 'schedule_id');
    }

    
}
