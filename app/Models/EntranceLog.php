<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntranceLog extends Model
{
    use HasFactory;

    protected $table = 'entrance_logs';
    protected $primaryKey = 'entrance_log_id';


    protected $fillable = [
        'student_id', 
        'fullname', 
        'program', 
        'year_level', 
        'contact_no', 
        'date_entry'
    ];
}
