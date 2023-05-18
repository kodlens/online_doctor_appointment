<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaxPatient extends Model
{
    use HasFactory;


    protected $table = 'max_patients';
    protected $primaryKey = 'id';


    protected $fillable = [
        'max'
    ];


}
