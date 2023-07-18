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
        'status',
        'is_notify',
        'is_archived',
        'is_arrived',
        'arrival_datetime',
        'is_served',
        'served_datetime',
        'is_noshow',
        'no_show_datetime'
    ];


    public function user(){
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function schedule(){
        return $this->hasOne(Schedule::class, 'schedule_id', 'schedule_id');
    }

    public function patients(){
        return $this->hasMany(Patient::class, 'appointment_id', 'appointment_id')
            ->leftJoin('provinces', 'patients.province', 'provinces.provCode')
            ->leftJoin('cities', 'patients.city', 'cities.citymunCode')
            ->leftJoin('barangays', 'patients.barangay', 'barangays.brgyCode');
    }

    
}
